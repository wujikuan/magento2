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
    'Haosuo_Wechat/js/model/get-wechat-url'
], function (quote, urlBuilder,  getUrl) {
    'use strict';

    return function (paymentData, messageContainer) {
        var serviceUrl, payload;

        payload = {
            cartId: quote.getQuoteId(),

        };
        serviceUrl = urlBuilder.createUrl('/wechat/redirect/get_url', {});
        return getUrl(serviceUrl, payload, messageContainer);
    };
});
