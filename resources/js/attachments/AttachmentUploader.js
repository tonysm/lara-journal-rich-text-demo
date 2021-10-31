class FileAttachmentUploader {
    constructor(attachment, uploader, filename = 'attachment') {
        this.attachment = attachment;
        this.uploader = uploader;
        this.filename = filename;
    }

    start() {
        this.uploader.upload(
            this.attachment.file,
            this.filename,
            this,
        );
    }

    onProgress({ event }) {
        this.attachment.setUploadProgress(
            this.computeProgress(event)
        );
    }

    onSuccess(response) {
        this.attachment.setAttributes(
            this.makeAttributes(response.data)
        );
    }

    onError(error) {
        this.attachment.setAttributes({
            url: null,
            href: null,
        });
    }

    computeProgress(event) {
        return event.loaded / event.total * 100;
    }

    makeAttributes(data) {
        return {
            sgid: data.sgid,
            url: data.url,
        };
    }
}

class VideoAttachmentUploader extends FileAttachmentUploader {
    start() {
        this.attachment.setAttributes({
            url: 'placeholder',
            content: this.makeVideoPreview(),
        });

        super.start();
    }

    onProgress({ event }) {
        this.attachment.setAttributes({
            content: this.makeVideoPreview(this.computeProgress(event)),
        });
    }

    onSuccess(response) {
        this.attachment.setAttributes({
            ...this.makeAttributes(response.data),
            previewable: true,
            content: response.data.content,
        });
    }

    makeVideoPreview(progress = 0) {
        progress = progress + '%';

        return `
            <div class="flex items-center space-x-2 border rounded-md" style="padding: 5px !important;">
                <div class="flex items-center justify-center w-12 h-12 bg-gray-200 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </div>
                <div class="flex flex-col w-[150px]">
                    <span class="text-gray-800 truncate">${this.file.name}</span>
                    <span class="text-sm text-gray-400">${this.attachment.getFormattedFilesize()}</span>
                </div>
                <div class="flex-1 relative max-w-[200px]">
                    <div class="w-full h-5 bg-gray-100 rounded"></div>
                    <div class="absolute top-0 bottom-0 z-10 h-5 bg-green-400 rounded" style="width: ${progress}"></div>
                </div>
            </div>
        `;
    }

    get file() {
        return this.attachment.file;
    }
}

export default class AttachmentUploader {
    static factory(attachment, url) {
        if (attachment.file.type.includes('video')) {
            return new VideoAttachmentUploader(attachment, new AttachmentUploader(url));
        }

        return new FileAttachmentUploader(attachment, new AttachmentUploader(url));
    }

    constructor(url) {
        this.url = url;
    }

    upload(file, filename, delegate) {
        const data = new FormData();
        data.append(filename, file);

        window.axios
            .post(this.url, data, {
                onUploadProgress: (event) => delegate.onProgress({ event }),
            })
            .then((resp) => delegate.onSuccess(resp))
            .catch((error) => delegate.onError(error));
    }
}
