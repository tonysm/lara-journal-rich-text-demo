<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Tonysm\GlobalId\Models\HasGlobalIdentification;

class Team extends JetstreamTeam implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasGlobalIdentification;

    /**
     * The attributes that should be cast.search
     *
     * @var array
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
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

    public function getAttachableSgidAttribute()
    {
        return $this->toSignedGlobalId([
            'for' => 'rich-text-laravel',
        ])->toString();
    }

    public function addAttachment(UploadedFile $file): Media
    {
        return $this->addMedia($file)->toMediaCollection('trix-attachments');
    }
}
