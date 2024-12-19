<?php

namespace Solveda\Wallet\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Invoice;
use Solveda\Wallet\Model\ResourceModel\Balance;

class AddToWalletOnInvoice implements ObserverInterface
{
    protected $balanceResource;

    public function __construct(
        Balance $balanceResource
    ) {
        $this->balanceResource = $balanceResource;
    }

    public function execute(Observer $observer)
    {
        try {
            /** @var Invoice $invoice */
            $invoice = $observer->getEvent()->getInvoice();
            $order = $invoice->getOrder();
            $customerId = $order->getCustomerId();

            if (!$customerId || !$order->getGrandTotal()) {
                return;
            }

            $walletAmount = $order->getGrandTotal() * 0.5;

            $balanceData = $this->balanceResource->loadByCustomerId($customerId);
            $currentBalance = $balanceData['balance'] ?? 0;

            $connection = $this->balanceResource->getConnection();
            if (empty($balanceData)) {
                $connection->insert(
                    $this->balanceResource->getMainTable(),
                    [
                        'customer_id' => $customerId,
                        'balance' => $walletAmount
                    ]
                );
            } else {
                $connection->update(
                    $this->balanceResource->getMainTable(),
                    ['balance' => $currentBalance + $walletAmount],
                    ['customer_id = ?' => $customerId]
                );
            }
        } catch (\Exception $e) {
           
        }
    }
}
