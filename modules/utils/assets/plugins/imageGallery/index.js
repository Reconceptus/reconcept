$(function () {
    if(typeof ($.Redactor)!=='undefined') {
        $.Redactor.prototype.imageGallery = function () {
            return {
                getTemplate: function () {
                    return String()
                        + '<input type="file" id="modal-image-insert-field" name="galleries[]" accept="image/*" multiple>'
                },
                init: function () {
                    if (!this.opts.imageUploadPath) return;

                    this.galleryGuid = '';
                    this.galleryType = '';

                    //Create custom button in redactor bar
                    const button = this.button.add('imageGallery', 'Create Image Gallery');
                    this.button.setAwesome('imageGallery', 'fa fa-file-image-o');
                    this.button.addCallback(button, this.imageGallery.openModal);
                },
                openModal: function () {
                    this.selection.save();

                    //Create modal with the image upload form
                    this.modal.addTemplate('imageGallery', this.imageGallery.getTemplate());
                    this.modal.load('imageGallery', 'Upload Images and chose gallery template', 700);

                    //Init image upload function
                    this.imageGallery.initImageUploadForm();

                    //Create CTA buttons set
                    const createGalleryButton = this.modal.createActionButton('Add gallery');
                    createGalleryButton.on('click', this.imageGallery.createGallery);

                    this.modal.show();
                },
                initImageUploadForm: function () {
                    this.modalImageUploadingArea = jQuery('#modal-image-insert-field');

                    if (!this.modalImageUploadingArea) {
                        return;
                    }
                    this.galleryGuid = this.imageGallery.generateGalleryId(10);
                    this.modalImageUploadingArea.fileinput({
                        uploadUrl: this.opts.imageUploadPath,
                        maxFileCount: 40,
                        allowedFileTypes: ['image'],
                        showCancel: false,
                        showClose: false,
                        browseOnZoneClick: true,
                        initialPreviewAsData: true,
                        overwriteInitial: false,
                        showUpload: false,
                        showConsoleLogs: false,
                        theme: 'fas',
                        fileActionSettings: {
                            showZoom: false,
                        },
                        uploadExtraData: {
                            guid: this.galleryGuid,
                            type: this.galleryType,
                            field: "galleries"
                        }
                    });
                },
                generateGalleryId: function (length) {
                    var result = '';
                    var characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
                    var charactersLength = characters.length;
                    for (var i = 0; i < length; i++) {
                        result += characters.charAt(Math.floor(Math.random() * charactersLength));
                    }
                    return result;
                },
                postImageList: function (type) {
                    this.galleryType = type;

                    this.modalImageUploadingArea.fileinput('upload');
                    this.modal.close();
                    this.selection.restore();
                    this.insert.text('{{'+ this.galleryGuid + ';' +'}}');
                },
                createGallery: function () {
                    if (this.modalImageUploadingArea && this.modalImageUploadingArea[0].files.length > 0) {
                        this.imageGallery.postImageList('gallery');
                    }
                },
            };
        };
    }
});
