var _player, videoPlayer, experience;

// listen to post messages sent by TCG host window

if ("addEventListener" in window){

    function receiveTGCmessage(e) {
        if (e.data=='play'){
            videoPlayer.play();
        }

        if (e.data=='pause'){
            videoPlayer.pause();
        }
    }
    window.addEventListener("message", receiveTGCmessage, false);

}

(function() {
    console.log("************ TGC custom plug-in script loaded ******************");

    _player = brightcove.api.getExperience();
    videoPlayer = _player.getModule(brightcove.api.modules.APIModules.VIDEO_PLAYER);
    experience = _player.getModule(brightcove.api.modules.APIModules.EXPERIENCE);

}());