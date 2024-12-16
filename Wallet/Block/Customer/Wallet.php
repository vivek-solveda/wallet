<?php

namespace Solveda\Wallet\Block\Customer;

use Magento\Framework\View\Element\Template;
use Solveda\Wallet\Model\ResourceModel\Balance as BalanceResource;
use Magento\Customer\Model\SessionFactory;

class Wallet extends Template
{
    protected $balanceResource;
	protected $customerSession;

    public function __construct(
        Template\Context $context,
        BalanceResource $balanceResource,
        SessionFactory $sessionFactory,
        array $data = []
    ) {
        $this->balanceResource = $balanceResource;
        $this->_customerSession = $sessionFactory->create();
        parent::__construct($context, $data);
    }

    public function getCustomerBalance()
    {
        $customerId ='';
        $this->_customerSession->start();
        
        if ($this->_customerSession->isLoggedIn()) {
            $customerId = $this->_customerSession->getCustomer()->getId();
            //var_dump('Customer ID:', $customerId);
        }
        $balanceData = $this->balanceResource->loadByCustomerId($customerId);
        
        return $balanceData['balance'] ?? 0;
    }
}
