define([
    'Magento_Checkout/js/view/payment/default'
], function (Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Solveda_Wallet/payment/walletpayment'
        },
        getInstructions: function () {
            return window.checkoutConfig.payment.instructions[this.item.method];
        }
    });
});
