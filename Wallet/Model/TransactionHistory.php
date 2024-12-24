<?php

namespace Solveda\Wallet\Model;

use Solveda\Wallet\Model\ResourceModel\Transaction\CollectionFactory as TransactionCollectionFactory;
use Magento\Customer\Model\Session;

class TransactionHistory
{
    protected $transactionCollectionFactory;
    protected $customerSession;

    public function __construct(
        TransactionCollectionFactory $transactionCollectionFactory,
        Session $customerSession
    ) {
        $this->transactionCollectionFactory = $transactionCollectionFactory;
        $this->customerSession = $customerSession;
    }

    public function getTransactionHistory()
    {
        $customerId = $this->customerSession->getCustomer();
        var_dump($customerId);
        if (!$customerId) {
            return [];
        }

        $transactionCollection = $this->transactionCollectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId)
            ->setOrder('created_at', 'DESC');

        return $transactionCollection->getItems();
    }
}
