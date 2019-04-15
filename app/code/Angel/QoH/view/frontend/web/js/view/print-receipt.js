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
], function ($, ko, Component, receipt, priceUtils) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Angel_QoH/print-receipt'
        },
        ticket : receipt.ticket,
        product_name : receipt.product_name,
        priceFormat : window.checkoutConfig?window.checkoutConfig.priceFormat:{"pattern":"$%s","precision":2,"requiredPrecision":2,"decimalSymbol":".","groupSymbol":",","groupLength":3,"integerRequired":false},
        /** @inheritdoc */
        initialize: function () {
            var self = this;
            this.formatPrice = function (price) {
                return priceUtils.formatPrice(price, self.priceFormat);
            };
            this.priceFormated = ko.computed(function(){
                return priceUtils.formatPrice(self.ticket().price, self.priceFormat);
            });
            this.timeFormated = ko.computed(function(){
                var date = new Date(self.ticket().created_at);
                return date.toLocaleTimeString();
            });
            this.ticketId = ko.computed(function(){
                return '#'+self.ticket().ticket_id;
            });
            this._super();
        },
    });
});
