<?php

namespace Solveda\Wallet\Model;

use Solveda\Wallet\Model\ResourceModel\Balance as BalanceResource;
use Solveda\Wallet\Model\BalanceFactory;
use Solveda\Wallet\Model\ResourceModel\Transaction as TransactionResource;
use Solveda\Wallet\Model\TransactionFactory;

class WalletManager
{
    protected $balanceFactory;
    protected $balanceResource;
    protected $transactionFactory;
    protected $transactionResource;

    public function __construct(
        BalanceFactory $balanceFactory,
        BalanceResource $balanceResource,
        TransactionFactory $transactionFactory,
        TransactionResource $transactionResource
    ) {
        $this->balanceFactory = $balanceFactory;
        $this->balanceResource = $balanceResource;
        $this->transactionFactory = $transactionFactory;
        $this->transactionResource = $transactionResource;
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

        // Log transaction
        $transaction = $this->transactionFactory->create();
        $transaction->setData([
            'customer_id' => $customerId,
            'amount' => $amount,
            'transaction_type' => 'Credit',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $this->transactionResource->save($transaction);
    }

    public function deductWalletBalance($customerId, $amount)
    {
        $balance = $this->balanceFactory->create();
        $this->balanceResource->load($balance, $customerId, 'customer_id');

        if ($balance->getId() && $balance->getBalance() >= $amount) {
            $newBalance = $balance->getBalance() - $amount;
            $balance->setBalance($newBalance);
            $this->balanceResource->save($balance);

            // Log transaction
            $transaction = $this->transactionFactory->create();
            $transaction->setData([
                'customer_id' => $customerId,
                'amount' => -$amount,
                'transaction_type' => 'Debit',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $this->transactionResource->save($transaction);

        } else {
            throw new \Exception(__('Insufficient wallet balance.'));
        }
    }

}
