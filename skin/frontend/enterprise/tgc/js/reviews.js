/**
 * Created by mhidalgo on 30/05/14.
 */
jQuery(document).ready(function(){
    setTimeout(function() {
            jQuery('#tab-tabreviews a').click();
            var position = jQuery('#tab-tabreviews a').offset();
            window.scrollTo(0,position.top);
        },
        1000
    )
});
