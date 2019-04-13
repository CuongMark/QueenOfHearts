/*
 * Copyright © 2016 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'underscore',
    'Magento_Ui/js/form/form',
    'Angel_QoH/js/model/queen-of-hearts',
    'uiRegistry'
], function ($, _, Form, qoh, registry) {
    'use strict';

    return Form.extend({
        defaults: {
            listens: {
                responseData: 'processResponseData'
            }
        },
        printTickets : qoh.printTickets,
        /**
         * Validate and save form.
         *
         * @param {String} redirect
         * @param {Object} data
         */
        save: function (redirect, data) {
            this.validate();
            if (!this.additionalInvalid && !this.source.get('params.invalid')) {
                if(!data){
                    data = {};
                }
                var self = this;
                _.forEach(this.elems(), function(el){
                    if(el.index == 'ticket_print_listing'){
                        data['namespace'] = el.ns;
                        registry.async(el.ns+"."+el.ns+".angel_qoh_ticket_columns.ids")(function(idsColumn){
                            if(idsColumn){
                                var selection = idsColumn.getSelections();
                                if(selection.excludeMode == true){
                                    data['excluded'] = (selection.excluded.length == 0)?false:selection.excluded;
                                    data['selected'] = null;
                                }else{
                                    data['excluded'] = null;
                                    data['selected'] = selection.selected;
                                }
                                if(selection.params && selection.params.filters){
                                    data['filters'] = selection.params.filters;
                                    if(typeof data['filters']['placeholder'] != 'undefined'){
                                        delete data['filters']['placeholder'];
                                    }
                                }
                                self.setAdditionalData(data)
                                    .submit(redirect);
                            }
                        });
                    }
                });
            }
        },
        /**
         * Process response data
         *
         * @param {Object} data
         */
        processResponseData: function (response) {
            var self = this;
            if(response){
                qoh.setTicketsToPrint(response);
                self.printBarcode($('#tickets_to_print_box').html());
            }
            if(response.error && response.messages){
                Alert('Error',response.messages);
            }
        },

        /**
         *
         * @param html
         */
        printBarcode: function(html){
            var print_window = window.open('', 'print_window', 'status=1,width=500,height=500');
            if(print_window){
                print_window.document.open();
                print_window.document.write(html);
                print_window.document.close();
                print_window.addEventListener('load',function(){
                    print_window.print();
                });
            }else{
                Alert('Message','Your browser has blocked the automatic popup, please change your browser settings or print the receipt manually');
            }
        }
    });
});
