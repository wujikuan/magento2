/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* @api */
define([
    'CustomCheckout_Payment/js/view/payment/default',
    'Magento_Checkout/js/model/quote'
], function (Component, quote) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'CustomCheckout_Payment/payment/alipay'
        },

        /** Returns is method available */
        isAvailable: function () {
            return quote.totals()['grand_total'] <= 0;
        }
    });
});
