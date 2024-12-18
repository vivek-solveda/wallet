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

    /**
     * @var BalanceResource
     */
    private $balanceResource;

    /**
     * @var BalanceFactory
     */
    private $balanceFactory;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param BalanceResource $balanceResource
     * @param BalanceFactory $balanceFactory
     * @param CustomerSession $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        BalanceResource $balanceResource,
        BalanceFactory $balanceFactory,
        CustomerSession $customerSession,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData, // Matching type
            $scopeConfig,
            $logger
        );

        $this->balanceResource = $balanceResource;
        $this->balanceFactory = $balanceFactory;
        $this->customerSession = $customerSession;
    }

    /**
     * Check if Wallet Payment is available.
     *
     * @param \Magento\Quote\Api\Data\CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        if (!$quote || !$this->customerSession->isLoggedIn()) {
            return false;
        }

        $customerId = $this->customerSession->getCustomerId();
        $walletBalance = $this->getCustomerWalletBalance($customerId);

        return $walletBalance >= $quote->getGrandTotal();
    }

    /**
     * Get Customer Wallet Balance.
     *
     * @param int $customerId
     * @return float
     */
    private function getCustomerWalletBalance(int $customerId): float
    {
        $balance = $this->balanceFactory->create();
        $this->balanceResource->load($balance, $customerId, 'customer_id');
        return $balance->getBalance() ?: 0.00;
    }
}
