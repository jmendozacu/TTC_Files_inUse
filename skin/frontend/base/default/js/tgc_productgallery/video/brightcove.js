/**
 * ProductGallery
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     ProductGallery
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

if (typeof Product == 'undefined') {
    var Product = {};
}

if (typeof Product.GalleryBrightcovePlayers == 'undefined') {
    Product.GalleryBrightcovePlayers = new Hash();
}

Product.GalleryBrightcove = Class.create();
Product.GalleryBrightcove.prototype = {
    initialize: function (config) {
        this.brightcoveExperienceId = config.brightcoveExperienceId;
        this.baseImageData = typeof(config.baseImageData) != 'undefined' ? config.baseImageData : null;
        this.thumbnailsWithVideoData = typeof(config.thumbnailsWithVideoData) != 'undefined' ? config.thumbnailsWithVideoData : [];
        this.playerContainer = config.playerContainer;
        this.playButtonContainer = config.playButtonContainer;
        this.playButton = this.playButtonContainer.down('a');
        this.baseImageContainer = config.baseImageContainer;
        this.thumbnailsContainer = config.thumbnailsContainer;

        this.playButtonClickObserverRef = null;
        this.player = null;
        this.videoReferenceToCue = null;

        this.initPlayerContainer();
        this.initBaseImageVideo();
        this.initThumbnailImageVideos();

        Event.observe(window, 'resize', function () {
            this.updatePlayerSize.bind(this).delay(0.5);
        }.bind(this));
    },

    initPlayerContainer: function () {
        this.baseImageContainer.insert(this.playButtonContainer);
        this.baseImageContainer.insert(this.playerContainer);
        this.playerContainer.show();
        this.hidePlayerContainer();
    },

    hidePlayerContainer: function () {
        this.playerContainer.setStyle({position: 'absolute', left: '-9999px'});
    },

    showPlayerContainer: function () {
        this.playerContainer.setStyle({position: 'absolute', left: '0', top: '0'});
    },

    initBaseImageVideo: function () {
        var currentBaseImageSrc = this.baseImageContainer.down('img').src;
        if (!currentBaseImageSrc) {
            return;
        }

        var brightcoveIdToPlay;
        if (this.baseImageData && this._imageSrcContainsFilename(currentBaseImageSrc, this.baseImageData.file) && this.baseImageData.brightcove_id) {
            brightcoveIdToPlay = this.baseImageData.brightcove_id;
        } else {
            // looking in other images in case if base image has been already switched
            for (var i = 0, len = this.thumbnailsWithVideoData.length; i < len; i++) {
                if (this._imageSrcContainsFilename(currentBaseImageSrc, this.thumbnailsWithVideoData[i].file)) {
                    brightcoveIdToPlay = this.thumbnailsWithVideoData[i].brightcove_id;
                    break;
                }
            }
        }

        if (brightcoveIdToPlay) {
            this.setPlayButtonClickObserver(brightcoveIdToPlay);
            this.playButtonContainer.show();
        }
    },

    _imageSrcContainsFilename: function (src, filename) {
        var lastDotIndex = filename.lastIndexOf('.');
        if (lastDotIndex > 0 && lastDotIndex >= (filename.length - 5)) {
            // matching against regexp "/[filename_without_extension](\.\d*)?[file_extension]$/" for CacheBuster compatibility
            return !!src.match(new RegExp(RegExp.escape(filename.slice(0, lastDotIndex)) + '(\\.\\d*)?' + RegExp.escape(filename.slice(lastDotIndex)) + '$'));
        } else {
            return src.endsWith(filename);
        }
    },

    initThumbnailImageVideos: function () {
        if (!this.thumbnailsContainer) {
            return;
        }

        var i, j, leni, lenj, imageBrightcoveId;
        var thumbnailImgs = this.thumbnailsContainer.select('img');
        for (i = 0, leni = thumbnailImgs.length; i < leni; i++) {
            imageBrightcoveId = false;
            for (j = 0, lenj = this.thumbnailsWithVideoData.length; j < lenj; j++) {
                if (this._imageSrcContainsFilename(thumbnailImgs[i].src, this.thumbnailsWithVideoData[j].file)) {
                    imageBrightcoveId = this.thumbnailsWithVideoData[j].brightcove_id;
                    break;
                }
            }

            if (imageBrightcoveId) {
                thumbnailImgs[i].up('a').observe('click', this.thumbnailWithVideoClickObserver.bind(this, imageBrightcoveId));
            } else {
                thumbnailImgs[i].up('a').observe('click', this.thumbnailWithoutVideoClickObserver.bind(this));
            }
        }
    },

    thumbnailWithVideoClickObserver: function (brightcoveId) {
        this.setPlayButtonClickObserver(brightcoveId);
        this.hidePlayerContainer();
        this.playButtonContainer.show();
        if (this.player) {
            this.playerModVP.pause();
        }
    },

    thumbnailWithoutVideoClickObserver: function () {
        this.hidePlayerContainer();
        this.playButtonContainer.hide();
        if (this.player) {
            this.playerModVP.pause();
        }
    },

    setPlayButtonClickObserver: function (brightcoveId) {
        if (this.playButtonClickObserverRef) {
            this.playButton.stopObserving('click', this.playButtonClickObserverRef);
        }

        this.videoReferenceToCue = brightcoveId;
        this.playButtonClickObserverRef = this.playButtonClickObserver.bind(this, brightcoveId);
        this.playButton.observe('click', this.playButtonClickObserverRef);
        if (this.player) {
            this.playerModVP.cueVideoByReferenceID(brightcoveId);
        }
    },

    playButtonClickObserver: function (brightcoveId, event) {
        Event.stop(event);

        if (this.player) {
            // pausing all product players first
            Product.GalleryBrightcovePlayers.each(function (pair) {
                if (pair.value) {
                    pair.value.pause();
                }
            });
            // ---------------------------------
            this.playerModVP.play();
            this.updatePlayerSize();
            this.playButtonContainer.hide();
            this.showPlayerContainer();
        }
    },

    updatePlayerSize: function () {
        if (this.player) {
            var measurementContainer = this.baseImageContainer;
            if (measurementContainer) {
                var layout = measurementContainer.getLayout();
                var width = layout.get('width');
                var height = layout.get('height');
                if (width > 0 && height > 0) {
                    this.playerModExp.setSize(width, height);
                    var playerObj = $(this.brightcoveExperienceId);
                    if (playerObj) {
                        playerObj.width = width;
                        playerObj.height = height;
                        //playerObj.setStyle({width: width, height: height});
                    }
                }
            }
        }
    },

    playerOnTemplateLoad: function (experienceId) {
        this.player = brightcove.api.getExperience(experienceId);
        this.playerModVP = this.player.getModule(brightcove.api.modules.APIModules.VIDEO_PLAYER);
        this.playerModExp = this.player.getModule(brightcove.api.modules.APIModules.EXPERIENCE);
        Product.GalleryBrightcovePlayers.set(experienceId, this.playerModVP);
        if (this.videoReferenceToCue) {
            this.playerModVP.cueVideoByReferenceID(this.videoReferenceToCue);
        }
        this.updatePlayerSize();
    }
};
