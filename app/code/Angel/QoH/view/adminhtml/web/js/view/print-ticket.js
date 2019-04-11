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
            template: 'Angel_QoH/print-ticket'
        },
        printTickets : qoh.printTickets,

        /** @inheritdoc */
        initialize: function () {
            var self = this;
            this._super();
        },
    });
});
