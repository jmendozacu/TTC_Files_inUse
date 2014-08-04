jQuery(document).ready(function(){

    //init functions

    redirectToMyLibrary = false;

    initDetachedTooltips();
    if(isTouchable){
        jQuery('body').addClass('is-touch');
    }else{
        jQuery('body').addClass('no-touch');
    }

    if (isTouchable){
        var navBarStyle, rootWrapperStyle, mainContinerStyle;
        jQuery('textarea,input,select').on('focus', function(e) {
            navBarStyle = jQuery('#mobile-nav-header').attr("style");
            rootWrapperStyle = jQuery('#root-wrapper').attr("style");
            mainContinerStyle = jQuery(".main-container").attr("style");
            jQuery('#mobile-nav-header').css('position', 'absolute');
            jQuery('#root-wrapper').css('position', 'relative');
            jQuery('.main-container').css({'margin-top' : '0', 'padding-top' : jQuery('#mobile-nav-header').innerHeight()});
        });
        jQuery('textarea,input,select').on('blur', function(e) {
            jQuery('#mobile-nav-header').removeAttr('style').attr("style", navBarStyle);
            jQuery('#root-wrapper').removeAttr('style').attr("style", rootWrapperStyle);
            jQuery('.main-container').removeAttr('style').attr("style", mainContinerStyle);
        });
    }
    // Toltip Scroll Shadow
    jQuery(".tooltip-text-desc .tooltip-body-text").each(function(){
        jQuery(this).parents('.tooltip-balloon').css({'visibility' : 'hidden'});
        jQuery(this).parents('.tooltip-balloon').show();
        if (jQuery(this).find('p').height() > jQuery(this).height()){
            jQuery(this).addClass('scroll-tooltip-body-text');
        }
        jQuery(this).parents('.tooltip-balloon').hide();
        jQuery(this).parents('.tooltip-balloon').css({'visibility' : 'visible'});
    });

    // helpers
    jQuery('.tooltip-balloon .close-balloon').on('click',function(e){
        e.preventDefault();
        closeBalloon(jQuery(this).parent());
    });

    // Checkout //
    jQuery('#giftcard_balance_lookup .close-button').on('click',function(){
        jQuery('#giftcard_balance_lookup').css('display','none');
    });


    // PDP Buy Together as Set BLOCK //
    jQuery('.catalog-product-view .buy-together-container .format-block label').on('click',function(){
        jQuery('.catalog-product-view .buy-together-container .add-to-cart-btn').removeClass('disabled');
    });

    // PDP enable add to cat btn and wishlist when selet is used on mobile view //
    jQuery('.catalog-product-view .super-attribute-select').change(function(){
        if (jQuery('.catalog-product-view .super-attribute-select option:selected').attr('value')!=''){
            jQuery('.product-options-bottom .add-to-cart-btn').removeClass('disabled').removeClass('added').html('<span><span>Add to cart</span></span>');
            jQuery('.product-options-bottom .add-to-wishlist-btn').removeClass('disabled');
        }else{
            jQuery('.product-options-bottom .add-to-cart-btn').addClass('disabled');
            jQuery('.product-options-bottom .add-to-wishlist-btn').addClass('disabled');
        }
    });

    //mobile checkout
    jQuery('.mob-checkout-sign-action').on('click',function(e){
        e.preventDefault();
        jQuery('#checkout-step-login .grid12-6').css('display','none');
        jQuery('.checkout-account-login').fadeIn();
        jQuery('html, body').animate({
            scrollTop: jQuery(".checkout-account-login").offset().top-60
        }, 300);
    });

    jQuery('.mob-checkout-createaccount-action').on('click',function(e){
        e.preventDefault();
        jQuery('#checkout-step-login .grid12-6').css('display','none');
        jQuery('.checkout-account-creation').fadeIn();
        jQuery('html, body').animate({
            scrollTop: jQuery(".checkout-account-creation").offset().top-60
        }, 300);
    });



    //cart page, resize blocks
    coupons_height();
    tablet_size();

    // Event Details Page Recomendations Block
    evt_det_recomendations_height();

    var open = jQuery('.prof_view_more');
    var close = jQuery('.prof_view_less');
    open.each(function () {
        jQuery(this).on('click', function () {
            jQuery(this).parents('.professor_info_cont').find('.professor_desc').animate({ "max-height": jQuery(this).parent().siblings('.professor_desc').children('.prof_desc_container').height() }, 500, function () {
                jQuery(this).next('.prof_view_cont').find(".prof_view_less").show();
                jQuery(this).next('.prof_view_cont').find(".prof_view_more").hide();
            });
        });
    });

    close.each(function () {
        jQuery(this).on('click', function () {
            jQuery(this).parents('.professor_info_cont').find('.professor_desc').animate({ "max-height": "127px" }, 500, function () {
                jQuery(this).next('.prof_view_cont').find(".prof_view_less").hide();
                jQuery(this).next('.prof_view_cont').find(".prof_view_more").show();
            });
        });
    });
    // tooltips
    jQuery('.tooltip .js-action-icon').on('click',function(){
        closeElementsHeader();
        jQuery(this).siblings('.tooltip-balloon').show();
    });


    jQuery('.js-action-detached-tooltip').on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        closeToolTips();
        parentOffset = jQuery('.main-container .main').offset();
        relX = parseInt(e.pageX)-105;
        relY = parseInt(e.pageY)+20;
        bubbleEl = jQuery('.'+jQuery(this).attr('id'));
        bubbleEl.css('left',relX);
        bubbleEl.css('top',relY);
        bubbleEl.css('display','block');
    });

    jQuery('.login-form-dropdown').on('click',function(e){
        if(!jQuery(e.target).hasClass('js-action-detached-tooltip')){
            closeToolTips();
        }
    });

    jQuery('.infographic').on('click',function(){
        jQuery('.popup_overlay').css('display', 'block');
        jQuery('.col-main').css('z-index', '150');
    });

    jQuery('.close_popup').on('click',function(){
        jQuery('.popup_overlay').css('display', 'none');
        jQuery('.col-main').css('z-index', '0');
    });

    /** Login/Register form */
    jQuery('#create-account-button').on('click', function(e) {
        e.preventDefault();
        clearMessages();
        clearHeaderLoginMessages();
        jQuery('form#login-form').hide();
        jQuery('form#form-forgot-password').hide();
        jQuery('form#form-register').show();
    });

    function clearHeaderLoginMessages() {
        jQuery('.messages-wrapper').css('display','none');
    }

    jQuery('.venue_select').on('change', function(){
        var url = '/events';
        if (this.value){
            url = url + '/' + this.value;
        }
        window.location = url;
    });

    jQuery('.login-form-dropdown').delegate('.forgot-pass-link','click', function(e) {
        clearMessages();
        e.preventDefault();
        clearHeaderLoginMessages();
        jQuery('form#login-form').hide();
        jQuery('form#form-register').hide();
        jQuery('form#form-forgot-password').show();
    });

    jQuery('.back-to-login-form').on('click',function() {
        clearMessages();
        jQuery('form#form-register').hide();
        jQuery('form#form-forgot-password').hide();
        jQuery('form#login-form').show();
    });

    jQuery('.keep_logged_label_js').on('click',function() {
        if (jQuery('.login-form-dropdown #keep_logged').prop('checked')==true){
            jQuery('.login-form-dropdown #keep_logged').prop('checked',false);
        }else{
            jQuery('.login-form-dropdown #keep_logged').prop('checked',true);
        }
    });

    //login
    jQuery('button#send2').on('click', function(e) {
        e.preventDefault();
        var keepLogged;
        if (jQuery('#keep_logged').prop('checked')) {
            keepLogged = '1';
        } else {
            keepLogged = '0';
        }
        var optOut;
        if (jQuery('#opt_out_login').prop('checked')) {
            optOut = '1';
        } else {
            optOut = '0';
        }
        jQuery.ajax({
            type: 'POST',
            url: jQuery('form#login-form').attr('action'),
            data: {
                login: {
                    username:    jQuery('#email').val(),
                    password:    jQuery('#pass').val(),
                    keep_logged: keepLogged,
                    opt_out: optOut
                }
            },
            beforeSend: function(jqXHR) {
                beforeAjax();
            },
            success: function(data, textStatus, jqXHR) {
                if (data['status'] == 'success') {
                    if (!hideLoginForm) {
                        hideLoginForm = 1;
                        userLoggedIn = 1;
                        addToWish();
                    }
                    closeElementsHeader();
                    if (data['refresh'] == '1') {
                        location.reload();
                    }
                    if (redirectToMyLibrary){
                        location.href = myDigitalLibraryURL;
                    }
                    jQuery('div.signed-out-container').replaceWith(data['html']);
                    jQuery('.my-digital-library').show(500);
                    if (typeof getPageStatus == 'function') {
                        getPageStatus();
                    }
                    updateTopLinks();
                    jQuery('.login-form-dropdown').css('display','none');
                } else {
                    jQuery('.messages-wrapper-error').css('display','block');
                    jQuery('form#login-form .input-error-msg').css('display', 'block').html(data['message']);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                jQuery('.messages-wrapper-error').css('display','block');
                jQuery('form#login-form .input-error-msg').css('display', 'block').text('There was an error logging in. Please try again');
            },
            complete: function(jqXHR, textStatus) {
                afterAjax();
            }
        });
    });

    //register
    jQuery('button#submit-registration-form').on('click', function(e) {
        e.preventDefault();
        if (jQuery('input#accept_terms').prop('checked') != true) {
            jQuery('.messages-wrapper-error').css('display','block');
            jQuery('form#form-register .input-error-msg').css('display', 'block').text('You must accept the terms to continue.');
            return false;
        }
        var optOut;
        if (jQuery('#opt_out').prop('checked')) {
            optOut = '1';
        } else {
            optOut = '0';
        }
        jQuery.ajax({
            type: 'POST',
            url: jQuery('form#form-register').attr('action'),
            data: {
                firstname:     jQuery('#form-register #firstname').val(),
                lastname:      jQuery('#form-register #lastname').val(),
                email:         jQuery('#form-register #email_address').val(),
                is_subscribed: jQuery('#form-register #is_subscribed').val(),
                password:      jQuery('#form-register #password').val(),
                confirmation:  jQuery('#form-register #confirmation').val(),
                opt_out:       optOut
            },
            beforeSend: function(jqXHR) {
                beforeAjax();
            },
            success: function(data, textStatus, jqXHR) {
                if (data['status'] == 'success') {
                    if (!hideLoginForm) {
                        hideLoginForm = 1;
                        userLoggedIn = 1;
                        addToWish();
                    }
                    closeElementsHeader();
                    if (data['refresh'] == '1') {
                        location.reload();
                    }
                    jQuery('div.signed-out-container').replaceWith(data['html']);
                    jQuery('.my-digital-library').show(500);
                    if (typeof getPageStatus == 'function') {
                        getPageStatus();
                    }

                } else if (data['status'] == 'confirmation') {
                    jQuery('.messages-wrapper-succcess').css('display','block');
                    jQuery('form#form-register .input-success-msg').css('display', 'block').html(data['message']);
                }
                else {
                    jQuery('.messages-wrapper-error').css('display','block');
                    jQuery('form#form-register .input-error-msg').css('display', 'block').html(data['message']);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                jQuery('.messages-wrapper-error').css('display','block');
                jQuery('form#form-register .input-error-msg').css('display', 'block').text('There was an error creating your account. Please try again');
            },
            complete: function(jqXHR, textStatus) {
                afterAjax();
            }
        });
    });

    // do nothing if button is disabled
    jQuery('#submit-registration-form').on('click',function(){
        if (jQuery(this).hasClass('disabled')){
            return false;
        }
    });

    //logout
    jQuery('a[href*="customer/account/logout"]').on('click', function(e) {
        e.preventDefault();
        window.location = globalHeader.logoutUrl;
    });

    //messages close button
    jQuery('.pos-balloon-rel').on('click',function(e){
        e.preventDefault();
        jQuery('.messages-wrapper').css('display','none');
        e.stopPropagation();
    });

    //active create account button when checkoutbox is checked
    jQuery('.accept_terms').on('click',function(e){
       if (jQuery(this).prop('checked') != true){
           jQuery(this).parents('form').find('.submit-button').addClass('disabled');
       }
        else{
           jQuery(this).parents('form').find('.submit-button').removeClass('disabled');
       }
        e.stopPropagation();
    });

    //forgot password
    jQuery('button#forgot-password-submit').on('click', function(e) {
        e.preventDefault();
        jQuery.ajax({
            type: 'POST',
            url: jQuery('form#form-forgot-password').attr('action'),
            data: {
                email: jQuery('#forgot_pass_email_address').val()
            },
            beforeSend: function(jqXHR) {
                beforeAjax();
            },
            success: function(data, textStatus, jqXHR) {
                if (data['status'] == 'success') {
                    jQuery('.messages-wrapper-success').css('display','block');
                    jQuery('form#form-forgot-password .input-success-msg').css('display', 'block').html(data['message']);
                } else {
                    jQuery('.messages-wrapper-error').css('display','block');
                    jQuery('form#form-forgot-password .input-error-msg').css('display', 'block').html(data['message']);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('An error occurred. Please try again');
            },
            complete: function(jqXHR, textStatus) {
                afterAjax();
            }
        });
    });

    //Mobile sign-in
    jQuery('button#mob-forgotpassword-submit').on('click', function(e) {
        e.preventDefault();
        jQuery.ajax({
            type: 'POST',
            url: jQuery('form#mob_assist_form').attr('action'),
            data: {
                email: jQuery('#mobile-email-input').val()
            },
            beforeSend: function(jqXHR) {
                beforeAjax();
            },
            success: function(data, textStatus, jqXHR) {
                if (data['status'] == 'success') {
                    jQuery('#mob_assist_form .input-success-msg').css('display','block').html(data['message']);
                } else {
                    jQuery('#mob_assist_form .input-error-msg').css('display','block').html(data['message']);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('An error occurred. Please try again');
            },
            complete: function(jqXHR, textStatus) {
                afterAjax();
            }
        });
    });

    jQuery('input#firstname').attr('placeholder', 'Enter Your First Name');
    jQuery('input#lastname').attr('placeholder', 'Enter Your Last Name');
    jQuery('.login-form-dropdown label > em').hide();

    function updateTopLinks() {
        jQuery.ajax({
            type: 'POST',
            url: globalHeader.updateLinksUrl,
            success: function(data, textStatus, jqXHR) {
                if (data['status'] == 'success') {
                    jQuery('div#mini-cart').html(data['cartHtml']);
                    jQuery('div#mini-wishlist').html(data['wishlistHtml']);
                }
            }
        });
    }

    // *********** HEADER ***************


    //show tooltip for creation/password when focus
    jQuery('.create-account-password #password').on('focus',function(){
        jQuery(this).siblings('.custom-tooltip').fadeIn();
    });
    jQuery('.create-account-password #password').on('blur',function(){
        jQuery(this).siblings('.custom-tooltip').fadeOut();
    });
    jQuery('#mobile-create-pwd-input').on('focus', function(){
        jQuery('.mob_create_account_cont .create-account-password .create-psw-tooltip').fadeIn();
    });
    jQuery('#mobile-create-pwd-input').on('blur', function(){
        jQuery('.mob_create_account_cont .create-account-password .create-psw-tooltip').fadeOut();
    });
    // header login close button
    jQuery('.login-form-dropdown .close-balloon').on('click',function(e){
        e.preventDefault();
        loginForm.toggle();
        arrowDown(jQuery('.signed-out-container'));
        arrowDown(jQuery('.my-digital-library'));
        jQuery('.header-terms').css('display','none');
        jQuery('.header-privacy-policy').css('display','none');
        jQuery('body').off('mouseover');
    });

    // theme colors
    nav_active_color = '#30424D';

    // header megamenu
    pagebody = jQuery('body');
    megamenu = jQuery('#nav');
    megamenu_close = jQuery('.close-main-menu');
    megamenu_trigger = jQuery('.menu-container');

    /* code for mega menu*/
    (function($) {
        if( isTouchable === true ) {
            megamenu_trigger.on( customClickEvent, showHideMegamenu )
        } else {
            megamenu_state = megamenu.css('display');
            megamenu_trigger.on('mouseover', function() {
                if (megamenu_state === 'none'){
                    closeElementsHeader();
                    arrowUp(megamenu_trigger);
                    megamenu.toggle();

                    $('body').on('mouseover', function(e) {
                        if( $(e.target).parents('#nav-home').length === 0 ) {
                            megamenu.toggle();
                            arrowDown(megamenu_trigger);
                            $('body').off('mouseover');
                        }
                    });
                }
            });
        }
    })(jQuery);

    megamenu_close.on('click',showHideMegamenu);


    // if the clicked elements is not one of the named on the bellow conditional, then close tooptips and other popup elements
    pagebody.on('click', function(e) {
        if(jQuery(e.target).parents('.tooltip-balloon, .nav, .top-links, .customer_more_box span, .mobile_share, .tooltip-shipping-rates, .new-account-extra-options, .login-form-dropdown').length === 0) {
            if (!jQuery(this).hasClass('.keep_logged_label_js')) {
                e.stopPropagation();
                closeElementsHeader();
            }
        }
    });

    // header priority code
     jQuery('.priority_mini .js-action-link').on('click',function(){
         closeElementsHeader();
         jQuery('.priority-code-form').show();
     });

    jQuery('#priority-code').on('submit', function(e) {
        e.preventDefault();
        validatePriorityCode(jQuery('#priority-code-value').val());
    });

    jQuery('form#priority-code-mobile').on('submit', function(e) {
        e.preventDefault();
        jQuery('.priority_code_submited .descriptions').css('color','white').text('Submitting new code, please wait...');
        validatePriorityCode(jQuery('#mobile-priority-code-value').val());
    })

    if (typeof globalHeader !== 'undefined' && globalHeader.appliedPriorityCode != '') {
        priorityCodeApplied(globalHeader.appliedPriorityCode);
    }

    // close header mini-cart/mini-wishlist when X is clicked
    jQuery('.mini-widget .close-balloon').on('click',function(e){
        e.preventDefault();
        closeElementsHeader();
    });
    if (isTouchable){
        jQuery('.main-nav-wrapper .mini-widget.clickable-dropdown a.nav-text-link, .main-nav-wrapper .all-courses-js a.nav-text-link, .signed-in-container .signed-in.username-account-js a.nav-text-link-login').removeAttr("href");
    }
    // header logged-in user
    (function($) {
        if( isTouchable === true ) {
            $(document).on(customClickEvent, '.signed-in-container .nav-text-link-login', function() {
                userMenu = jQuery('.logged-in-user-dropdown');
                userMenuContainer = jQuery('.signed-in-container');
                if( userMenu.css('display') === 'block' ){
                    arrowDown(userMenuContainer);
                } else {
                    closeElementsHeader();
                    arrowUp(userMenuContainer);
                }
                userMenu.toggle();
            });
        } else {
            $(document).on('mouseover', '.signed-in-container', function() {
                userMenu = jQuery('.logged-in-user-dropdown');
                userMenuContainer = jQuery('.signed-in-container');
                var _that = $(this);
                closeElementsHeader();
                if( userMenu.css('display') === 'none' ) {
                    arrowUp(userMenuContainer);
                    userMenu.show();
                    $('body').on('mouseover', function(e) {
                        if ($(e.target).parents('.main-nav-wrapper')[0] !== _that.parents('.main-nav-wrapper')[0]) {
                            userMenu.toggle();
                            arrowDown(userMenuContainer);
                            $('body').off('mouseover');
                        }
                    });
                }
            });
        }
    })(jQuery);


     /* code for header login form */
    (function($) {
        var previous_login_trigger = '';
        var current_login_trigger = ' ';

            $(document).on(customClickEvent, '.my-digital-library-login-js', function(e) {
                jQuery('input.referringelement').val('mydigitallibrary');
            });

            $(document).on(customClickEvent, '.nav-text-link-login', function(e) {
                jQuery('input.referringelement').val('signin');
            });

            $(document).on(customClickEvent, '.my-digital-library-login-js, .signed-out-container', function(e) {
                loginForm = jQuery('.login-form-dropdown');

                loginFormContainerHeader = $(this);

                if (loginFormContainerHeader.hasClass('my-digital-library-login-js')){
                    current_login_trigger = 'digital-library';
                }else{
                    current_login_trigger = 'sign-in';
                }

                var dontshowDigitalLibraryDropdown = false;
                if(current_login_trigger == 'digital-library' && globalHeader.isLoggedIn) {
                    dontshowDigitalLibraryDropdown = true;
                }

                if( loginForm.css('display') === 'none') {
                    if(!dontshowDigitalLibraryDropdown) {
                        closeLoginForm();
                        closeElementsHeader();
                        arrowUp(loginFormContainerHeader);
                        loginForm.slideDown();
                    }
                }else{
                    if (current_login_trigger != previous_login_trigger) {
                        if(!dontshowDigitalLibraryDropdown) {
                            closeLoginForm();
                            closeElementsHeader();
                            arrowUp(loginFormContainerHeader);
                            loginForm.slideDown();
                        }
                    }else{
                        closeLoginForm();
                        closeElementsHeader();
                        arrowDown(loginFormContainerHeader);
                    }
                }

                if (loginFormContainerHeader.hasClass('my-digital-library-login-js')){
                    redirectToMyLibrary = true;
                    previous_login_trigger = 'digital-library';
                }else{
                    redirectToMyLibrary = false;
                    previous_login_trigger = 'sign-in';
                }
            });
    })(jQuery);

    //add a validation class for customer passwords
    Validation.add('validate-tgc-password', 'Please enter a password that is between 5 and 20 characters long.', function(v) {
        var pass=v.strip();
        if (0 == pass.length) {
            return true;
        }
        return !(pass.length < 5 || pass.length > 20);
    });

    //add a validation class for customer passwords confirmation in billing address
    Validation.add('validate-tgc-cpassword', 'Please make sure your passwords match.', function(v,obj) {
        var conf = $(obj.id) ? $(obj.id) : ($('confirmation') ? $('confirmation') : $$('.validate-tgc-cpassword')[0]);
        var pass = false;
        if ($('password')) {
            pass = $('password');
        }
        var passwordElements = $$('.validate-tgc-password');
        for (var i = 0; i < passwordElements.size(); i++) {
            var passwordElement = passwordElements[i];
            if (passwordElement.up('form').id == conf.up('form').id) {
                pass = passwordElement;
            }
        }
        if ($$('.validate-admin-password').size()) {
            pass = $$('.validate-admin-password')[0];
        }
        return (pass.value == conf.value);
    });

    // ********** END HEADER **************

    // ********* MOBILE NAVIGATION ***********
    nav_list = jQuery('.mobile-nav-trigger, .mobile-actions .search-action, .mobile-actions .account-action, .mobile-actions .account-action-logged, .closePushmenu');
    nav_first_level_menu = jQuery('.mobile-menu-first-level');
    nav_parent_list = jQuery('.mobile-more > span');
    nav_main_title = jQuery('.mobile-nav-title h3');
    mobMainPanel = jQuery('.mobile-main-panel');
    mobileLeftOffset = -280;
    nav_mobile_on = false;
    currentMobPanel = '';
    menuWidth = '280px';
    maxChilds = 1;
    mobListsCont = jQuery('.mobile-topmost-menu');
    panelsContainer = jQuery("#mobile-nav-container .mob-pop-panels-cont");
    mobileSearch = jQuery('#mobile-nav-container .mobile-search');
    mobNavContainer = jQuery('#mobile-nav-container');
    mobNavHeader = jQuery('#mobile-nav-header');
    emptyArea = jQuery('#mobile-nav-container .empty-area');
    actualList = mobListsCont;
    allLists = jQuery('.mobile-menu-list');
    liHeight = 42;
    jQuery('ul.mobile-menu-list').not('.mobile-topmost-menu').each(function(){
        if (maxChilds < jQuery(this).parents('ul.mobile-menu-list').length){
            maxChilds = jQuery(this).parents('ul.mobile-menu-list').length;
        }
    });
    nav_list.click(function(e) {
        e.preventDefault();
        if (jQuery(this).hasClass('account-action')){
            showSubMenu('.mob-pop-panel-sign-in');
            if (nav_mobile_on==false){
                nav_mobile_on = true;
            }else{
                nav_mobile_on = false;
            }
        }
        else{
            resetMobileNav();
            if (nav_mobile_on==false){
                nav_mobile_on = true;
            }else{
                nav_mobile_on = false;
            }
            if (jQuery(this).hasClass('account-action-logged')){
                jQuery('.mob-my-account-more').trigger('click');
            }
        }
        jQuery('#root-wrapper').toggleClass('pushmenu-push-toright');
        if (nav_mobile_on){
            jQuery('ul.mobile-menu-list').not('.mobile-topmost-menu').css({'left' : menuWidth});
            jQuery('ul.mobile-menu-list.mobile-topmost-menu').width(100 * (maxChilds + 1) + '%');
            mobNavContainer.show();
            maxNavHeight = jQuery(window).height() - mobNavContainer.height();
            mobListsCont.css({'max-height' : maxNavHeight}).show();
            mobNavContainer.height(jQuery(window).height()).width(jQuery(window).width());
            panelsContainer.height(jQuery(window).height() - mobileSearch.height());
            jQuery('html, body').toggleClass('pushmenu-push-toright-body').height(jQuery('#mobile-nav-container').height()).width(jQuery('#mobile-nav-container').width());
            mobNavContainer.css({'top' : '0'});
            mobNavHeader.css('position', 'absolute');
            jQuery('#root-wrapper .wrapper, #mobile-nav-header').addClass('mobMenu_rightElement').animate({ left : menuWidth },500,'jswing');
            jQuery('.pushmenu-left').animate({ left : "0" },500,'jswing');
            jQuery('.mobile-main-panel h3').removeClass('sub-item-header');
            jQuery('.mobile-search .ease-arrow-img').fadeIn(1000);
            emptyArea.height(mobMainPanel.height()).width(mobNavContainer.width() - mobMainPanel.width());
        }
        else{
            closeMobileMenu();
        }
    });
    function closeMobileMenu(){
        jQuery('#root-wrapper .wrapper, #mobile-nav-header').animate({ left : "0" },300);
        jQuery('.pushmenu-left').animate({ left : "-"+menuWidth},300);
        setTimeout(function(){
            jQuery('.mobile-search .ease-arrow-img').hide();
            allLists.removeAttr("style").removeClass("minimizedElements").scrollTop(0).hide();
            jQuery('#root-wrapper .wrapper, #mobile-nav-header').removeClass('mobMenu_rightElement');
            jQuery('html, body').toggleClass('pushmenu-push-toright-body').removeAttr('style');
            mobNavHeader.css('position', 'fixed');
            panelsContainer.removeAttr("style");
            actualList = mobListsCont;
            mobNavContainer.removeAttr("style");
            emptyArea.removeAttr("style");
        },300);
    }
    function adjustMobileMenu(){
        mobListsCont.hide();
        panelsContainer.hide();
        mobNavContainer.height('auto');
        jQuery('html, body').height(jQuery(window).height()).width(jQuery(window).width());
        maxNavHeight = jQuery(window).height() - mobNavContainer.height();
        mobListsCont.css({'max-height' : maxNavHeight}).show();
        panelsContainer.height(jQuery(window).height() - mobileSearch.height()).show();
        mobNavContainer.height(jQuery(window).height()).width(jQuery(window).width());
        actualList.parents('.mobile-menu-list').height(maxNavHeight);
        actualList.height(maxNavHeight);
        emptyArea.height(mobMainPanel.height()).width(mobNavContainer.width() - mobMainPanel.width());
    }

    jQuery(window).resize(function(){
        if (nav_mobile_on){
            if (jQuery(window).width() > 767){
                closeMobileMenu();
            }
            else{
                adjustMobileMenu()
            }
        }

    });
    jQuery('.mobile-menu-list > li > span').swipe({
        allowPageScroll: 'vertical',
        threshold:  100,
        tap: function() {
            spanClick = jQuery(this);
            liClick = jQuery(this).closest('li');
            if (!liClick.hasClass('mobile-nav-subtitle')){
                liClick.addClass('clickState');
                setTimeout(function () {
                    jQuery('.clickState').removeClass('clickState');
                    if (liClick.hasClass('mobile-more')){
                        setTimeout(function () {
                            nextMobileMenu(spanClick);
                        }, 100);
                    }
                }, 200);
            }
        },
        swipe:function() {
        }
    });

    function nextMobileMenu(spanClick){
        prevList = spanClick.closest('ul');
        nextList = spanClick.siblings('ul');
        nextList.find('.mobile-nav-subtitle span').text(spanClick.text());
        currentIcon = spanClick.css('background');
        nextList.children('.mobile-nav-subtitle').find('span').css('background',currentIcon);
        if (prevList.hasClass('mobile-topmost-menu')){
            jQuery('.mobile-main-panel h3.mob-text ').text('Main Menu');
            jQuery('.mobile-main-panel h3').addClass('sub-item-header');
        }else{
            jQuery('.mobile-main-panel h3.mob-text ').text(prevList.children('.mobile-nav-subtitle').find('span').text());
        }
        nextList.show();
        mobListsCont.css({'overflow-y': 'hidden'});
        nextList.css({'top': mobListsCont.scrollTop()});
        nextList.height(nextList.children('li').length * liHeight);
        nextList.parents('.mobile-menu-list').height(maxNavHeight);
        mobListsCont.animate({left : '-='+menuWidth}, 300, 'jswing');
        setTimeout(function(){
            mobListsCont.css({'overflow-y': 'auto'});
            nextList.css({'top': '0'});
            prevList.addClass('minimizedElements').scrollTop(0);
        },300);
        actualList = nextList;
    }

    nav_main_title.click(function(e){
        e.preventDefault();
        if (!actualList.hasClass('mobile-topmost-menu')){
            nextList = actualList.parent().closest('ul');
            nextList.removeClass('minimizedElements');
            mobListsCont.css({'overflow-y': 'hidden'});
            if (nextList.hasClass('mobile-topmost-menu') || nextList.parent().closest('ul').hasClass('mobile-topmost-menu')){
                if (nextList.hasClass('mobile-topmost-menu')){
                    jQuery('.mobile-main-panel h3').removeClass('sub-item-header');

                }
                jQuery('.mobile-main-panel h3.mob-text ').text('Main Menu');
            }else{
                jQuery('.mobile-main-panel h3.mob-text ').text(nextList.parent().closest('ul').children('.mobile-nav-subtitle').find('span').text());
            }
            mobListsCont.animate({left : '+='+menuWidth}, 300, 'jswing');
            setTimeout(function(){
                mobListsCont.css({'overflow-y': 'auto'});
                nextList.height(nextList.children('li').length * liHeight);
                actualList.scrollTop(0).hide();
                actualList = nextList;
            },300);
        }
    });

    function mobileSubPanels(){
        jQuery('.mobile-nav-container-before').css('display','block');
        jQuery('.mobile-main-panel').fadeIn();
        jQuery('.mobile-main-panel').removeClass('hided');
        currentMobPanel='';
        jQuery('.mob-pop-panel').hide();
        setTimeout(function () {
            jQuery('.mb_first_element').show();
            jQuery('.mb_second_element').hide();
        }, 500);
    }

    function resetMobileNav() {
        currentMobPanel = '';
        jQuery('.mobile-main-panel').removeClass('hided');
        jQuery('.mobile-menu-list').removeClass('fly-in');
        nav_first_level_menu.removeClass('hided');
        nav_first_level_menu.removeClass('show-mob-nav');
        nav_main_title.removeClass('mob-back');
        if (nav_mobile_on){
            nav_first_level_menu.removeClass('show-mob-nav');
        }else{
            nav_first_level_menu.addClass('show-mob-nav');
        }
        mobileSubPanels();
    }
    function showSubMenu(className){
        jQuery('.mobile-nav-container-before').css('display','none');
        jQuery('.mobile-main-panel').hide();
        jQuery('.mobile-main-panel').addClass('hided');
        nav_first_level_menu.addClass('show-mob-nav');
        jQuery(className).show();
        jQuery(className).css('left',mobileLeftOffset);
        jQuery(className).animate({left: 0},50);
    }
    function mobPopPanel(className){
        jQuery('.mobile-nav-container-before').hide();
        currentMobPanel = className;
        jQuery(className).fadeIn(250);
        jQuery('.mobile-main-panel').fadeOut('fast');
        jQuery('.mobile-main-panel').addClass('hided');
    }
    function mobClosePanel(jObj){
        jQuery('.mobile-nav-container-before').hide();
        jQuery('.mobile-main-panel').fadeIn(250);
        jQuery('.mobile-main-panel').removeClass('hided');
        jObj.parent().fadeOut(250);
    }

    //sign in panel
    if (jQuery('#mobile-user-input').attr('value')!='' && jQuery('#mobile-password-input').attr('value')!='') {
        jQuery('#mob-sign-in-button').removeClass('disabled');
    }

    jQuery('#mobile-user-input, #mobile-password-input').on('blur',function(){
        if (jQuery('#mobile-user-input').attr('value')!='' && jQuery('#mobile-password-input').attr('value')!='') {
            jQuery('#mob-sign-in-button').removeClass('disabled');
        }else{
            jQuery( '#mob-sign-in-button').addClass('disabled');
        }
    });

    jQuery('#mob-sign-in-button').on('click',function(e){
        if (jQuery(this).hasClass('disabled')) {
            return false;
        }
    });

    jQuery('#priority-code-mobile').on('submit', function(e) {
        e.preventDefault();
        jQuery('.priority_code_submited').fadeIn();
        jQuery('.pc_form_container').fadeOut();
        validatePriorityCode(jQuery('#mobile-priority-code-value').val());
        jQuery('#mobile-priority-code-value').val('');
    });

    jQuery('#mob_assist_form').on('submit', function(e) {
        e.preventDefault();
        jQuery.ajax({
            type: 'POST',
            url: jQuery('form#mob_assist_form').attr('action'),
            data: {
                email: jQuery('#mobile-email-input').val()
            },
            success: function(data, textStatus, jqXHR) {
                if (data['status'] == 'success') {
                    jQuery('form#mob_assist_form .input-success-msg').css('display', 'block').html(data['message']);
                } else {
                    jQuery('form#mob_assist_form .input-error-msg').css('display', 'block').html(data['message']);
                }
            }
        });
    });

    // Create Account mobile panel
    jQuery('#mobile-create-pwd-input').on('focus', function(){
        jQuery('#mob_create_account_form .create-psw-tooltip').css('display','block');
        setTimeout(function(){
            jQuery('#mob_create_account_form .create-psw-tooltip').fadeOut();
        },4000);
    });

    jQuery('#mobile-create-pwd-input').on('blur', function(){
        jQuery('#mob_create_account_form .create-psw-tooltip').css('display','none');
    });

    jQuery('#mob_create_account_form').on('submit', function(e) {
        e.preventDefault();
        if (jQuery('input#mobile_accept_terms').prop('checked') != true) {
            jQuery('form#mob_create_account_form .input-error-msg').css('display', 'block').text('You must accept the terms to continue.');
            return false;
        }
        jQuery.ajax({
            type: 'POST',
            url: jQuery('form#mob_create_account_form').attr('action'),
            data: {
                firstname:     'Lifelong',
                lastname:      'Learner',
                email:         jQuery('#mob_create_account_form #mobile-create-user-input').val(),
                password:      jQuery('#mob_create_account_form #mobile-create-pwd-input').val(),
                confirmation:  jQuery('#mob_create_account_form #mobile-create-conf-pwd-input').val()
            },
            success: function(data, textStatus, jqXHR) {
                if (data['status'] == 'success') {
                    jQuery('form#mob_create_account_form .input-success-msg').css('display', 'block').html('Success! Your account has been created');
                    location.reload();
                } else if (data['status'] == 'confirmation') {
                    jQuery('form#mob_create_account_form .input-success-msg').css('display', 'block').html(data['message']);
                }
                else {
                    jQuery('form#mob_create_account_form .input-error-msg').css('display', 'block').html(data['message']);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                jQuery('form#mob_create_account_form .input-error-msg').css('display', 'block').text('There was an error creating your account. Please try again');
            }
        });
    });

    jQuery('.pop_panel_new_pc').on('click',function(){
        jQuery('#mobile-priority-code-value').val('');
        jQuery('.priority_code_submited').fadeOut();
        jQuery('.pc_form_container').fadeIn();
    });

    jQuery('.mob_forgot_psw_link').on('click',function(){
        jQuery('.mobile_sing_in').fadeOut();
        jQuery('.mobile_psw_assist').fadeIn();
    });

    jQuery('.mob_create_account_link').on('click',function(){
        jQuery('.mobile_sing_in').fadeOut();
        jQuery('.mobile_create_account').fadeIn();
    });

    jQuery('.pop_panel_cancel, .mob-close-panel').on('click',function(){
        mobileSubPanels();
    });

   jQuery('a.mob-sign-in-js').on('click',function(e){
       e.preventDefault();
       mobPopPanel('.mob-pop-panel-sign-in');
   });

    jQuery('.mob-priority-code').on('click',function(){
        mobPopPanel('.mob-pop-panel-priority-code');
    });

    //Added close button for notifications
    var  messages = jQuery('ul.messages li[class*="-msg"]');
    messages.each(function(){
        jQuery(this).append('<a class="close-button" href="javascript:void(0)">Close</a>');
        jQuery('.close-button').click(function(){
            jQuery(this).parent(messages).css('display', 'none');
        });

    });
});

// helper functions
function closeBalloon(element){
    element.find('.input-error-msg').hide();
    element.find('.reset-value').val('');
    element.hide();
    return false;
}

function refreshTotals(totals){
    var refreshCallback = function (totalsHtml) {
        var originalTotals = jQuery('#shopping-cart-totals-table');
        if (!isEmpty(originalTotals)) {
            var parentTotals = originalTotals.parent();
            originalTotals.remove();
            parentTotals.append(totalsHtml);
        }
    };
    if (typeof (totals) != 'undefined' && (typeof(totals) == 'string' || totals instanceof String)) {
        refreshCallback(totals);
    } else {
        new Ajax.Request("/checkout/cart/refreshtotals", {
            method:'post',
            parameters: {},
            onSuccess: function(transport){
                var response = transport.responseText;
                var dataJSON = response.evalJSON();
                if (typeof(dataJSON.totals) != 'undefined') {
                    refreshCallback(dataJSON.totals);
                }
            },
            onFailure: function(){
                console.log('Server not response');
            }
        });
    }
}

function refreshReviewTotals(totals){
    var refreshCallback = function (totalsHtml) {
        var originalTotals = jQuery('#review-shopping-cart-totals-table');
        if (!isEmpty(originalTotals)) {
            originalTotals.replaceWith(totalsHtml);
        }
    };
    if (typeof (totals) != 'undefined' && (typeof(totals) == 'string' || totals instanceof String)) {
        refreshCallback(totals);
    } else {
        new Ajax.Request("/checkout/cart/refreshreviewtotals", {
            method:'post',
            parameters: {},
            onSuccess: function(transport){
                var response = transport.responseText;
                var dataJSON = response.evalJSON();
                if (typeof(dataJSON.reviewTotals) != 'undefined') {
                    refreshCallback(dataJSON.reviewTotals);
                }
            },
            onFailure: function(){
                console.log('Server not response');
            }
        });
    }
}

function giftRemove(urlGiftRemove) {
    jQuery('.gc-totals-please-wait').show();
    var parameters = {};
    if ($(document.body).hasClassName('checkout-onepage-index')) {
        parameters.from_checkout = 1;
    }
    new Ajax.Request(urlGiftRemove,
        {
            method:'post',
            parameters: parameters,
            onSuccess: function(transport){
                var response = transport.responseText;
                var dataJSON = response.evalJSON();
                var result = false;
                var message;
                if (typeof(dataJSON.result) != 'undefined' && dataJSON.result) {
                    result = true;
                    if (typeof(dataJSON.totals) != 'undefined') {
                        refreshTotals(dataJSON.totals);
                    } else {
                        refreshTotals();
                    }
                    if (!isEmpty(jQuery('#review-shopping-cart-totals-table'))) {
                        if (typeof(dataJSON.reviewTotals) != 'undefined') {
                            refreshReviewTotals(dataJSON.reviewTotals);
                        } else {
                            refreshReviewTotals();
                        }
                    }
                    if (typeof(checkout) != 'undefined') {
                        if (typeof(dataJSON.update_section) != 'undefined') {
                            checkout.setStepResponse(dataJSON);
                        }
                        if (typeof(dataJSON.goto_payment) != 'undefined' && dataJSON.goto_payment && checkout.currentStep == 'review') {
                            checkout.gotoSection('payment', false);
                        }
                    }
                    if (!isEmpty(jQuery('#giftcard-form'))) {
                        jQuery('#giftcard-form').show();
                        jQuery('#giftcard_balance_lookup_content').html("");
                        jQuery('#giftcard_code').val("");
                    }
                }
                if (typeof(dataJSON.message) != 'undefined' && dataJSON.message) {
                    message = dataJSON.message;
                }

                if (!result && message) {
                    alert(message);
                }
                jQuery('.gc-totals-please-wait').hide();
            },
            onFailure: function(){
                console.log('Server not response');
                jQuery('.gc-totals-please-wait').hide();
            }
        });
}

function isEmpty(obj) {

    // null and undefined are "empty"
    if (obj == null) return true;

    // Assume if it has a length property with a non-zero value
    // that that property is correct.
    if (obj.length > 0)    return false;
    if (obj.length === 0)  return true;

    // Otherwise, does it have any properties of its own?
    // Note that this doesn't handle
    // toString and valueOf enumeration bugs in IE < 9
    for (var key in obj) {
        if (hasOwnProperty.call(obj, key)) return false;
    }

    return true;
}



// ********** HEADER **************

// change arrow state and bkg color when a menu is open/closed
function arrowUp(jqObj){
    jqObj.removeClass('nav-arrow-down').addClass('nav-arrow-up');
}

function arrowDown(jqObj){
    jqObj.removeClass('nav-arrow-up').addClass('nav-arrow-down');
}

var hideLoginForm = 1;

// close login-form
function closeLoginForm(){
    arrowDown(jQuery('.signed-out-container'));
    arrowDown(jQuery('.my-digital-library'));
    jQuery('.header-terms').css('display','none');
    jQuery('.header-privacy-policy').css('display','none');

    arrowDown(jQuery('.sign-container'));
    arrowDown(jQuery('.my-digital-library'));
    loginForm.css('display','none');
    jQuery('form#form-register').hide();
    jQuery('form#form-forgot-password').hide();
    jQuery('form#login-form').show();
}

// close all open elements on header
function closeElementsHeader() {

    jQuery('.messages-wrapper').css('display','none');
    loginForm = jQuery('.login-form-dropdown');
    userMenu = jQuery('.logged-in-user-dropdown');
    userMenuContainer = jQuery('.signed-in-container');
    // close megamenus
    arrowDown(megamenu_trigger);
    megamenu.css('display','none');

    //close all tooltip-balloon's on header
    jQuery('.header-container .tooltip-balloon').css('display','none');

    //close logged-in user menu dropdown
    arrowDown(userMenuContainer);
    userMenu.css('display','none');

    //close mini-cart & mini-wishlist
    jQuery('.mini-widget').removeClass('open');
    jQuery('.mini-widget .dropdown-menu').css('display','none');

    //close all opened tooltips
    closeToolTips();

    clearMessages();
}

function closeToolTips(){
    jQuery('.tooltip .tooltip-balloon').css('display','none');
    jQuery('.tooltip-detached').css('display','none');
}

// megamenu show/hide
function showHideMegamenu(){
    megamenu_state = megamenu.css('display');

    if (megamenu_state == 'none'){
        closeElementsHeader();
        arrowUp(megamenu_trigger);
    }else{
        arrowDown(megamenu_trigger);

    }
    megamenu.toggle();
}


// priority code validation
function validatePriorityCode(val){
    // AJAX call to validate the entered priority code value
    // should return success or failed. (there seems to be more states).
    // Please check latest PRD's for header, point 1.0.4.2
    jQuery.ajax({
        type: 'POST',
        url: globalHeader.priorityCodeSubmitUrl,
        data: { code: val },
        beforeSend: function(jqXHR) {
            beforeAjax();
        },
        success: function(data, textStatus, jqXHR) {
            if (data == 'success') {
                priorityCodeApplied(val);
                closeBalloon(jQuery('.priority-code-form'));
                window.location = document.URL.split("?")[0];
            } else {
//                if(typeof window.utag_data == "object" && typeof window.utag == "object" && typeof window.utag.link == "function"){
//                    if (window.tgc_tealiumObj.badPriorityCodeEvent(val)) {}
//                }
                jQuery('.priority-code-form .input-error-msg').css('display','block').text('Priority Code ' + val + ' is not valid');
                // mobile
                jQuery('.priority_code_submited .descriptions').css('color','red').text('Priority Code ' + val + ' is not valid');
                jQuery('#priority-code-value').val('');
                return false;
            }
        },
        error: function () {
            console.log("Server Error.");
        },
        complete: function(jqXHR, textStatus) {
            afterAjax();
        }
    });
}

function priorityCodeApplied(val) {
    jQuery('.priority-code-empty').hide();
    jQuery('span.p-number').text(val);
    jQuery('.priority-code-applied').show();
    jQuery('.priority-code-form').hide().css('marginLeft','-50%');
    //mobile
    jQuery('.pc_form_container').hide();
    jQuery('.priority_code_submited').show();
    jQuery('div.priority_code_submited span.descriptions').text('Priority Code ' + val + ' Applied');
}

// *********** END HEADER ***********

function clearMessages() {
    jQuery('.login-form-dropdown .messages-wrapper').css('display','none');
    jQuery('.input-error-msg').text('').css('display', 'none');
    jQuery('.input-success-msg').text('').css('display', 'none');
}

function beforeAjax() {
    clearMessages();
    //show ajax loader?
}

function afterAjax() {
    //hide ajax loader?
}

function tablet_size() {
    if(jQuery(window).width() > 767 && jQuery(window).width() < 961 ) {
        equal_great_height(true,".customer_more_box_1", ".customer_more_box_2");
    }
    else{
        equal_great_height(false,".customer_more_box_1", ".customer_more_box_2");
    }
}

function equal_great_height(add_height, elem_1, elem_2){
    if (add_height){
        jQuery(elem_1 +", " + elem_2).height('inherit');
        if (jQuery(elem_1).height() > jQuery(elem_2).height()){
            jQuery(elem_2).height(jQuery(elem_1).height());
        }
        else{
            jQuery(elem_1).height(jQuery(elem_2).height());
        }
    }
    else{
        jQuery(elem_1 +", " + elem_2).height('inherit');
    }

}

function coupons_height(){
        equal_great_height(true,".priority_code_label", ".coupon_code label");
}

function evt_det_recomendations_height(){
    if(jQuery(window).width() > 767) {
        jQuery('.events-index-view .evt_det_more .recomendations').css('margin-top', '-'+jQuery('.events-index-view .evt_det_similar_events').height()+'px');
     }
    else{
        jQuery('.events-index-view .evt_det_more .recomendations').removeAttr( 'style' );
    }
}

function initDetachedTooltips(){
    jQuery('.tooltip-detached').appendTo(jQuery('body'));
}

jQuery(window).resize(function() {
    tablet_size();
    coupons_height();
    evt_det_recomendations_height();
});

userAgent = window.navigator.userAgent;
if(/iP(hone|od|ad)/.test(userAgent) && (jQuery(window).width() < 767)) {
    jQuery(window).on('scroll', function() {
        jQuery('.header.container').css({'top': window.scrollY + "px", 'position':'absolute'});
    });
}

/* Custom click event for mobile devices with touch screen*/

if( (navigator.userAgent.match(/Android/i)) ||
    (navigator.userAgent.match(/webOS/i)) ||
    (navigator.userAgent.match(/iPhone/i)) ||
    (navigator.userAgent.match(/iPad/i)) ||
    (navigator.userAgent.match(/iPod/i)) ||
    (navigator.userAgent.match(/BlackBerry/i)) ||
    (navigator.userAgent.match(/Windows Phone/i)) ||
    (navigator.userAgent.match(/Silk/i))
) {
    customClickEvent = 'touchend';
    customMousedownEvent = 'touchstart';
    customMouseupEvent = 'touchend';
    isTouchable = true;
} else {
    customClickEvent = 'click';
    customMousedownEvent = 'mousedown';
    customMouseupEvent = 'mouseup';
    isTouchable = false;
}

/* OOP Custom Tooltip
*
* trigger - element for which will be related tooltip
* content - could be text or dom element(will be used them html)
* position - could have top or bottom (should be text)
* customClass - could be used custom class for styling tooltip
*
* */
customTooltip = {
    isClosed: true,
    show: function(trigger, content, position, customClass, showCloseButton) {

        jQuery('.custom-tooltip').remove();
        var curposition = null;
        var curcontent = null;
        var curcustomClass = null;
        this.closeButton = false;
        this.el = null;
        this.trigger = trigger;
        var _this = this;

        if(jQuery.type(content) === 'string') {
            curcontent = content;
            _this.contenttype = 'string';
        } else if(jQuery.type(content) === 'object') {
            curcontent = content.html();
            _this.contenttype = 'object';
        }

        if(position != 'undefined') {
            curposition = position;
        } else {
            curposition = 'top';
        }

        if(customClass !== 'undefined' || customClass !== null) {
            curcustomClass = customClass;
        }

        var tooltip = jQuery('<div class="custom-tooltip ' + curposition + ' ' + curcustomClass + '"' + '><div class="tooltip-arrow"></div></div>');

        if(curposition === 'top') {
            tooltip.append(curcontent).appendTo('body').position({
                my: "bottom",
                at: curposition + "-8",
                collision: "fit none",
                of: trigger
            });
            tooltip.find('.tooltip-arrow').position({
                my: "top",
                at: curposition + "-9",
                collision: "none",
                of: trigger
            });
        } else if(curposition === 'bottom') {
            tooltip.append(curcontent).appendTo('body').position({
                my: "top",
                at: curposition + "+8",
                collision: "fit none",
                of: trigger
            });
            tooltip.find('.tooltip-arrow').position({
                my: "top",
                at: curposition + "+9",
                collision: "none",
                of: trigger
            });
        }
        if(showCloseButton === true) {
            tooltip.prepend('<div class="close-balloon"></div>');
        }

        tooltip.animate({opacity: 1}, 250, function() {
            _this.isClosed = false;
            _this.addEvents();
            _this.el = tooltip;
            jQuery(this).off(customClickEvent);
        });
    },
    close: function() {
        var _this = this;
        if(this.el !== null) {
            this.el.animate({opacity: 0}, 250, function() {
                _this.el.remove();
                _this.isClosed = true;
                _this.trigger = null;
            });
        }
        jQuery('body').off(customClickEvent);
    },
    addEvents: function() {
        var _this = this;
        jQuery('body').on(customClickEvent, function(e) {
            e.stopPropagation();
            if( _this.contenttype === 'object' && _this.el !== null && jQuery(e.target).parents('.custom-tooltip')[0] === _this.el[0] && !jQuery(e.target).hasClass('close-balloon')) {
                return false;
            } else if( _this.contenttype === 'string' && _this.el !== null && jQuery(e.target).parents('.custom-tooltip').context === _this.el[0] ) {
                return false;
            } else {
                _this.close();
                console.log();
            }

        });
        jQuery(window).on('resize', function() {
            if (!_this.isClosed) {
                _this.close()
            }
        })
    }
}

/* Functio to check is device mobile or not. */
function isMobile()
{
    if( (navigator.userAgent.match(/Android/i)) ||
        (navigator.userAgent.match(/webOS/i)) ||
        (navigator.userAgent.match(/iPhone/i)) ||
        (navigator.userAgent.match(/iPad/i)) ||
        (navigator.userAgent.match(/iPod/i)) ||
        (navigator.userAgent.match(/BlackBerry/i)) ||
        (navigator.userAgent.match(/Windows Phone/i)) ||
        (navigator.userAgent.match(/Silk/i))
        ) {
        return true;
    }

    return false;
}

jQuery(function($) {
    /* Function for hover effects for nav, for descktop and mobile devices*/
    if( isTouchable === true ) {
        $('.vert-navigation li a').on('touchend', function() {
            $(this).toggleClass('hover');
        });
    } else {
        $('.vert-navigation li a').hover(function() {
            $(this).toggleClass('hover');
        });
    }

    /* For View more button on Paryner page */
    $('.professor_info').on(customClickEvent, '.view-more-button', function(e) {
        e.preventDefault();
        var _that = $(this);
        if(!$(this).hasClass('rest')) {
                _that.parent().find('.professor_desc').transit({'height' : _that.parent().find('.prof_desc_container').height()}, 'fast');
                _that.addClass('rest').text('View Less');
            } else {
                _that.parent().find('.professor_desc').transit({'height' : '127px'}, 'fast');
                _that.removeClass('rest').text('View More');
            }
        e.stopPropagation();
    });

    /* Hover for product blocks */
    $('.boutique-itemslider-wrapper a, ' +
        '.professor-products a, ' +
        '.category-products a, ' +
        '.new-itemslider-wrapper .slides a, ' +
        '.upsell-itemslider-wrapper .slides a, ' +
        '.bestseller-itemslider-wrapper .slides a, ' +
        '.recentlyshopped-itemslider-wrapper .slides a, ' +
        '.itemslider-categories a, ' +
        '.about-pages-carousel a, ' +
        '.itemslider-categories a, ' +
        '.boutique-itemslider-wrapper li, ' +
        '.professor-products li, ' +
        '.category-products-grid li, ' +
        '.recentlyshopped-itemslider-wrapper .slides li, ' +
        '.new-itemslider-wrapper .slides li, ' +
        '.upsell-itemslider-wrapper .slides li, ' +
        '.bestseller-itemslider-wrapper .slides li'
    )
    .hover(function() {
        $(this).toggleClass('hover');
    })
});
