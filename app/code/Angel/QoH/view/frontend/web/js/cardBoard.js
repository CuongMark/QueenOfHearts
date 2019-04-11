/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'ko',
    'mage/mage',
    'Magento_Catalog/product/view/validation',
    'Angel_QoH/js/action/purchase-tickets',
    'Angel_QoH/js/model/tickets',
    'Angel_QoH/js/model/queen-of-hearts',
    'mage/validation'
], function ($, ko, mage, validation, purchaseAction, tickets, qoh) {
    'use strict';

    $.widget('qoh.cardBoard', {
        isLoading: ko.observable(false),
        jackPot: qoh.jackPot,
        prizes: qoh.prizes,
        options: {
            bindSubmit: false,
            radioCheckboxClosest: '.nested'
        },

        /**
         * Uses Magento's validation widget for the form object.
         * @private
         */
        _create: function () {
            var self = this;
            this.jackPot(parseFloat(self.options.jackPot));
            this.prizes(self.options.prizes);
        }
    });

    return $.qoh.cardBoard;
});
