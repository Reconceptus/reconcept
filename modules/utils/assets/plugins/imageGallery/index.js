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
                    const createSliderButton = this.modal.createActionButton('Slider');
                    createSliderButton.on('click', this.imageGallery.createSlider);
                    const createBackButton = this.modal.createActionButton('Image back');
                    createBackButton.on('click', this.imageGallery.createBack);
                    const createFullButton = this.modal.createActionButton('Image full');
                    createFullButton.on('click', this.imageGallery.createFull);
                    const create4imagesButton = this.modal.createActionButton('4 images');
                    create4imagesButton.on('click', this.imageGallery.create4images);
                    const create2picturesButton = this.modal.createActionButton('2 pictures');
                    create2picturesButton.on('click', this.imageGallery.create2pictures);

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
                    this.insert.text('{{'+ this.galleryGuid + ';' + type.toUpperCase()+'}}');
                },
                createSlider: function () {
                    if (this.modalImageUploadingArea && this.modalImageUploadingArea[0].files.length > 0) {
                        this.imageGallery.postImageList('slider');
                    }
                },
                createBack: function () {
                    if (this.modalImageUploadingArea && this.modalImageUploadingArea[0].files.length > 0) {
                        this.imageGallery.postImageList('imagebackground');
                    }
                },
                createFull: function () {
                    if (this.modalImageUploadingArea && this.modalImageUploadingArea[0].files.length > 0) {
                        this.imageGallery.postImageList('imagefull');
                    }
                },
                create4images: function () {
                    if (this.modalImageUploadingArea && this.modalImageUploadingArea[0].files.length > 0) {
                        this.imageGallery.postImageList('4images');
                    }
                },
                create2pictures: function () {
                    if (this.modalImageUploadingArea && this.modalImageUploadingArea[0].files.length > 0) {
                        this.imageGallery.postImageList('2pictures');
                    }
                },
            };
        };
    }
})
