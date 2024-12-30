<?php

namespace Solveda\Wallet\Block\Form;

use Magento\Payment\Block\Form as PaymentForm;

class WalletPayment extends PaymentForm
{
    protected $_template = 'Solveda_Wallet::form/walletpayment.phtml';
}
