<?php

namespace Solveda\Wallet\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Balance extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('wallet_balance', 'customer_id');
    }

    public function loadByCustomerId($customerId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('customer_id = :customer_id');
        $bind = ['customer_id' => (int)$customerId];

        return $connection->fetchRow($select, $bind);
    }
}
