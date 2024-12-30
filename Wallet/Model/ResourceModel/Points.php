<?php

namespace Solveda\Wallet\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Points extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('wallet_points', 'points_id');
    }

    /**
     * Load points by customer ID
     *
     * @param int $customerId
     * @return array
     */
    public function loadByCustomerId($customerId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('customer_id = ?', $customerId);

        return $connection->fetchRow($select) ?: [];
    }
}
