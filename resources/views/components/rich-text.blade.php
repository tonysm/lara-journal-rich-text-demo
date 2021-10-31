@props(['disabled' => false, 'name', 'id', 'value'])

<input type="hidden" name="{{ $name }}" id="{{ $id }}_input" value="{{ $value }}">
<trix-editor
    id="{{ $id }}"
    input="{{ $id }}_input"
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => 'trix-content border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm']) !!}
    data-controller="trix"
    data-action="trix-attachment-add->trix#attachmentUpload"
    data-trix-upload-url-value="{{ route('attachments.store') }}"
></trix-editor>
