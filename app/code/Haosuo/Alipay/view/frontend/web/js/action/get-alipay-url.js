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
    'Haosuo_Alipay/js/model/get-alipay-url'
], function (quote, urlBuilder,  alipayUrl) {
    'use strict';

    return function (paymentData, messageContainer) {
        var serviceUrl, payload;

        payload = {
            cartId: quote.getQuoteId(),

        };
        serviceUrl = urlBuilder.createUrl('/alipay/redirect/get_url', {});

        return alipayUrl(serviceUrl, payload, messageContainer);
    };
});
