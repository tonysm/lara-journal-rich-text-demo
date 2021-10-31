<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseModel;
use Tonysm\RichTextLaravel\Attachables\Attachable;
use Tonysm\RichTextLaravel\Attachables\AttachableContract;

class Media extends BaseModel implements AttachableContract
{
    use HasFactory;
    use Attachable;

    protected $appends = [
        'sgid',
        'attachable_sgid',
    ];

    public function isImage(): bool
    {
        return str_contains($this->mime_type, 'image');
    }

    public function isVideo(): bool
    {
        return str_contains($this->mime_type, 'video');
    }

    public function isFile(): bool
    {
        return ! $this->isImage() && ! $this->isVideo();
    }

    public function extension(): string
    {
        return Str::afterLast($this->file_name, '.');
    }

    public function getSgidAttribute()
    {
        return $this->richTextSgid();
    }

    public function getAttachableSgidAttribute()
    {
        return $this->model->attachable_sgid;
    }

    public function richTextPreviewable()
    {
        return $this->isImage() || $this->isVideo();
    }

    public function attachmentUrl(): string
    {
        return route('attachments.show', $this->richTextSgid());
    }

    public function trixAttachmentUrl()
    {
        if ($this->isVideo()) {
            return $this->getUrl('thumb');
        }

        return $this->getUrl();
    }

    public function toTrixContent(): ?string
    {
        if (! $this->richTextPreviewable()) {
            return null;
        }

        return view('medias._trix-media-editor', [
            'media' => $this,
        ])->render();
    }

    public function richTextRender(array $options = []): string
    {
        return view('medias._trix-media', [
            'media' => $this,
            'options' => $options,
        ])->render();
    }
}
