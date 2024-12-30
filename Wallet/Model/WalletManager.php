<?php

namespace Solveda\Wallet\Model;

use Solveda\Wallet\Model\ResourceModel\Balance as BalanceResource;
use Solveda\Wallet\Model\BalanceFactory;
use Solveda\Wallet\Model\ResourceModel\Transaction as TransactionResource;
use Solveda\Wallet\Model\TransactionFactory;
use Solveda\Wallet\Model\ResourceModel\Points as PointsResource;
use Solveda\Wallet\Model\Points;

class WalletManager
{
    const POINTS_TO_DOLLAR_RATIO = 2;

    protected $balanceFactory;
    protected $balanceResource;
    protected $transactionFactory;
    protected $transactionResource;
    protected $pointsModel;
    protected $pointsResource;

    public function __construct(
        BalanceFactory $balanceFactory,
        BalanceResource $balanceResource,
        TransactionFactory $transactionFactory,
        TransactionResource $transactionResource,
        Points $pointsModel,
        PointsResource $pointsResource

    ) {
        $this->balanceFactory = $balanceFactory;
        $this->balanceResource = $balanceResource;
        $this->transactionFactory = $transactionFactory;
        $this->transactionResource = $transactionResource;
        $this->pointsModel = $pointsModel;
        $this->pointsResource = $pointsResource;

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

    public function redeemPoints($customerId)
    {
        // Load points and wallet balance
        $points = $this->pointsModel->load($customerId, 'customer_id');
        if (!$points->getId() || $points->getPoints() < self::POINTS_TO_DOLLAR_RATIO) {
            throw new \Exception("Not enough points to redeem.");
        }

        $redeemablePoints = floor($points->getPoints() / self::POINTS_TO_DOLLAR_RATIO);
        $walletAmount = $redeemablePoints;

        // Update points balance
        $remainingPoints = $points->getPoints() - ($redeemablePoints * self::POINTS_TO_DOLLAR_RATIO);
        $points->setPoints($remainingPoints);
        $this->pointsResource->save($points);

        // Update wallet balance
        $balance = $this->balanceFactory->create();
        $this->balanceResource->load($balance, $customerId, 'customer_id');
        if (!$balance->getId()) {
            $balance->setCustomerId($customerId);
            $balance->setBalance(0);
        }

        $currentBalance = $balance->getBalance();
        $balance->setBalance($currentBalance + $walletAmount);
        $this->balanceResource->save($balance);

        // Log the transaction
        $transaction = $this->transactionFactory->create();
        $transaction->setData([
            'customer_id' => $customerId,
            'amount' => $walletAmount,
            'transaction_type' => 'Redeem',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $this->transactionResource->save($transaction);
    }

}
