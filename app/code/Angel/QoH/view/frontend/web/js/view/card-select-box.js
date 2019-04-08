/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'ko',
    'uiComponent',
    'Angel_QoH/js/model/queen-of-hearts'
], function ($, ko, Component, qoh) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Angel_QoH/card-select-box'
        },
        selectedCardNumber: ko.observable(1),
        selectedCardClass: ko.observable(1),
        prizes: qoh.prizes,
        cards: [],
        updateCardNumber: function(cardNumber){
            var drawed = true;
            this.prizes().forEach(function(el){
                if (el.card_number == cardNumber){
                    drawed = false;
                }
            });
            if (drawed) {
                this.selectedCardNumber(cardNumber);
            }
        },
        getSelectedCardClass: function(card_number){
            var css = 'card_' + card_number;
            this.prizes().forEach(function(el){
                if (el.card_number == card_number){
                    css += ' drawed card_image_' + el.card;
                }
            });
            return css;
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
