<?php namespace Modules\CustomShippingAPI\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Modules\CustomShippingAPI\API\OrderDataApi;
use Modules\CustomShippingAPI\Helper;
use Modules\CustomShippingAPI\Model;
use Psr\Log\LoggerInterface;

class OrderData implements ObserverInterface
{
    private $logger;
    private $orderDataApi;
    private $orderDataFactory;
    private $orderDataModelFactory;

    public function __construct(
        LoggerInterface $logger,
        OrderDataApi $orderDataApi,
        Helper\OrderDataFactory $orderDataFactory,
        Model\OrderDataFactory $orderDataModelFactory
    ) {
        $this->orderDataModelFactory = $orderDataModelFactory;
        $this->orderDataFactory = $orderDataFactory;
        $this->orderDataApi = $orderDataApi;
        $this->logger = $logger;
        $this->logger->info('I will observe');
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $this->logger->info('Order was placed');
        $this->saveOrder($order);
    }

    public function saveOrder($orderObject)
    {
        $orderData = $this->orderDataFactory->create($orderObject);
        $this->logger->info('Formatted order data, ready to send to an API: '
            . json_encode($orderData));
        $responseApi = $this->orderDataApi->postRequest($orderData);

        $this->logger->info('API response' . $responseApi);

        $saveOrderToDb = $this->orderDataModelFactory->create();
        $this->logger->info(json_encode($saveOrderToDb));
        $saveOrderToDb->addData([
            'order_id' => 1,
            'order_id_response' => 6
        ]);
        $this->logger->info(json_encode($saveOrderToDb));
        $this->logger->info('Done something');
        $saveOrderToDb->save();
    }
}
