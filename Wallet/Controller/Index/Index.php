<?php

namespace Solveda\Wallet\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Solveda\Wallet\Helper\Data ;
use Magento\Framework\UrlInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

class Index extends Action
{
    protected $resultPageFactory;
    
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    private $resultRedirect;

    public function __construct(
        Context $context, 
        PageFactory $resultPageFactory,
        Data $helper,
        RedirectFactory $resultRedirect,
        UrlInterface $urlInterface
    ){
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->helper = $helper;
        $this->resultRedirect = $resultRedirect;
        $this->urlInterface = $urlInterface;
    }

    public function execute()
    {
        $isCustomerLogin = $this->helper->customerIsLoggedIn();
        if (!$isCustomerLogin) {
            $loginUrl = $this->urlInterface->getUrl('customer/account/login');
            return $this->resultRedirectFactory->create()->setUrl($loginUrl);
        }
        return $this->resultPageFactory->create();
    }
}
