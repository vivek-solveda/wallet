<?php

namespace Solveda\Wallet\Block\Customer;

use Magento\Framework\View\Element\Template;
use Solveda\Wallet\Model\ResourceModel\Balance as BalanceResource;
use Solveda\Wallet\Model\ResourceModel\Points as PointsResource;
use Magento\Customer\Model\SessionFactory;


class Wallet extends Template
{
    protected $balanceResource;
    protected $pointsResource;
	protected $customerSession;

    public function __construct(
        Template\Context $context,
        BalanceResource $balanceResource,
        PointsResource $pointsResource,
        SessionFactory $sessionFactory,
        array $data = []
    ) {
        $this->balanceResource = $balanceResource;
        $this->pointsResource = $pointsResource;
        $this->_customerSession = $sessionFactory->create();
        parent::__construct($context, $data);
    }

    public function getCustomerBalance()
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return 0;
        }

        $balanceData = $this->balanceResource->loadByCustomerId($customerId);
        return $balanceData['balance'] ?? 0;
    }

    public function getCustomerPoints()
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return 0;
        }

        $pointsData = $this->pointsResource->loadByCustomerId($customerId);
        return $pointsData['points'] ?? 0;
    }

    private function getCustomerId()
    {
        $this->customerSession->start();
        if ($this->customerSession->isLoggedIn()) {
            return $this->customerSession->getCustomer()->getId();
        }
        return null;
    }
}
