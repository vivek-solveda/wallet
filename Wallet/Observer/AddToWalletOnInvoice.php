<?php

namespace Solveda\Wallet\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Invoice;
use Solveda\Wallet\Model\Points;
use Solveda\Wallet\Model\ResourceModel\Points as PointsResource;

class AddPointsOnInvoice implements ObserverInterface
{
    protected $pointsModel;
    protected $pointsResource;

    public function __construct(
        Points $pointsModel,
        PointsResource $pointsResource
    ) {
        $this->pointsModel = $pointsModel;
        $this->pointsResource = $pointsResource;
    }

    public function execute(Observer $observer)
    {
        /** @var Invoice $invoice */
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();
        $customerId = $order->getCustomerId();

        if (!$customerId || !$order->getGrandTotal()) {
            return;
        }

        // Calculate points (10x the order's grand total)
        $pointsToAdd = $order->getGrandTotal() * 10;

        // Load or create points entry for the customer
        $points = $this->pointsModel->load($customerId, 'customer_id');
        if (!$points->getId()) {
            $points->setCustomerId($customerId);
            $points->setPoints(0);
        }

        // Update points balance
        $currentPoints = $points->getPoints();
        $points->setPoints($currentPoints + $pointsToAdd);
        $this->pointsResource->save($points);
    }
}
