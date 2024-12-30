<?php

namespace Solveda\Wallet\Model;

use Magento\Framework\Model\AbstractModel;

class Points extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Solveda\Wallet\Model\ResourceModel\Points::class);
    }
}
