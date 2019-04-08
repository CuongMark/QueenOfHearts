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
            template: 'Angel_QoH/prizes'
        },
        prizes : qoh.prizes,
        hasPrizes : ko.observable(false),

        /** @inheritdoc */
        initialize: function () {
            var self = this;
            self.prizes.subscribe(function () {
                self.hasPrizes(Boolean(self.prizes().length));
            });
            this._super();
        },
    });
});
