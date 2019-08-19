<?php

namespace Modules\Sold\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Modules\Sold\Helper\AdminConfigDataHelper;
use Modules\Sold\Helper\LoggerHelper;
use Modules\Sold\Helper\ModelHelper;

class OrderedIncrement implements ObserverInterface
{
    private $logger;
    private $databaseModel;
    private $adminConfigData;

    public function __construct(
        LoggerHelper $logger,
        ModelHelper $databaseModel,
        AdminConfigDataHelper $adminConfigData
    ) {
        $this->logger = $logger;
        $this->databaseModel = $databaseModel;
        $this->adminConfigData = $adminConfigData;
    }

    public function execute(Observer $observer)
    {
        $orderObject = $observer->getEvent()->getOrder();
        $this->handleOrderData($orderObject);
    }

    private function handleOrderData($orderObject)
    {
        $allItems = $orderObject->getAllItems();
        $this->logger->logIfEnabled('Observer noticed activity');

        foreach ($allItems as $item) {
            $sku = $item->getSku();
            $this->databaseModel->handleOrderedItem($sku);
        }
    }
}
