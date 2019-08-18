<?php

namespace Modules\Sold\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Modules\Sold\Logger\Logger;
use Modules\Sold\Model;
use Psr\Log\LoggerInterface;

class OrderedIncrement implements ObserverInterface
{
    private $logger;
    private $orderedFactory;
    private $oemLogger;

    public function __construct(
        LoggerInterface $oemLogger,
        Logger $logger,
        Model\OrderedFactory $orderedFactory
    ) {
        $this->orderedFactory = $orderedFactory;
        $this->logger = $logger;
        $this->oemLogger = $oemLogger;
    }

    public function execute(Observer $observer)
    {
        $orderObject = $observer->getEvent()->getOrder();
        $this->oemLogger->info('Order was placed');
        $this->oemLogger->info($orderObject->getId());
        $this->logger->info('I am alive');
    }
}
