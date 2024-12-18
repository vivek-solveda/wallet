<?php

namespace Solveda\Wallet\Model\Payment;

use Magento\Payment\Model\Method\AbstractMethod;
use Solveda\Wallet\Model\ResourceModel\Balance as BalanceResource;
use Solveda\Wallet\Model\BalanceFactory;
use Magento\Customer\Model\Session as CustomerSession;

class Wallet extends AbstractMethod
{
    protected $_code = 'wallet_payment';
    protected $_isOffline = true;

    private $balanceResource;
    private $balanceFactory;
    private $customerSession;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        BalanceResource $balanceResource,
        BalanceFactory $balanceFactory,
        CustomerSession $customerSession,
        array $data = []
    ) {
        parent::__construct(
            $extensionFactory,
            $paymentData,
            $scopeConfig,
            $logger
        );

        $this->balanceResource = $balanceResource;
        $this->balanceFactory = $balanceFactory;
        $this->customerSession = $customerSession;
    }

    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        if (!$quote || !$this->customerSession->isLoggedIn()) {
            return false;
        }

        $customerId = $this->customerSession->getCustomerId();
        $walletBalance = $this->getCustomerWalletBalance($customerId);

        return $walletBalance >= $quote->getGrandTotal();
    }

    private function getCustomerWalletBalance(int $customerId): float
    {
        $balance = $this->balanceFactory->create();
        $this->balanceResource->load($balance, $customerId, 'customer_id');
        return $balance->getBalance() ?: 0.00;
    }
}
