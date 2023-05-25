/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/**
 * @api
 */
define(
    [
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Customer/js/customer-data',
        'Magento_Checkout/js/model/payment/place-order-hooks',
        'underscore'
    ],
    function (storage, errorProcessor, fullScreenLoader, customerData, hooks, _) {
        'use strict';

        return function (serviceUrl, payload, messageContainer) {
            var headers = {};

            fullScreenLoader.startLoader();
            _.each(hooks.requestModifiers, function (modifier) {
                modifier(headers, payload);
            });

            return storage.post(
                serviceUrl, JSON.stringify(payload), true, 'application/json', headers
            ).fail(
                function (response) {
                    errorProcessor.process(response, messageContainer);
                }
            ).done(
                function (response) {

                    if (response.responseType !== 'error') {
                        return response;
                    }
                }
            ).always(
                function () {
                    fullScreenLoader.stopLoader();
                    _.each(hooks.afterRequestListeners, function (listener) {
                        listener();
                    });
                }
            );
        };
    }
);
