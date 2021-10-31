<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Tonysm\GlobalId\Models\HasGlobalIdentification;
use Tonysm\RichTextLaravel\Attachment;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use HasRichText;
    use HasGlobalIdentification;
    use InteractsWithMedia;

    protected $guarded = [];

    protected $richTextFields = [
        'content',
    ];

    protected $appends = [
        'sgid',
        'attachable_sgid',
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('trix-attachments')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(600)
                    ->height(400);
            });
    }

    public static function booted()
    {
        static::saved(function (Post $post) {
            $post->content->attachments()
                ->filter(function (Attachment $attachment) {
                    return $attachment->attachable instanceof Media;
                })
                ->each(function (Attachment $attachment) use ($post) {
                    if ($attachment->attachable->model->isNot($post)) {
                        $attachment->attachable->model()->associate($post);
                    }

                    $attachment->attachable->setCustomProperty('caption', $attachment->node->getAttribute('caption'));
                    $attachment->attachable->setCustomProperty('width', $attachment->node->getAttribute('width'));
                    $attachment->attachable->setCustomProperty('height', $attachment->node->getAttribute('height'));
                    $attachment->attachable->save();
                });
        });
    }

    public function getSgidAttribute()
    {
        return $this->toSignedGlobalId()->toParam();
    }

    public function getAttachableSgidAttribute()
    {
        return $this->toSignedGlobalId([
            'for' => 'rich-text-laravel',
        ])->toParam();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
