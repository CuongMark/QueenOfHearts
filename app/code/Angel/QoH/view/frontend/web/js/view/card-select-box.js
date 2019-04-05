/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'ko',
    'uiComponent'
], function ($, ko, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Angel_QoH/card-select-box'
        },
        selectedCardNumber: ko.observable(1),
        selectedCardClass: ko.observable(1),
        cards: [],
        updateCardNumber: function(cardNumber){
            this.selectedCardNumber(cardNumber);
        },

        /** @inheritdoc */
        initialize: function () {
            var self = this;
            for (var i = 1; i<=54; i++){
                this.cards.push({card_number:i});
            }
            self.selectedCardNumber.subscribe(function () {
                self.selectedCardClass('selected_'+self.selectedCardNumber());
            });
            this._super();
        },
    });
});
