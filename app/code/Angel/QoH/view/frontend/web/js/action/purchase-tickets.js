/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/storage',
    'Magento_Ui/js/model/messageList',
    'mage/translate'
], function ($, storage, globalMessageList, $t) {
    'use strict';

    var callbacks = [],

        /**
         * @param {Object}  purchaseData
         * @param {String} redirectUrl
         * @param {*} isGlobal
         * @param {Object} messageContainer
         */
        action = function ( purchaseData, redirectUrl, isGlobal, messageContainer) {
            messageContainer = messageContainer || globalMessageList;

            return storage.post(
                'rest/V1/angel-qoh/purchase',
                JSON.stringify(purchaseData),
                isGlobal
            ).done(function (response) {
                if (response.errors) {
                    messageContainer.addErrorMessage(response);
                    callbacks.forEach(function (callback) {
                        callback(purchaseData);
                    });
                } else {
                    callbacks.forEach(function (callback) {
                        callback(purchaseData, response);
                    });
                }
                messageContainer.addErrorMessage({
                    'message': $t('Could not purchase tickets. Please try again later')
                });
            }).fail(function () {
                messageContainer.addErrorMessage({
                    'message': $t('Could not purchase tickets. Please try again later')
                });
                callbacks.forEach(function (callback) {
                    callback(purchaseData);
                });
            });
        };

    /**
     * @param {Function} callback
     */
    action.registerPurchaseCallback = function (callback) {
        callbacks.push(callback);
    };

    return action;
});
