/*
    Some of these override earlier varien/product.js methods, therefore
    varien/product.js must have been included prior to this file.
    script by Matt Dean ( http://organicinternet.co.uk/ )
*/

Product.Config.prototype.getMatchingSimpleProduct = function(){
    var inScopeProductIds = this.getInScopeProductIds();
    if ((typeof inScopeProductIds != 'undefined') && (inScopeProductIds.length == 1)) {
        return inScopeProductIds[0];
    }
    return false;
};

/*
    Find products which are within consideration based on user's selection of
    config options so far
    Returns a normal array containing product ids
    allowedProducts is a normal numeric array containing product ids.
    childProducts is a hash keyed on product id
    optionalAllowedProducts lets you pass a set of products to restrict by,
    in addition to just using the ones already selected by the user
*/
Product.Config.prototype.getInScopeProductIds = function(optionalAllowedProducts) {

    var childProducts = this.config.childProducts;
    var allowedProducts = [];

    if ((typeof optionalAllowedProducts != 'undefined') && (optionalAllowedProducts.length > 0)) {
        allowedProducts = optionalAllowedProducts;
    }

    for(var s=0, len=this.settings.length-1; s<=len; s++) {
        if (this.settings[s].selectedIndex <= 0){
            break;
        }
        var selected = this.settings[s].options[this.settings[s].selectedIndex];
        if (s==0 && allowedProducts.length == 0){
            allowedProducts = selected.config.allowedProducts;
        } else {
            allowedProducts = allowedProducts.intersect(selected.config.allowedProducts).uniq();
        }
    }

    //If we can't find any products (because nothing's been selected most likely)
    //then just use all product ids.
    if ((typeof allowedProducts == 'undefined') || (allowedProducts.length == 0)) {
        productIds = Object.keys(childProducts);
    } else {
        productIds = allowedProducts;
    }
    return productIds;
};


Product.Config.prototype.getProductIdOfCheapestProductInScope = function(priceType, optionalAllowedProducts) {

    var childProducts = this.config.childProducts;
    var productIds = this.getInScopeProductIds(optionalAllowedProducts);

    var minPrice = Infinity;
    var lowestPricedProdId = false;

    //Get lowest price from product ids.
    for (var x=0, len=productIds.length; x<len; ++x) {
        var thisPrice = Number(childProducts[productIds[x]][priceType]);
        if (thisPrice < minPrice) {
            minPrice = thisPrice;
            lowestPricedProdId = productIds[x];
        }
    }
    return lowestPricedProdId;
};


Product.Config.prototype.getProductIdOfMostExpensiveProductInScope = function(priceType, optionalAllowedProducts) {

    var childProducts = this.config.childProducts;
    var productIds = this.getInScopeProductIds(optionalAllowedProducts);

    var maxPrice = 0;
    var highestPricedProdId = false;

    //Get highest price from product ids.
    for (var x=0, len=productIds.length; x<len; ++x) {
        var thisPrice = Number(childProducts[productIds[x]][priceType]);
        if (thisPrice >= maxPrice) {
            maxPrice = thisPrice;
            highestPricedProdId = productIds[x];
        }
    }
    return highestPricedProdId;
};

Product.OptionsPrice.prototype.updateSpecialPriceDisplay = function(price, finalPrice) {

    var prodForm = $('product_addtocart_form');

    var specialPriceBox = prodForm.select('p.special-price');
    var oldPricePriceBox = prodForm.select('p.old-price, p.was-old-price');
    var magentopriceLabel = prodForm.select('span.price-label');

    if (price == finalPrice) {
        specialPriceBox.each(function(x) {x.hide();});
        magentopriceLabel.each(function(x) {x.hide();});
        oldPricePriceBox.each(function(x) {
            x.removeClassName('old-price');
            x.addClassName('was-old-price');
        });
    }else{
        specialPriceBox.each(function(x) {x.show();});
        magentopriceLabel.each(function(x) {x.show();});
        oldPricePriceBox.each(function(x) {
            x.removeClassName('was-old-price');
            x.addClassName('old-price');
        });
    }
};

//This triggers reload of price and other elements that can change
//once all options are selected
Product.Config.prototype.reloadPrice = function() {
    if (this.config.disablePriceReload) {
        return;
    }
    var childProductId = this.getMatchingSimpleProduct();
    var childProducts = this.config.childProducts;
    var usingZoomer = false;
    if(this.config.imageZoomer){
        usingZoomer = true;
    }

    if(childProductId){

        var price = childProducts[childProductId]["price"];
        var finalPrice = childProducts[childProductId]["finalPrice"];
        optionsPrice.productPrice = finalPrice;
        optionsPrice.productOldPrice = price;
        optionsPrice.reload();
        optionsPrice.reloadPriceLabels(true);
        optionsPrice.updateSpecialPriceDisplay(price, finalPrice);
        if(this.config.updateShortDescription) {
          this.updateProductShortDescription(childProductId);
        }
        if(this.config.updateDescription) {
          this.updateProductDescription(childProductId);
        }
        if(this.config.updateProductName) {
          this.updateProductName(childProductId);
        }
      //  this.updateProductAttributes(childProductId);
        if(this.config.customStockDisplay) {
        	this.updateProductAvailability(childProductId);
        }
      //  this.updateFormProductId(childProductId);
      //  this.addParentProductIdToCartForm(this.config.productId);
        this.showTierPricingBlock(childProductId, this.config.productId);
        if (usingZoomer) {
            this.showFullImageDiv(childProductId, this.config.productId);
        }else{
            this.updateProductImage(childProductId);
        }

    } else {
        var cheapestPid = this.getProductIdOfCheapestProductInScope("finalPrice");
        var price = childProducts[cheapestPid]["price"];
        var finalPrice = childProducts[cheapestPid]["finalPrice"];
        optionsPrice.productPrice = finalPrice;
        optionsPrice.productOldPrice = price;
        optionsPrice.reload();
        optionsPrice.reloadPriceLabels(false);
        optionsPrice.updateSpecialPriceDisplay(price, finalPrice);
        this.updateProductShortDescription(false);
        this.updateProductDescription(false);
        this.updateProductAttributes(false);
        if(this.config.customStockDisplay) {
        	this.updateProductAvailability(false);
        }
        this.showTierPricingBlock(false);
        this.showCustomOptionsBlock(false, false);
        if (usingZoomer) {
            this.showFullImageDiv(false, false);
        } else {
        	//this.ColorSwitcher(childProducts[cheapestPid]["image"]);
        }
    }
};


Product.Config.prototype.ColorSwitcher = function(imageURL) {
	var mainImgObj = document.getElementById("productMainImg");
	    var iw = mainImgObj.width;
	    var ih = mainImgObj.height;
	  mainImgObj.width = iw;
	  mainImgObj.height = ih;
	  mainImgObj.src = imageURL;
};

Product.Config.prototype.updateProductImage = function(productId) {
    var imageUrl = this.config.imageUrl;
    if(productId && this.config.childProducts[productId].imageUrl) {
        imageUrl = this.config.childProducts[productId].imageUrl;
    }
    if (!imageUrl) {
        return;
    }
    if($('image')) {
        $('image').src = imageUrl;
    } else {
        $$('#product_addtocart_form p.product-image img').each(function(el) {
            var dims = el.getDimensions();
            el.src = imageUrl;
            el.width = dims.width;
            el.height = dims.height;
        });
    }
};

Product.Config.prototype.updateProductName = function(productId) {

    if (productId && this.config.ProductNames[productId].ProductName) {
    	productName = this.config.ProductNames[productId].ProductName;
    }
    $$('#product_addtocart_form div.product-name h1').each(function(el) {
        el.innerHTML = productName;
    });
};

Product.Config.prototype.updateProductAvailability = function(productId) {
	var stockInfo = this.config.stockInfo;
	var is_in_stock = false;
	var stockLabel = '';
    if (productId && stockInfo[productId]["stockLabel"]) {
    	stockLabel = stockInfo[productId]["stockLabel"];
    	stockQty = stockInfo[productId]["stockQty"];
    	is_in_stock =  stockInfo[productId]["is_in_stock"];
    }
    $$('#product_addtocart_form p.availability span').each(function(el) {
    	if(is_in_stock) {
    		 el.innerHTML = stockQty + '  ' + stockLabel;
    	} else {
    		 el.innerHTML = stockLabel;
    	}

    });
};

Product.Config.prototype.updateProductShortDescription = function(productId) {
   var shortDescription = '';
    if (productId && this.config.shortDescriptions[productId].shortDescription) {
        shortDescription = this.config.shortDescriptions[productId].shortDescription;
    }
    $$('#product_addtocart_form div.short-description div.std').each(function(el) {
        el.innerHTML = shortDescription;
    });
};

Product.Config.prototype.updateProductDescription = function(productId) {
   var description = '';
	if (productId && this.config.Descriptions[productId].Description) {
        description = this.config.Descriptions[productId].Description;
    }
    $$('div.box-description div.std').each(function(el) {
        el.innerHTML = description;
    });
};

Product.Config.prototype.updateProductAttributes = function(productId) {
    var productAttributes = this.config.productAttributes;
    if (productId && this.config.childProducts[productId].productAttributes) {
        productAttributes = this.config.childProducts[productId].productAttributes;
    }
    //If config product doesn't already have an additional information section,
    //it won't be shown for associated product either. It's too hard to work out
    //where to place it given that different themes use very different html here
    $$('div.product-collateral div.box-additional').each(function(el) {
        el.innerHTML = productAttributes;
        decorateTable('product-attribute-specs-table');
    });
};

Product.Config.prototype.showCustomOptionsBlock = function(productId, parentId) {
    var coUrl = this.config.ajaxBaseUrl + "co/?id=" + productId + '&pid=' + parentId;
    var prodForm = $('product_addtocart_form');

   if ($('SCPcustomOptionsDiv')==null) {
      return;
   }

    Effect.Fade('SCPcustomOptionsDiv', { duration: 0.5, from: 1, to: 0.5 });
    if(productId) {
        //Uncomment the line below if you want an ajax loader to appear while any custom
        //options are being loaded.
        //$$('span.scp-please-wait').each(function(el) {el.show()});

        //prodForm.getElements().each(function(el) {el.disable()});
        new Ajax.Updater('SCPcustomOptionsDiv', coUrl, {
          method: 'get',
          evalScripts: true,
          onComplete: function() {
              $$('span.scp-please-wait').each(function(el) {el.hide()});
              Effect.Fade('SCPcustomOptionsDiv', { duration: 0.5, from: 0.5, to: 1 });
              //prodForm.getElements().each(function(el) {el.enable()});
          }
        });
    } else {
        $('SCPcustomOptionsDiv').innerHTML = '';
        try{window.opConfig = new Product.Options([]);} catch(e){}
    }
};


Product.OptionsPrice.prototype.reloadPriceLabels = function(productPriceIsKnown) {
    var priceFromLabel = '';
    var prodForm = $('product_addtocart_form');

    if (!productPriceIsKnown && typeof spConfig != "undefined") {
        priceFromLabel = spConfig.config.priceFromLabel;
    }

    var priceSpanId = 'configurable-price-from-' + this.productId;
    var duplicatePriceSpanId = priceSpanId + this.duplicateIdSuffix;

    if($(priceSpanId) && $(priceSpanId).select('span.configurable-price-from-label'))
        $(priceSpanId).select('span.configurable-price-from-label').each(function(label) {
        label.innerHTML = priceFromLabel;
    });

    if ($(duplicatePriceSpanId) && $(duplicatePriceSpanId).select('span.configurable-price-from-label')) {
        $(duplicatePriceSpanId).select('span.configurable-price-from-label').each(function(label) {
            label.innerHTML = priceFromLabel;
        });
    }
};



//SCP: Forces the 'next' element to have it's optionLabels reloaded too
Product.Config.prototype.configureElement = function(element) {
    this.reloadOptionLabels(element);
    if(element.value){
        this.state[element.config.id] = element.value;
        if(element.nextSetting){
            element.nextSetting.disabled = false;
            this.fillSelect(element.nextSetting);
            this.reloadOptionLabels(element.nextSetting);
            this.resetChildren(element.nextSetting);
        }
    }
    else {
        this.resetChildren(element);
    }
    this.reloadPrice();
};


//SCP: Changed logic to use absolute price ranges rather than price differentials
Product.Config.prototype.reloadOptionLabels = function(element){
    var selectedPrice;
    var childProducts = this.config.childProducts;
    var stockInfo = this.config.stockInfo;

    //Don't update elements that have a selected option
    if(element.options[element.selectedIndex].config){
        return;
    }

    for(var i=0;i<element.options.length;i++){
        if(element.options[i].config){
            var cheapestPid = this.getProductIdOfCheapestProductInScope("finalPrice", element.options[i].config.allowedProducts);
            var mostExpensivePid = this.getProductIdOfMostExpensiveProductInScope("finalPrice", element.options[i].config.allowedProducts);
            var cheapestFinalPrice = childProducts[cheapestPid]["finalPrice"];
            var mostExpensiveFinalPrice = childProducts[mostExpensivePid]["finalPrice"];
            var stock = stockInfo[cheapestPid]["stockLabel"];
            var tierpricing = childProducts[mostExpensivePid]["tierpricing"];
            element.options[i].innerHTML = this.getOptionLabel(element.options[i].config, cheapestFinalPrice, mostExpensiveFinalPrice, stock , tierpricing);
        }
    }
};

Product.Config.prototype.showTierPricingBlock = function(productId, parentId) {

	var coUrl = this.config.ajaxBaseUrl + "co/?id=" + productId + '&pid=' + parentId;
	var prodForm = $('product_addtocart_form');
	   if(productId) {
	        new Ajax.Updater('sppTierPricingDiv', coUrl, {

	          method: 'get',
	          evalScripts: true,
	          onComplete: function() {
	              $$('span.scp-please-wait').each(function(el) {el.hide()});
	          }
	        });
	    } else {
	        $('sppTierPricingDiv').innerHTML = '';
	    }
	};

//SCP: Changed label formatting to show absolute price ranges rather than price differentials
Product.Config.prototype.getOptionLabel = function(option, lowPrice, highPrice, stock, tierpricing){
    var str;

    if(isMobile() && jQuery(window).width() < 768){
        str = option.label;
    } else {
        str = "<span class='format-label'>" + option.label + "</span>" ;
    }

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
                    var childOldPrice = '';
                    var newPriceClass = 'format-price';
                    if(this.formatPrice(this.config.childProducts[option.products[0]].price, false) != this.formatPrice(lowPrices[0], false)){
                        childOldPrice = '<span class="format-old-price">&nbsp;&nbsp;<span class="old-price-container">' + this.formatPrice(this.config.childProducts[option.products[0]].price, false) + '</span></span>'
                        newPriceClass = 'format-price sale';
                    }

                    if(isMobile() && jQuery(window).width() < 768){
                        str+= " " + this.formatPrice(lowPrices[0], false);
                    } else {
                        str+= '<span class="' + newPriceClass + '">&nbsp;&nbsp;' + this.formatPrice(lowPrices[0], false) + '</span>' + childOldPrice;
                    }
            	}
                str += stock;
                str += tierpricinglowestprice;
            }
        }
    }
    ;
    return str;
};


//SCP: Refactored price calculations into separate function
Product.Config.prototype.getTaxPrices = function(price) {
    var price = parseFloat(price);
    if (this.taxConfig.includeTax) {
        var tax = price / (100 + this.taxConfig.defaultTax) * this.taxConfig.defaultTax;
        var excl = price - tax;
        var incl = excl*(1+(this.taxConfig.currentTax/100));
    } else {
        var tax = price * (this.taxConfig.currentTax / 100);
        var excl = price;
        var incl = excl + tax;
    }
    if (this.taxConfig.showIncludeTax || this.taxConfig.showBothPrices) {
        price = incl;
    } else {
        price = excl;
    }

    return [price, incl, excl];
};
//SCP: Forces price labels to be updated on load
//so that first select shows ranges from the start
document.observe("dom:loaded", function() {
    //Really only needs to be the first element that has configureElement set on it,
    //rather than all.

    if ($('product_addtocart_form')) {
        $('product_addtocart_form').getElements().each(function(el) {
            if(el.type == 'select-one') {
                if(el.options && (el.options.length > 1)) {
                    el.options[0].selected = true;
                    spConfig.reloadOptionLabels(el);
                }
            }
        });
    }
});