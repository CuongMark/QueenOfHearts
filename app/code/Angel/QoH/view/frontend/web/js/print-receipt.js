/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'ko',
    'mage/mage',
    'Angel_QoH/js/action/receipt',
    'Angel_QoH/js/model/receipt',
    'Angel_QoH/js/jquery-barcode'
], function ($, ko, mage, ticketAction, receipt) {
    'use strict';

    $.widget('qoh.printReceipt', {
        isLoading: ko.observable(false),

        /**
         * Uses Magento's validation widget for the form object.
         * @private
         */
        _create: function () {
            var self = this;
            this.element.click(function(){
                var ticket_id = this.getAttribute('ticket_id');
                self.isLoading(true);
                ticketAction({ticket_id: ticket_id});
            });

            ticketAction.registerCallback(function (purchaseData, response) {
                if (response && response.ticket_id){
                    receipt.product_name(response.product_name);
                    receipt.ticket(response);
                    $("#ticket_barcode").barcode(
                        response.serial, // Value barcode (dependent on the type of barcode)
                        "ean13", // type (string)
                        {barWidth: 2}
                    );
                    self.printBarcode($('#print_receipt_box').html());
                }
                self.isLoading(false);
            });
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

    return $.qoh.printReceipt;
});
