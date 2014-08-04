Product.Config.prototype.getOptionLabel = function(option, lowPrice, highPrice, stock, tierpricing){
    var str = option.label;
    if(tierpricing > 0 ) {
    var tierpricinglowestprice = ' - As Low as ' + this.formatPrice(tierpricing,false);
    } else {
    	var tierpricinglowestprice = '';
    }
    if (!this.config.showPriceRangesInOptions) {
        return str;
    }

    if (!this.config.showOutOfStock){
    	stock = '';
    }

	if (this.config.hideprices) {
    	 return str;
    }


    var to = ' ' + this.config.rangeToLabel + ' ';
    var separator = ': ( ';

    lowPrices = this.getTaxPrices(lowPrice);
    highPrices = this.getTaxPrices(highPrice);

    if(lowPrice && highPrice){
    	if (this.config.showfromprice) {
    	  this.config.priceFromLabel = 'Price from: ';
    	}
        if (lowPrice != highPrice) {
            if (this.taxConfig.showBothPrices) {
                str+= separator + this.formatPrice(lowPrices[2], false) + ' (' + this.formatPrice(lowPrices[1], false) + ' ' + this.taxConfig.inclTaxTitle + ')';
                str+= to + this.formatPrice(highPrices[2], false) + ' (' + this.formatPrice(highPrices[1], false) + ' ' + this.taxConfig.inclTaxTitle + ')';
                str += " ) ";
            } else {
                str+= separator + this.formatPrice(lowPrices[0], false);
                str+= to + this.formatPrice(highPrices[0], false);
                str += " ) ";
            }
        } else {

            if (this.taxConfig.showBothPrices) {
                str+= separator + this.formatPrice(lowPrices[2], false) + ' (' + this.formatPrice(lowPrices[1], false) + ' ' + this.taxConfig.inclTaxTitle + ')';
                str += " ) ";
                str += stock;
                str += tierpricinglowestprice;
            } else {

            	if(tierpricing == 0 ) {
                    str+= ' ' + this.formatPrice(lowPrices[0], false);
            	}
                str += stock;
                str += tierpricinglowestprice;
            }
        }
    }
    ;
    return str;
};
