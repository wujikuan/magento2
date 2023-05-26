/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* @api */
define([
    'Magento_Checkout/js/view/payment/default',
    'jquery',
    'Haosuo_Wechat/js/action/get-wechat-url',
    'ko',
    'Magento_Checkout/js/model/quote',
    'Magento_Ui/js/model/messages',
    'uiLayout',
    'Magento_Ui/js/modal/modal',
    'mage/url',
    'Haosuo_Wechat/js/action/get-wechat-order-status'
], function (
    Component,
    $,
    getWechatUrlAction,
    ko,
    quote,
    Messages,
    layout,
    modal,
    urlBuilder,
    getWechatOrderStatusAction
) {
    'use strict';
    return Component.extend({
        redirectAfterPlaceOrder: false,
        isPlaceOrderActionAllowed: ko.observable(quote.billingAddress() != null),
        defaults: {
            template: 'Haosuo_Wechat/payment/wechat'
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
            this.getWechatUrlActionObject()
                .done(
                    function (response) {
                        var exampleModal = $('#example-modal');
                        exampleModal.modal({
                            title: 'Wechat Payment',
                            buttons: []
                        }).modal('openModal');

                        $('.payment-qrCode-content').html('<img src="'+response+'" >');

                        $('button[class="action-close"]').click(function (){
                            exampleModal.modal('closeModal');
                            var currentUrl = urlBuilder.build('sales/order/history');
                            window.location.href = currentUrl;
                        })

                        self.getOrderPayStatus();
                    }
                ).always(
                function () {
                    self.isPlaceOrderActionAllowed(true);
                }
            );
        },

        getWechatUrlActionObject: function () {
            return $.when(
                getWechatUrlAction([], this.messageContainer)
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
        getOrderPayStatus:function (){
            var self = this;
            $.when(
                getWechatOrderStatusAction([], this.messageContainer)
            ).done(
                function (response) {
                    // paySuccess
                    if (response == true){
                        $('#example-modal').modal('closeModal');
                        var currentUrl = urlBuilder.build('sales/order/history');
                        window.location.href = currentUrl;
                    }else{
                        setTimeout(function() {
                           self.getOrderPayStatus();
                        }, 2000); // 每 5 秒钟查询一次订单状态
                    }
                }
            ).always(
                function () {
                    self.isPlaceOrderActionAllowed(true);
                }
            );
        }

    });
});
