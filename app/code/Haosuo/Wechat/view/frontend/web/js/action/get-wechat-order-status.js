/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/url-builder',
    'Haosuo_Wechat/js/model/get-wechat-order-status'
], function (quote, urlBuilder,  getOrderStatus) {
    'use strict';

    return function (paymentData, messageContainer) {
        var serviceUrl, payload;

        payload = {
            cartId: quote.getQuoteId(),
        };
        serviceUrl = urlBuilder.createUrl('/wechat/redirect/get_order_status', {});
        return getOrderStatus(serviceUrl, payload, messageContainer);
    };
});
