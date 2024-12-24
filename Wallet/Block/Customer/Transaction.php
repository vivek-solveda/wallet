<?php

namespace Solveda\Wallet\Block\Customer;

use Magento\Framework\View\Element\Template;
use Solveda\Wallet\Model\TransactionHistory;

class Transaction extends Template
{
    protected $transactionHistoryModel;

    public function __construct(
        Template\Context $context,
        TransactionHistory $transactionHistoryModel,
        array $data = []
    ) {
        $this->transactionHistoryModel = $transactionHistoryModel;
        parent::__construct($context, $data);
    }

    public function getTransactionHistory()
    {
        return $this->transactionHistoryModel->getTransactionHistory();
    }
}
