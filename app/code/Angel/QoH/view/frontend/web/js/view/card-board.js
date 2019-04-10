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
            template: 'Angel_QoH/card-board'
        },
        prizes : qoh.prizes,
        cards: ko.observable(),
        cardsImages : [],
        getImageUrl: function(cardName){
            return 'pub/static/Angel/QoH/img/cards/' + cardName;
        },

        /** @inheritdoc */
        initialize: function () {
            var self = this;
            var types = ['clubs', 'diamonds', 'hearts', 'spades'];
            var numbers = ['ace', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'jack', 'queen', 'spades'];
            types.forEach(function (type) {
               numbers.forEach(function (number) {
                   self.cardsImages.push(number + '_' + type + '.png');
               })
            });
            this.cardsImages.push('jocker_a.png');
            this.cardsImages.push('jocker_b.png');


            var cards = [];
            for (var i = 1; i<=54; i++){
                cards.push('back-card.png');
            }
            this.cards(cards);

            self.prizes.subscribe(function () {
                var prizes = self.prizes();
                var cards = [];
                for (var i = 1; i<=54; i++){
                    var card = 0;
                    prizes.forEach(function (el) {
                        if (el.card_number == i){
                            cards.push(self.cardsImages[parseInt(el.card) - 1]);
                        }
                    });
                    cards.push('back-card.png');
                }
                self.cards(cards);
            });
            this._super();
        },
    });
});
