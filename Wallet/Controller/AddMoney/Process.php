<?php

namespace Solveda\Wallet\Controller\AddMoney;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Message\ManagerInterface;
use Solveda\Wallet\Model\WalletManager;

class Process extends Action
{
    protected $walletManager;
    protected $messageManager;

    public function __construct(
        Context $context,
        WalletManager $walletManager,
        ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->walletManager = $walletManager;
        $this->messageManager = $messageManager;
    }

    public function execute()
    {
        $walletAmount = $this->getRequest()->getParam('wallet_amount', 0);
        $customerId = $this->_objectManager->get(\Magento\Customer\Model\Session::class)->getCustomerId();

        if ($customerId && $walletAmount > 0) {
            try {
                $this->walletManager->addMoneyToWallet($customerId, $walletAmount);
                $this->messageManager->addSuccessMessage(__('Amount added successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error: ' . $e->getMessage()));
            }
        } else {
            $this->messageManager->addErrorMessage(__('Invalid amount or customer.'));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('wallet/index/index');
    }
}
