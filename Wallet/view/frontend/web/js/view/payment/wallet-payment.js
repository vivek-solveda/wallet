define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (Component, rendererList) {
    'use strict';

    rendererList.push({
        type: 'wallet_payment',
        component: 'Solveda_Wallet/js/view/payment/method-renderer/wallet-payment-method'
    });

    return Component.extend({});
});
