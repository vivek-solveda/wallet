<?php

namespace Solveda\Wallet\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Invoice;
use Solveda\Wallet\Model\Balance;
use Solveda\Wallet\Model\TransactionFactory;
use Solveda\Wallet\Model\ResourceModel\Balance as BalanceResource;
use Solveda\Wallet\Model\ResourceModel\Transaction as TransactionResource;

class AddToWalletOnInvoice implements ObserverInterface
{
    protected $balanceModel;
    protected $balanceResource;
    protected $transactionFactory;
    protected $transactionResource;

    public function __construct(
        Balance $balanceModel,
        BalanceResource $balanceResource,
        TransactionFactory $transactionFactory,
        TransactionResource $transactionResource
    ) {
        $this->balanceModel = $balanceModel;
        $this->balanceResource = $balanceResource;
        $this->transactionFactory = $transactionFactory;
        $this->transactionResource = $transactionResource;
    }

    public function execute(Observer $observer)
    {
        /** @var Invoice $invoice */
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();
        $customerId = $order->getCustomerId();

        if (!$customerId || !$order->getGrandTotal()) {
            return;
        }

        // Calculate 50% of the order's grand total
        $walletAmount = $order->getGrandTotal() * 0.5;

        $balance = $this->balanceModel->load($customerId, 'customer_id');
        if (!$balance->getId()) {
            $balance->setCustomerId($customerId);
            $balance->setBalance(0);
        }

        // Update the wallet balance
        $currentBalance = $balance->getBalance();
        $balance->setBalance($currentBalance + $walletAmount);
        $this->balanceResource->save($balance);

        // Log transaction
        $transaction = $this->transactionFactory->create();
        $transaction->setData([
            'customer_id' => $customerId,
            'amount' => $walletAmount,
            'transaction_type' => 'Credit',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $this->transactionResource->save($transaction);
    }
}
