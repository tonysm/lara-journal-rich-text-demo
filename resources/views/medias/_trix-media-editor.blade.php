@if ($media->isImage())
    <img src="{{ $media->getUrl() }}" width="{{ $media->getCustomProperty('width') }}" height="{{ $media->getCustomProperty('height') }}" alt="Preview" />
@elseif ($media->isVideo())
    <img src="{{ $media->getUrl('thumb') }}" width="300" height="200" alt="Video Preview" />
@endif
