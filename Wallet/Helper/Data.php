<?php

namespace Solveda\Wallet\Helper;

use Magento\Customer\Model\Session ;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	protected $customerSession;

    public function __construct(
        Session $customerSession,
    ) {
        $this->_customerSession = $customerSession;
    }

    public function customerIsLoggedIn()
    {
		return $this->_customerSession->isLoggedIn();
    }

}