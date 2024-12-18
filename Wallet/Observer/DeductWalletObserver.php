<?php

namespace Solveda\Wallet\Observer;

use Magento\Framework\Event\ObserverInterface;
use Solveda\Wallet\Model\WalletManager;

class DeductWalletObserver implements ObserverInterface
{
    protected $walletManager;

    public function __construct(WalletManager $walletManager)
    {
        $this->walletManager = $walletManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $payment = $order->getPayment();

        if ($payment->getMethod() == 'wallet_payment') {
            $customerId = $order->getCustomerId();
            $grandTotal = $order->getGrandTotal();

            // Deduct wallet balance
            $this->walletManager->deductWalletBalance($customerId, $grandTotal);
        }
    }
}
