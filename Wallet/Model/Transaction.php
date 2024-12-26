<?php

namespace Solveda\Wallet\Model;

use Magento\Framework\Model\AbstractModel;

class Transaction extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Solveda\Wallet\Model\ResourceModel\Transaction::class);
    }
}
