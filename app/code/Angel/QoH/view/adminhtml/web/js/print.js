/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'ko',
    'mage/mage',
    'Angel_QoH/js/model/queen-of-hearts',
    'Angel_QoH/js/model/receipt',
    'Angel_QoH/js/jquery-barcode',
], function ($, ko, mage, qoh, receipt) {
    'use strict';

    $.widget('qoh.printTicket', {
        /**
         * Uses Magento's validation widget for the form object.
         * @private
         */
        
        _create: function () {
            var self = this;
            this.tickets = self.options.tickets;
            this.product_name = self.options.product_name;

            window.printTicket = function () {
                qoh.setTicketsToPrint(self.tickets);
                self.printBarcode($('#tickets_to_print_box').html());
            };

            window.printReceipt = function () {
                var ticket = self.tickets[0];
                receipt.ticket(ticket);
                receipt.product_name(self.product_name);
                $("#ticket_barcode").barcode(
                    ticket.serial, // Value barcode (dependent on the type of barcode)
                    "ean13", // type (string)
                    {barWidth: 2}
                );
                self.printBarcode($('#print_receipt_box').html());
            };
        },

        /**
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
