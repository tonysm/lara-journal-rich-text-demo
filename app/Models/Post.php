<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tonysm\GlobalId\Models\HasGlobalIdentification;
use Tonysm\RichTextLaravel\Attachment;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;

class Post extends Model
{
    use HasFactory;
    use HasRichText;
    use HasGlobalIdentification;

    protected $guarded = [];

    protected $richTextFields = [
        'content',
    ];

    protected $appends = [
        'sgid',
        'attachable_sgid',
    ];

    public static function booted()
    {
        static::saved(function (Post $post) {
            $post->content->attachments()
                ->filter(function (Attachment $attachment) {
                    return $attachment->attachable instanceof Media;
                })
                ->each(function (Attachment $attachment) {
                    $attachment->attachable->setCustomProperty('caption', $attachment->node->getAttribute('caption'));
                    $attachment->attachable->setCustomProperty('width', $attachment->node->getAttribute('width'));
                    $attachment->attachable->setCustomProperty('height', $attachment->node->getAttribute('height'));
                    $attachment->attachable->save();
                });
        });
    }

    public function getSgidAttribute()
    {
        return $this->toSignedGlobalId();
    }

    public function getAttachableSgidAttribute()
    {
        return $this->toSignedGlobalId([
            'for' => 'rich-text-laravel',
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
