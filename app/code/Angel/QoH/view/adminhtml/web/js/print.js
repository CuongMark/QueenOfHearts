/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'ko',
    'mage/mage',
    'Angel_QoH/js/model/queen-of-hearts'
], function ($, ko, mage, qoh) {
    'use strict';

    $.widget('qoh.printTicket', {
        /**
         * Uses Magento's validation widget for the form object.
         * @private
         */
        
        _create: function () {
            var self = this;
            this.tickets = self.options.tickets;

            window.printTicket = function () {
                qoh.setTicketsToPrint(self.tickets);
                self.printBarcode($('#tickets_to_print_box').html());
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

    return $.qoh.printTicket;
});
