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
        'underscore'
    ],
    function (storage, errorProcessor,  _) {
        'use strict';

        return function (serviceUrl, payload, messageContainer) {
            var headers = {};

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
            );
        };
    }
);
