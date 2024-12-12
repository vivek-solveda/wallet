<?php

namespace Solveda\Wallet\Model\ResourceModel\Balance;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(\Solveda\Wallet\Model\Balance::class, \Solveda\Wallet\Model\ResourceModel\Balance::class);
    }
}
