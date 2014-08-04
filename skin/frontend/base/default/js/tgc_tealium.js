/**
 * Tealium
 *
 */

tgc_tealiumObj = {
    addToCartEventLink : function(format, product_id, product_sku, price){
        if(typeof window.utag_data == "object" && typeof window.utag == "object" && typeof window.utag.link == "function"){
            var data = {};
            var utag = window.utag;
            data.event_name = "add to cart";
            data.link_text = "course add to cart button ";
            data.product_id = product_id;
            data.product_sku = product_sku;
            data.course_format = format;
            data.product_priority_price = price;

            return utag.link(data);
        }
    },

    removeItemFromCartEvent : function(format, sku, id, price) {
        if(typeof window.utag_data == "object" && typeof window.utag == "object" && typeof window.utag.link == "function"){
            var data = {};
            var utag = window.utag;
            data.event_name = 'remove from cart';
            data.link_text = 'remove from cart';
            data.product_id = id;
            data.product_sku = sku;
            data.course_format_removed = format;
            data.product_priority_price = price;

            return utag.link(data);
        }
    },

    addToWishlistEvent : function(format, price) {
        if(typeof window.utag_data == "object" && typeof window.utag == "object" && typeof window.utag.link == "function"){
            var data = {};
            var utag = window.utag;
            data.event_name = 'add to wishlist';
            data.link_text = 'add to wishlist';
            data.product_id = window.utag_data.product_id;
            data.product_sku = window.utag_data.product_sku;
            data.course_format = format;
            data.product_priority_price = price;

            return utag.link(data);
        }
    },

    buyTogetherAndSaveEvent : function(product_id, product_sku, format, price) {
        if(typeof window.utag_data == "object" && typeof window.utag == "object" && typeof window.utag.link == "function"){
            var data = {};
            var utag = window.utag;
            data.event_name = 'buy together add to cart';
            data.link_text = 'buy together and save button';
            data.product_id = product_id;
            data.product_sku = product_sku;
            data.course_format = format;
            data.merchandising_location = 'buy_together: ' + window.utag_data.page_name;
            data.product_priority_price = price;

            return utag.link(data);
        }
    },

    addToCartYmalEvent : function(product_id, product_sku, price) {
        if(typeof window.utag_data == "object" && typeof window.utag == "object" && typeof window.utag.link == "function"){
            var data = {};
            var utag = window.utag;
            data.event_name = 'you may like add to cart';
            data.link_text = 'you may also like button';
            data.product_id = product_id;
            data.product_sku = product_sku;
            data.merchandising_location = 'youmaylike: ' + window.utag_data.page_name;
            data.product_priority_price = price;

            return utag.link(data);
        }
    },

    badPriorityCodeEvent : function(priority_code) {
        if(typeof window.utag_data == "object" && typeof window.utag == "object" && typeof window.utag.link == "function"){
            var data = {};
            var utag = window.utag;
            data.event_name = 'apply priority code';
            data.link_text = 'apply priority code';
            data.tgcpc_submit = priority_code;

            return utag.link(data);
        }
    },

    applyPriorityCodeEvent : function(priority_code) {
        if(typeof window.utag_data == "object" && typeof window.utag == "object" && typeof window.utag.link == "function"){
            var data = {};
            var utag = window.utag;
            data.event_name = 'apply priority code';
            data.link_text = 'apply priority code';
            data.tgcpc_submit = priority_code;

            return utag.link(data);
        }
    },

    addCouponCodeFromCartEvent : function(coupon_code) {
        if(typeof window.utag_data == "object" && typeof window.utag == "object" && typeof window.utag.link == "function"){
            var data = {};
            var utag = window.utag;
            data.event_name = 'apply coupon';
            data.link_text = 'apply coupon';
            data.coupon_submit = coupon_code;

            return utag.link(data);
        }
    },

    addPriorityCodeFromCartEvent : function(priority_code) {
        if(typeof window.utag_data == "object" && typeof window.utag == "object" && typeof window.utag.link == "function"){
            var data = {};
            var utag = window.utag;
            data.event_name = 'apply priority code';
            data.link_text = 'apply priority code';
            data.tgcpc_submit = priority_code;

            return utag.link(data);
        }
    },

    submitRadioCodeEvent : function(radioCode) {
        if(typeof window.utag_data == "object" && typeof window.utag == "object" && typeof window.utag.link == "function"){
            var data = {};
            var utag = window.utag;
            data.event_name = 'apply radio code';
            data.link_text = 'apply radio code';
            data.radio_code_submit = radioCode;

            return utag.link(data);
        }
    }
};

document.observe('dom:loaded', function() {
    jQuery('form#form-set-buy button.add-to-cart-set-button').on('click', function() {
        if (jQuery(this).hasClass('disabled')) {
            return;
        }
        var product_id = jQuery('form#form-set-buy div.information-needed input[name="product"]').val();
        var product_sku = jQuery('form#form-set-buy div.information-needed input[name="product_sku"]').val();
        var format = jQuery('form#form-set-buy div.choose-format-container input[type=radio]:checked').next('label').text();
        var price = jQuery('form#form-set-buy div.choose-format-container input[type=radio]:checked').closest('div.format-block').find('div.format-price').text();
        price = price.trim().replace('$', '').replace('£', '');
        window.tgc_tealiumObj.buyTogetherAndSaveEvent(product_id, product_sku, format, price);
    });
});
