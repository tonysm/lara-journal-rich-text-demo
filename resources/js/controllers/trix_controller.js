import { Controller } from "@hotwired/stimulus";
import AttachmentUploader from "../attachments/AttachmentUploader";

export default class extends Controller {
    static values = {
        uploadUrl: String,
    };

    attachmentUpload(event) {
        AttachmentUploader
            .factory(event.attachment, this.uploadUrlValue)
            .start();
    }

    attachmentRemoved(event) {
        // Not implemented...
    }
}
