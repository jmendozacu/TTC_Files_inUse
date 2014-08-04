/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Customer
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

customerDashboardForm = Class.create({
    initialize: function (config) {
        this.form = config.form;
        this.formSubmitUrl = typeof(config.formSubmitUrl) != 'undefined' ? config.formSubmitUrl : this.form.action;
        this.formContainer = typeof(config.formContainer) != 'undefined' ? config.formContainer : null;
        this.onFormSubmitSuccessCallback = typeof(config.onFormSubmitSuccessCallback) != 'undefined' ? config.onFormSubmitSuccessCallback : null;
        this.autoHideFormContainerDelay = typeof(config.autoHideFormContainerDelay) != 'undefined' ? config.autoHideFormContainerDelay : null;
        this.onFormContainerHideCallback = typeof(config.onFormContainerHideCallback) != 'undefined' ? config.onFormContainerHideCallback : null;
        this.loaderHoverContainer = typeof(config.loaderHoverContainer) != 'undefined' ? config.loaderHoverContainer : null;
        this.resultNoticeContainer = typeof(config.resultNoticeContainer) != 'undefined' ? config.resultNoticeContainer : null;
        this.resultNoticeMessagesContainer = typeof(config.resultNoticeMessagesContainer) != 'undefined' ? config.resultNoticeMessagesContainer : this.resultNoticeContainer;
        this.resultNoticeErrorClass = typeof(config.resultNoticeErrorClass) != 'undefined' ? config.resultNoticeErrorClass : 'error';
        this.resultNoticeSuccessClass = typeof(config.resultNoticeSuccessClass) != 'undefined' ? config.resultNoticeSuccessClass : 'success';
        this.autoHideSuccessNoticeDelay = typeof(config.autoHideSuccessNoticeDelay) != 'undefined' ? config.autoHideSuccessNoticeDelay : null;
        this.autoHideErrorNoticeDelay = typeof(config.autoHideErrorNoticeDelay) != 'undefined' ? config.autoHideErrorNoticeDelay : null;
        this.successMsgPrefix = typeof(config.successMsgPrefix) != 'undefined' ? config.successMsgPrefix : '';
        this.errorMsgPrefix = typeof(config.errorMsgPrefix) != 'undefined' ? config.errorMsgPrefix : '';

        this.form.observe('submit', this.formSubmit.bind(this));
    },

    showLoader: function (loaderHoverContainer) {
        if (typeof (loaderHoverContainer) == 'undefined') {
            loaderHoverContainer = this.loaderHoverContainer;
        }

        if (loaderHoverContainer) {
            loaderHoverContainer.show();
        }
    },

    hideLoader: function (loaderHoverContainer) {
        if (typeof (loaderHoverContainer) == 'undefined') {
            loaderHoverContainer = this.loaderHoverContainer;
        }

        if (loaderHoverContainer) {
            loaderHoverContainer.hide();
        }
    },

    showResultNotice: function (type, messages, resultNoticeContainer, resultNoticeMessagesContainer) {
        if (typeof (resultNoticeContainer) == 'undefined') {
            resultNoticeContainer = this.resultNoticeContainer;
        }
        if (typeof (resultNoticeMessagesContainer) == 'undefined') {
            resultNoticeMessagesContainer = this.resultNoticeMessagesContainer;
        }

        if (resultNoticeContainer && resultNoticeMessagesContainer) {
            if (type == 'success') {
                var prefix = this.successMsgPrefix;
                resultNoticeContainer.addClassName(this.resultNoticeSuccessClass);
                resultNoticeContainer.removeClassName(this.resultNoticeErrorClass);
            } else {
                var prefix = this.errorMsgPrefix;
                resultNoticeContainer.removeClassName(this.resultNoticeSuccessClass);
                resultNoticeContainer.addClassName(this.resultNoticeErrorClass);
            }
            resultNoticeMessagesContainer.update(prefix + messages.join('<br />' + prefix));
            resultNoticeContainer.show();
        }
    },

    hideResultNotice: function (resultNoticeContainer) {
        if (typeof(resultNoticeContainer) == 'undefined') {
            resultNoticeContainer = this.resultNoticeContainer;
        }

        if (resultNoticeContainer) {
            resultNoticeContainer.hide();
        }
    },

    hideFormContainer: function () {
        if (this.formContainer) {
            this.formContainer.hide();
        }
        if (this.onFormContainerHideCallback) {
            this.onFormContainerHideCallback();
        }
    },

    formSubmit: function (event) {
        Event.stop(event);

        var inputs = this.form.select('input,select');
        var formValid = true;
        for (var i = 0, length = inputs.length; i < length; ++i) {
            if (!Validation.validate(inputs[i])) {
                formValid = false;
            }
        }

        if (!formValid) {
            return;
        }

        this.showLoader();

        new Ajax.Request(this.formSubmitUrl, {
            method: 'post',
            onFailure: this.ajaxFailure.bind(this),
            onSuccess: this.ajaxSuccess.bind(this),
            parameters: this.form.serialize()
        });
    },

    ajaxFailure: function () {
        this.hideLoader();
        alert('Unable to reach server, please try again later');
    },

    ajaxSuccess: function (transport) {
        this.hideLoader();
        var response = transport.responseText.evalJSON();
        if (typeof(response.success) != 'undefined' && response.success == true) {
            if (typeof(response.success_msg) != 'undefined') {
                this.showResultNotice('success', [response.success_msg]);
                if (this.autoHideSuccessNoticeDelay) {
                    setTimeout(this.hideResultNotice.bind(this), this.autoHideSuccessNoticeDelay);
                }
                if (this.autoHideFormContainerDelay) {
                    setTimeout(this.hideFormContainer.bind(this), this.autoHideFormContainerDelay);
                }
            } else {
                this.hideResultNotice();
                this.hideFormContainer();
            }
            if (this.onFormSubmitSuccessCallback) {
                this.onFormSubmitSuccessCallback();
            }
        } else {
            this.showResultNotice('error', (typeof(response.errors) != 'undefined' && response.errors.length > 0 ? response.errors : ['Unknown internal error, please try again later']));
            if (this.autoHideErrorNoticeDelay) {
                setTimeout(this.hideResultNotice.bind(this), this.autoHideErrorNoticeDelay);
            }
        }
    }
});

customerDashboardAddressForm = Class.create(customerDashboardForm, {
    initialize: function ($super, config) {
        $super(config);
        this.addressListContainer = config.addressListContainer;
        this.addressDeleteUrl = typeof(config.addressDeleteUrl) != 'undefined' ? config.addressDeleteUrl : null;
        this.newAddressFormDisplayContainer = typeof(config.newAddressFormDisplayContainer) != 'undefined' ? config.newAddressFormDisplayContainer : null;
        this.newAddressFormContainer = typeof(config.newAddressFormContainer) != 'undefined' ? config.newAddressFormContainer : null;
        this.addNewAddressButton = typeof(config.addNewAddressButton) != 'undefined' ? config.addNewAddressButton : null;
        this.addressesListCloseButton = typeof(config.addressesListCloseButton) != 'undefined' ? config.addressesListCloseButton : null;
        this.addressesListCloseButtonClickCallback = typeof(config.addressesListCloseButtonClickCallback) != 'undefined' ? config.addressesListCloseButtonClickCallback : null;
        this.formCancelButton = typeof(config.formCancelButton) != 'undefined' ? config.formCancelButton : null;
        this.addressEditButtonsSelector = typeof(config.addressEditButtonsSelector) != 'undefined' ? config.addressEditButtonsSelector : null;
        this.addressEditButtons = [];
        this.addressDeleteButtonsSelector = typeof(config.addressDeleteButtonsSelector) != 'undefined' ? config.addressDeleteButtonsSelector : null;
        this.addressDeleteButtons = [];
        this.addressEditFormContainerUpSelector = typeof(config.addressEditFormContainerUpSelector) != 'undefined' ? config.addressEditFormContainerUpSelector : null;
        this.addressEditFormContainerDataRowSelector = typeof(config.addressEditFormContainerDataRowSelector) != 'undefined' ? config.addressEditFormContainerDataRowSelector : null;
        this.defaultBillingOneLineContainer = typeof(config.defaultBillingOneLineContainer) != 'undefined' ? config.defaultBillingOneLineContainer : null;
        this.defaultShippingOneLineContainer = typeof(config.defaultShippingOneLineContainer) != 'undefined' ? config.defaultShippingOneLineContainer : null;
        this.defaultNoAddressesOneLineContainer = typeof(config.defaultNoAddressesOneLineContainer) != 'undefined' ? config.defaultNoAddressesOneLineContainer : null;
        this.addressOneLineTemplate = typeof(config.addressOneLineTemplate) != 'undefined' ? config.addressOneLineTemplate : null;
        this.addressRowTemplate = typeof(config.addressRowTemplate) != 'undefined' ? config.addressRowTemplate : null;
        this.newAddressRowFullTemplate = typeof(config.newAddressRowFullTemplate) != 'undefined' ? config.newAddressRowFullTemplate : null;

        this.formDefaults = this.form.serialize({hash: true});

        if (this.addNewAddressButton) {
            this.addNewAddressButton.observe('click', this.addNewAddressButtonClick.bind(this));
        }

        if (this.addressesListCloseButton) {
            this.addressesListCloseButton.observe('click', this.addressesListCloseButtonClick.bind(this));
        }

        if (this.formCancelButton) {
            this.formCancelButton.observe('click', this.formCancelButtonClick.bind(this));
        }

        if (this.addressEditButtonsSelector) {
            this.addressEditButtons = this.addressListContainer.select(this.addressEditButtonsSelector);
            this.addressEditButtons.each(function (el) {
                el.observe('click', this.addressEditButtonClick.bind(this));
            }.bind(this));
        }

        if (this.addressDeleteButtonsSelector) {
            this.addressDeleteButtons = this.addressListContainer.select(this.addressDeleteButtonsSelector);
            this.addressDeleteButtons.each(function (el) {
                el.observe('click', this.addressDeleteButtonClick.bind(this));
            }.bind(this));
        }
    },

    addNewAddressButtonClick: function (event) {
        Event.stop(event);
        var button = event.findElement();
        if (button.hasClassName('disabled')) {
            return;
        }

        this.parkAddressForm();
        this.newAddressFormDisplayContainer.show();
        this.disableAddNewAddressButton();
        this.disableAddressListCloseButton();
        this.disableAddressEditButtons();
    },

    addressesListCloseButtonClick: function (event) {
        Event.stop(event);
        var button = event.findElement();
        if (button.hasClassName('disabled')) {
            return;
        }

        if (this.addressesListCloseButtonClickCallback) {
            this.addressesListCloseButtonClickCallback(event);
        }
    },

    hideFormContainer: function () {
        this.hideAddressForm();
        if (this.onFormContainerHideCallback) {
            this.onFormContainerHideCallback();
        }
    },

    hideAddressForm: function () {
        this.parkAddressForm();
        this.newAddressFormDisplayContainer.hide();
        this.enableAddNewAddressButton();
        this.enableAddressListCloseButton();
        this.enableAddressEditButtons();
        this.enableAddressDeleteButtons();
    },

    formCancelButtonClick: function (event) {
        Event.stop(event);
        this.showAddressDataRow(event.findElement());
        this.parkAddressForm();
        this.hideAddressForm();
    },

    addressEditButtonClick: function (event) {
        Event.stop(event);
        var button = event.findElement();
        if (button.hasClassName('disabled')) {
            return;
        }

        this.hideAddressDataRow(button);
        var container = button.up(this.addressEditFormContainerUpSelector);
        if (container) {
            var addressData = container.down('input[name="address_data"]');
            if (addressData) {
                addressData = addressData.getValue().evalJSON();
                this.prepareForm(addressData);
                this.form.down('input[name="address_id"]').setValue(addressData.id);
                this.disableAddressDeleteButtons(addressData.id);
            } else {
                this.prepareForm(this.formDefaults);
            }
            container.insert({bottom: this.formContainer});
        }

        this.disableAddNewAddressButton();
        this.disableAddressListCloseButton();
        this.disableAddressEditButtons();
    },

    addressDeleteButtonClick: function (event) {
        Event.stop(event);
        var button = event.findElement();
        if (button.hasClassName('disabled')) {
            return;
        }

        var addressData = button.up(this.addressEditFormContainerDataRowSelector).down('input[name="address_data"]');
        if (!addressData) {
            return;
        }

        addressData = addressData.getValue().evalJSON();

        if (confirm('Are you sure you want to delete this address?')) {
            this.showLoader(button.up(this.addressEditFormContainerDataRowSelector).down('.loader'));

            new Ajax.Request(this.addressDeleteUrl, {
                method: 'post',
                onFailure: function () {
                    this.hideLoader(button.up(this.addressEditFormContainerDataRowSelector).down('.loader'));
                    alert('Unable to reach server, please try again later');
                }.bind(this),
                onSuccess: function (transport) {
                    this.hideLoader(button.up(this.addressEditFormContainerDataRowSelector).down('.loader'));
                    var response = transport.responseText.evalJSON();
                    if (typeof(response.success) != 'undefined' && response.success == true) {
                        var addressId = response.address_id;
                        if (addressId) {
                            this.addressListContainer.select('.address-' + addressId).each(function (el) {
                                if (el.hasClassName('default-billing-row') || el.hasClassName('default-shipping-row')) {
                                    el.hide();
                                    el.down(this.addressEditFormContainerDataRowSelector).update('');
                                    if (el.hasClassName('default-billing-row') && this.defaultBillingOneLineContainer) {
                                        this.defaultBillingOneLineContainer.up().hide();
                                    }
                                    if (el.hasClassName('default-shipping-row') && this.defaultShippingOneLineContainer) {
                                        this.defaultShippingOneLineContainer.up().hide();
                                    }
                                    el.removeClassName('address-' + addressId);
                                } else {
                                    el.remove();
                                }
                            }.bind(this));
                        }
                    } else {
                        var noticeContainer = button.up(this.addressEditFormContainerDataRowSelector).down('.notice-container');
                        this.showResultNotice('error', (typeof(response.errors) != 'undefined' && response.errors.length > 0 ? response.errors : ['Unknown internal error, please try again later']), noticeContainer, noticeContainer.down('.msg'));
                        if (this.autoHideErrorNoticeDelay) {
                            setTimeout(this.hideResultNotice.bind(this, noticeContainer), this.autoHideErrorNoticeDelay);
                        }
                    }
                }.bind(this),
                parameters: {'address_id': addressData.id}
            });
        }
    },

    showAddressDataRow: function (button) {
        var container = button.up(this.addressEditFormContainerUpSelector);
        if (container) {
            container = container.up();
            var dataRow = container.down(this.addressEditFormContainerDataRowSelector);
            if (dataRow) {
                dataRow.show();
            }
        }
    },

    hideAddressDataRow: function (button) {
        var container = button.up(this.addressEditFormContainerUpSelector);
        if (container) {
            container = container.up();
            var dataRow = container.down(this.addressEditFormContainerDataRowSelector);
            if (dataRow) {
                dataRow.hide();
            }
        }
    },

    enableAddNewAddressButton: function () {
        if (this.addNewAddressButton) {
            this.addNewAddressButton.removeClassName('disabled');
        }
    },

    disableAddNewAddressButton: function () {
        if (this.addNewAddressButton) {
            this.addNewAddressButton.addClassName('disabled');
        }
    },

    enableAddressListCloseButton: function () {
        if (this.addressesListCloseButton) {
            this.addressesListCloseButton.removeClassName('disabled');
        }
    },

    disableAddressListCloseButton: function () {
        if (this.addressesListCloseButton) {
            this.addressesListCloseButton.addClassName('disabled');
        }
    },

    enableAddressEditButtons: function () {
        if (this.addressEditButtons) {
            this.addressEditButtons.each(function(el) {el.removeClassName('disabled');});
        }
    },

    disableAddressEditButtons: function () {
        if (this.addressEditButtons) {
            this.addressEditButtons.each(function(el) {el.addClassName('disabled');});
        }
    },

    enableAddressDeleteButtons: function () {
        if (this.addressDeleteButtons) {
            this.addressDeleteButtons.each(function(el) {el.removeClassName('disabled');});
        }
    },

    disableAddressDeleteButtons: function (addressId) {
        var deleteButtons = [];
        if (addressId) {
            deleteButtons = this.addressListContainer.select('.address-' + addressId + ' ' + this.addressDeleteButtonsSelector);
        } else {
            deleteButtons = this.addressDeleteButtons;
        }

        if (deleteButtons) {
            deleteButtons.each(function(el) {el.addClassName('disabled');});
        }
    },

    parkAddressForm: function () {
        this.newAddressFormContainer.insert({bottom: this.formContainer});
        this.resetAddressForm();
    },

    resetAddressForm: function () {
        this.prepareForm(this.formDefaults);
        this.form.down('input[name="address_id"]').setValue('');
    },

    fillForm: function (data) {
        var countrySelect = this.form.down('select[name="country_id"]');
        if (countrySelect && typeof(data.country_id) !== 'undefined') {
            countrySelect.setValue(data.country_id);
            if ("createEvent" in document) {
                var evt = document.createEvent("HTMLEvents");
                evt.initEvent("change", false, true);
                countrySelect.dispatchEvent(evt);
            }
            else {
                countrySelect.fireEvent("onchange");
            }
        }

        var streetInputs = this.form.select('input[name="street[]"]');
        if (streetInputs) {
            for (var j = 0, lenj = streetInputs.length; j < lenj; ++j) {
                if (typeof(data['street' + (j+1)]) != 'undefined') {
                    streetInputs[j].setValue(data['street' + (j+1)]);
                } else {
                    streetInputs[j].setValue('');
                }
            }
        }

        var formFields = this.form.select('input,select');
        for (var i = 0, len = formFields.length; i < len; ++i) {
            formFields[i].removeClassName('validation-passed');
            if (formFields[i].name == 'country_id' || formFields[i].name == 'street[]') {
                continue;
            }

            if (formFields[i].type == 'checkbox') {
                formFields[i].checked = typeof(data[formFields[i].name]) != 'undefined';
            } else {
                formFields[i].setValue(typeof(data[formFields[i].name]) == 'undefined' ? '' : data[formFields[i].name]);
            }
        }
    },

    prepareForm: function (data) {
        this.fillForm(data);

        var defaultBillingCheckbox = this.form.down('input[name="default_billing"]');
        if (defaultBillingCheckbox) {
            if (typeof(data.is_default_billing) != 'undefined' && data.is_default_billing) {
                defaultBillingCheckbox.checked = false;
                defaultBillingCheckbox.up().hide();
            } else {
                defaultBillingCheckbox.up().show();
            }
        }

        var defaultShippingCheckbox = this.form.down('input[name="default_shipping"]');
        if (defaultShippingCheckbox) {
            if (typeof(data.is_default_shipping) != 'undefined' && data.is_default_shipping) {
                defaultShippingCheckbox.checked = false;
                defaultShippingCheckbox.up().hide();
            } else {
                defaultShippingCheckbox.up().show();
            }
        }
    },

    ajaxSuccess: function (transport) {
        this.hideLoader();
        var response = transport.responseText.evalJSON();
        if (typeof(response.success) != 'undefined' && response.success == true) {
            this.processSavedAddressData(response.address_data);

            if (typeof(response.success_msg) != 'undefined') {
                this.addressListContainer.select('.address-' + response.address_data.id).each(function (el) {
                    var noticeContainer = el.down('.notice-container');
                    this.showResultNotice('success', [response.success_msg], noticeContainer, noticeContainer.down('.msg'));
                    if (this.autoHideSuccessNoticeDelay) {
                        setTimeout(this.hideResultNotice.bind(this, noticeContainer), this.autoHideSuccessNoticeDelay);
                    }
                }.bind(this));
                this.hideFormContainer();
            } else {
                this.hideResultNotice();
                this.hideFormContainer();
            }
            if (this.onFormSubmitSuccessCallback) {
                this.onFormSubmitSuccessCallback();
            }
        } else {
            this.showResultNotice('error', (typeof(response.errors) != 'undefined' && response.errors.length > 0 ? response.errors : ['Unknown internal error, please try again later']));
            if (this.autoHideErrorNoticeDelay) {
                setTimeout(this.hideResultNotice.bind(this), this.autoHideErrorNoticeDelay);
            }
        }
    },

    processSavedAddressData: function (addressData) {
        var formAddressId = this.form.down('input[name="address_id"]').getValue();
        var isDefaultBilling = typeof(addressData.is_default_billing) != 'undefined' && addressData.is_default_billing;
        var isDefaultShipping = typeof(addressData.is_default_shipping) != 'undefined' && addressData.is_default_shipping;
        if (isDefaultBilling || isDefaultShipping) {
            if (!(this.form.up('.default-billing-row') || this.form.up('.default-shipping-row'))) {
                this.addressListContainer.select('.address-' + addressData.id).each(function (el) {
                    if (!el.hasClassName('default-billing-row') && !el.hasClassName('default-shipping-row')) {
                        el.remove();
                    }
                });
            }

            var defaultBillingRow = this.addressListContainer.down('.default-billing-row');
            var defaultBillingAddressData = defaultBillingRow.down('input[name="address_data"]');
            if (defaultBillingAddressData) {
                defaultBillingAddressData = defaultBillingAddressData.getValue().evalJSON();
            } else {
                if (isDefaultBilling) {
                    defaultBillingRow.show();
                }
            }

            var defaultShippingRow = this.addressListContainer.down('.default-shipping-row');
            var defaultShippingAddressData = defaultShippingRow.down('input[name="address_data"]');
            if (defaultShippingAddressData) {
                defaultShippingAddressData = defaultShippingAddressData.getValue().evalJSON();
            } else {
                if (isDefaultShipping) {
                    defaultShippingRow.show();
                }
            }

            if (isDefaultBilling && !isDefaultShipping && !this.form.up('.default-billing-row')) {
                if (defaultBillingAddressData) {
                    defaultBillingRow.removeClassName('address-' + defaultBillingAddressData.id);
                    if (!defaultShippingAddressData || defaultShippingAddressData.id != defaultBillingAddressData.id || isDefaultShipping) {
                        defaultBillingAddressData.is_default_billing = false;
                        defaultBillingAddressData.is_default_shipping = false;
                        this.addNewAddressRow(defaultBillingAddressData);
                    }
                }
                defaultBillingRow.addClassName('address-' + addressData.id);
            }
            if (isDefaultShipping && !this.form.up('.default-shipping-row')) {
                if (defaultShippingAddressData) {
                    defaultShippingRow.removeClassName('address-' + defaultShippingAddressData.id);
                    if (!defaultBillingAddressData || defaultShippingAddressData.id != defaultBillingAddressData.id) {
                        defaultShippingAddressData.is_default_billing = false;
                        defaultShippingAddressData.is_default_shipping = false;
                        this.addNewAddressRow(defaultShippingAddressData);
                    }
                }
                defaultShippingRow.addClassName('address-' + addressData.id);
            }
            this.updateAddressRow(addressData);
            this.showAddressDataRow(this.form);
            this.updateHeadingOneLineContainers(addressData);
        } else if (formAddressId == addressData.id) {
            this.updateAddressRow(addressData);
            this.showAddressDataRow(this.form);
        } else {
            this.addNewAddressRow(addressData);
        }
    },

    updateHeadingOneLineContainers: function (addressData) {
        var isDefaultBilling = typeof(addressData.is_default_billing) != 'undefined' && addressData.is_default_billing;
        var isDefaultShipping = typeof(addressData.is_default_shipping) != 'undefined' && addressData.is_default_shipping;
        var oneLineHtml = this.addressOneLineTemplate.evaluate(addressData);

        if (isDefaultBilling && this.defaultBillingOneLineContainer) {
            this.defaultBillingOneLineContainer.update(oneLineHtml);
            this.defaultBillingOneLineContainer.up().show();
        }
        if (isDefaultShipping && this.defaultShippingOneLineContainer) {
            this.defaultShippingOneLineContainer.update(oneLineHtml);
            this.defaultShippingOneLineContainer.up().show();
        }
    },

    addNewAddressRow: function (addressData) {
        this.newAddressFormDisplayContainer.insert({before: this.newAddressRowFullTemplate.evaluate(addressData)});

        var container = this.newAddressFormDisplayContainer.previous(0);

        container.down(this.addressEditFormContainerDataRowSelector).insert(new Element('input', {
            'type': 'hidden',
            'name': 'address_data',
            'value': Object.toJSON(addressData)
        }));

        container.addClassName('address-' + addressData.id);

        var editButton = container.down(this.addressEditButtonsSelector);
        if (editButton) {
            this.addressEditButtons.push(editButton);
            editButton.observe('click', this.addressEditButtonClick.bind(this));
        }

        var deleteButton = container.down(this.addressDeleteButtonsSelector);
        if (deleteButton) {
            this.addressDeleteButtons.push(this.addressDeleteButtons);
            deleteButton.observe('click', this.addressDeleteButtonClick.bind(this));
        }

        if (this.defaultNoAddressesOneLineContainer) {
            this.defaultNoAddressesOneLineContainer.hide();
        }
    },

    updateAddressRow: function (addressData) {
        var addressRowHtml = this.addressRowTemplate.evaluate(addressData);
        this.addressListContainer.select('.address-' + addressData.id).each(function (el) {
            var row = el.down(this.addressEditFormContainerDataRowSelector);
            row.update(addressRowHtml);

            row.insert(new Element('input', {
                'type': 'hidden',
                'name': 'address_data',
                'value': Object.toJSON(addressData)
            }));
            var editButton = row.down(this.addressEditButtonsSelector);
            if (editButton) {
                this.addressEditButtons.push(editButton);
                editButton.observe('click', this.addressEditButtonClick.bind(this));
            }

            var deleteButton = row.down(this.addressDeleteButtonsSelector);
            if (deleteButton) {
                this.addressDeleteButtons.push(deleteButton);
                deleteButton.observe('click', this.addressDeleteButtonClick.bind(this));
            }
        }.bind(this));
    }
});
