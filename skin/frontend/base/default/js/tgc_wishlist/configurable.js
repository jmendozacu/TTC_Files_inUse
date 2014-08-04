/**
 * Wishlist configurable items js
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Wishlist
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */

Product.Config.prototype.getOptionLabel = function(option, lowPrice, highPrice, stock, tierpricing){
    var str = option.label;

    return str;
};

//This triggers reload of price and other elements that can change
//once all options are selected
Product.Config.prototype.reloadPrice = function() {
    var childProductId = this.getMatchingSimpleProduct();
    var childProducts = this.config.childProducts;

    if(childProductId){
        var price = childProducts[childProductId]["price"];
        var finalPrice = childProducts[childProductId]["finalPrice"];
        this.config.optionsPrice.productPrice = finalPrice;
        this.config.optionsPrice.productOldPrice = price;
        this.config.optionsPrice.reload();
        this.config.optionsPrice.reloadPriceLabels(true);
        //this.config.optionsPrice.updateSpecialPriceDisplay(price, finalPrice);

        var priceRangeContainer = $('configurable-price-to-' + this.config.optionsPrice.productId);
        var priceContainer = $('product-price-' + this.config.optionsPrice.productId);
        if (priceRangeContainer && priceContainer) {
            priceRangeContainer.up('.price-box,.price-boxsale').hide();
            priceContainer.up('.price-box').show();
        }
    } else {
        var cheapestPid = this.getProductIdOfCheapestProductInScope("finalPrice");
        var price = childProducts[cheapestPid]["price"];
        var finalPrice = childProducts[cheapestPid]["finalPrice"];
        this.config.optionsPrice.productPrice = finalPrice;
        this.config.optionsPrice.productOldPrice = price;
        this.config.optionsPrice.reload();
        this.config.optionsPrice.reloadPriceLabels(false);
        //this.config.optionsPrice.updateSpecialPriceDisplay(price, finalPrice);

        var priceRangeContainer = $('configurable-price-to-' + this.config.optionsPrice.productId);
        var priceContainer = $('product-price-' + this.config.optionsPrice.productId);
        if (priceRangeContainer && priceContainer) {
            priceRangeContainer.up('.price-box,.price-boxsale').show();
            priceContainer.up('.price-box').hide();
        }
    }
};
