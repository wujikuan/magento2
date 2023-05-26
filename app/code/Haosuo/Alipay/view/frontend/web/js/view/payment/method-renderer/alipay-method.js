/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* @api */
define([
    'Magento_Checkout/js/view/payment/default',
    'jquery',
    'Haosuo_Alipay/js/action/get-alipay-url',
    'ko',
    'Magento_Checkout/js/model/quote',
    'Magento_Ui/js/model/messages',
    'uiLayout'
], function (
    Component,
    $,
    getAlipayUrlAction,
    ko,
    quote,
    Messages,
    layout
) {
    'use strict';
    return Component.extend({
        redirectAfterPlaceOrder: false,
        isPlaceOrderActionAllowed: ko.observable(quote.billingAddress() != null),
        defaults: {
            template: 'Haosuo_Alipay/payment/alipay'
        },

        initChildren: function () {
            this.messageContainer = new Messages();
            this.createMessagesComponent();

            return this;
        },

        /**
         * After place order callback
         */
        afterPlaceOrder: function () {
            var self = this;
            // Override this function and logic here
            this.getAlipayUrlActionObject()
                .done(
                    function (response) {
                        window.location.href = response;
                    }
                ).always(
                function () {
                    self.isPlaceOrderActionAllowed(true);
                }
            );
        },
        getAlipayUrlActionObject: function () {
            return $.when(
                getAlipayUrlAction([], this.messageContainer)
            );
        },

        /**
         * Create child message renderer component
         *
         * @returns {Component} Chainable.
         */
        createMessagesComponent: function () {

            var messagesComponent = {
                parent: this.name,
                name: this.name + '.messages',
                displayArea: 'messages',
                component: 'Magento_Ui/js/view/messages',
                config: {
                    messageContainer: this.messageContainer
                }
            };

            layout([messagesComponent]);

            return this;
        },

    });
});
