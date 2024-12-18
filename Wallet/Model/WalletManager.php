<?php

namespace Solveda\Wallet\Model;

use Solveda\Wallet\Model\ResourceModel\Balance as BalanceResource;
use Solveda\Wallet\Model\BalanceFactory;

class WalletManager
{
    protected $balanceFactory;
    protected $balanceResource;

    public function __construct(
        BalanceFactory $balanceFactory,
        BalanceResource $balanceResource
    ) {
        $this->balanceFactory = $balanceFactory;
        $this->balanceResource = $balanceResource;
    }

    public function addMoneyToWallet($customerId, $amount)
    {
        // Load or create wallet balance
        $balance = $this->balanceFactory->create();
        $this->balanceResource->load($balance, $customerId, 'customer_id');

        if (!$balance->getId()) {
            $balance->setCustomerId($customerId);
            $balance->setBalance(0);
        }

        $currentBalance = (float) $balance->getBalance();
        $newBalance = $currentBalance + $amount;

        $balance->setBalance($newBalance);
        $this->balanceResource->save($balance);
    }

    public function deductWalletBalance($customerId, $amount)
    {
        $balance = $this->balanceFactory->create();
        $this->balanceResource->load($balance, $customerId, 'customer_id');

        if ($balance->getId() && $balance->getBalance() >= $amount) {
            $newBalance = $balance->getBalance() - $amount;
            $balance->setBalance($newBalance);
            $this->balanceResource->save($balance);
        } else {
            throw new \Exception(__('Insufficient wallet balance.'));
        }
    }

}
