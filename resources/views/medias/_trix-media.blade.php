<figure class="attachment attachment--{{ $media->richTextPreviewable() ? 'preview' : 'file' }} attachment--{{ $media->extension() }}" @if ($options['in_gallery'] ?? false) data-trix-attributes='{"presentation":"gallery"}' @endif>
    @if ($media->isImage())
        <img src="{{ $media->getUrl() }}" width="{{ $media->getCustomProperty('width') }}" height="{{ $media->getCustomProperty('height') }}" />
    @elseif ($media->isVideo())
        <video poster="{{ $media->getUrl('thumb') }}" controls>
            <source src="{{ $media->getUrl() }}" type="{{ $media->mime_type }}" />
        </video>
    @endif

    <figcaption class="attachment__caption">
        @if ($media->getCustomProperty('caption'))
            {{ $media->getCustomProperty('caption') }}
        @else
            <span class="attachment__name">{{ $media->file_name }}</spanp>
            <span class="attachment__size">{{ $media->human_readable_size }}</span>
        @endif
    </figcaption>
</figure>
