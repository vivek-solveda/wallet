<?php

namespace Solveda\Wallet\Block\Customer;

use Magento\Framework\View\Element\Template;

class AddMoney extends Template
{
    public function getAddMoneyActionUrl()
    {
        return $this->getUrl('wallet/addmoney/process');
    }
}
