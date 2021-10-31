@props(['disabled' => false, 'name', 'id', 'value'])

<input type="hidden" name="{{ $name }}" id="{{ $id }}_input" value="{{ $value }}">
<trix-editor
    id="{{ $id }}"
    input="{{ $id }}_input"
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => 'trix-content border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm']) !!}
    x-data="{
        upload(event) {
            if (! event?.attachment?.file) {
                return;
            }

            this._uploadFile(event.attachment);
        },

        _uploadFile(attachment) {
            const form = new FormData();
            form.append('attachment', attachment.file);

            window.axios.post('/attachments', form, {
                onUploadProgress: (progressEvent) => {
                    attachment.setUploadProgress(progressEvent.loaded / progressEvent.total * 100);
                }
            }).then(({ data }) => {
                attachment.setAttributes({
                    sgid: data.sgid,
                    url: data.url,
                });
            });
        },
    }"
    x-on:trix-attachment-add="upload"
></trix-editor>
