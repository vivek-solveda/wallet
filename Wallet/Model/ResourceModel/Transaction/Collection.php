<?php

namespace Solveda\Wallet\Model\ResourceModel\Transaction;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(\Solveda\Wallet\Model\Transaction::class, \Solveda\Wallet\Model\ResourceModel\Transaction::class);
    }
}
