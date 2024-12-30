<?php

namespace Solveda\Wallet\Controller\Points;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Solveda\Wallet\Model\WalletManager;

class Redeem extends Action
{
    protected $walletManager;
    protected $_session;

    public function __construct(
        Context $context,
        WalletManager $walletManager,
        Session $session
    ) {
        $this->walletManager = $walletManager;
        $this->_session = $session;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $customerId = $this->_session->getCustomerId();
            $this->walletManager->redeemPoints($customerId);
            $this->messageManager->addSuccessMessage(__('Points redeemed successfully.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->_redirect('wallet/index/index');
    }
}
