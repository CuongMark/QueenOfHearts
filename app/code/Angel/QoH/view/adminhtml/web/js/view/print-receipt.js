/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'ko',
    'uiComponent',
    'Angel_QoH/js/model/receipt',
    'Magento_Catalog/js/price-utils'
], function ($, ko, Component, qoh, priceUtils) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Angel_QoH/print-receipt'
        },
        ticket : qoh.ticket,
        product_name : qoh.product_name,
        priceFormat : window.checkoutConfig?window.checkoutConfig.priceFormat:{"pattern":"$%s","precision":2,"requiredPrecision":2,"decimalSymbol":".","groupSymbol":",","groupLength":3,"integerRequired":false},
        /** @inheritdoc */
        initialize: function () {
            var self = this;
            this.formatPrice = function (price) {
                return priceUtils.formatPrice(price, self.priceFormat);
            };
            this._super();
        },
    });
});
