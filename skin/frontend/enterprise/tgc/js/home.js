//custom scripts for home page

jQuery(document).ready(function(){
    // set equal heights on divs
    jQuery(window).resize(function() {
        var resizeDetector = null;
        if(jQuery(window).width() < 767) {
            if (resizeDetector) clearTimeout(resizeDetector);
            resizeDetector = setTimeout(equalHeight(jQuery(".quote-item")), 250);
        }
    });

    function equalHeight(group) {
        group.css('height','inherit');
        tallest = 0;
        group.each(function() {
            thisHeight = jQuery(this).outerHeight();
            if(thisHeight > tallest) {
                tallest = thisHeight;
            }
        });
        group.height(tallest);
    };

    equalHeight(jQuery(".quote-item"));

});