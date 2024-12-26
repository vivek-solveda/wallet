<?php

namespace Solveda\Wallet\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Transaction extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('wallet_transaction', 'transaction_id');
    }
}
