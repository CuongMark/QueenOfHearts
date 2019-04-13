/*
 * Copyright © 2016 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'Magento_Ui/js/form/provider',
    'Magento_Ui/js/modal/alert',
    'mage/translate'
], function (Component, alert, $t) {
    'use strict';

    return Component.extend({
        /**
         * Saves currently available data.
         *
         * @param {Object} [options] - Addtitional request options.
         * @returns {Provider} Chainable.
         */
        save: function (options) {
            var data = this.get('data');
            var selected = data.selected;
            if (Array.isArray(selected) && !selected.length){
                alert({
                    content: $t('Please choose tickets in the list to print.')
                });
                return this;
            } else {
                var data = this.get('data');
                // JSON serialize for data.links
                if (undefined !== data.links) {
                    data = Object.assign({}, data);
                    data.links = JSON.stringify(data.links);
                }
                this.client.save(data, options);
                return this;
            }
        },
    });
});
