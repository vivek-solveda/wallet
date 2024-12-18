var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/view/payment/default': {
                'Solveda_Wallet/js/view/payment/method-renderer/wallet': true
            }
        }
    },
    map: {
        '*': {
            walletPayment: 'Solveda_Wallet/js/view/payment/method-renderer/wallet'
        }
    }
};
