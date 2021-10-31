@if ($media->isImage())
    <img src="{{ $media->getUrl() }}" width="{{ $media->width }}" height="{{ $media->height }}" alt="Preview" />
@elseif ($media->isVideo())
    <img src="{{ $media->getUrl('thumb') }}" alt="Video Preview" />
@endif
