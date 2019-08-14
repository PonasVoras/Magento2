<?php namespace Modules\CustomShippingAPI\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Modules\CustomShippingAPI\API\OrderDataApi;
use Modules\CustomShippingAPI\Helper;
use Modules\CustomShippingAPI\Model;
use Psr\Log\LoggerInterface;

class OrderCreate implements ObserverInterface
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
    )
    {
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
        $orderDataResponse = $this->orderDataApi->postRequest($orderData);

        // TODO if enabled LOGGER
        $this->logger->info('Formatted order data, ready to send to an API: '
            . json_encode($orderData));

        $this->logger->info('API response' . $orderDataResponse);

        $orderDataResponse = json_decode($orderDataResponse, true);

        $saveOrderToDb = $this->orderDataModelFactory->create();
//        $saveOrderToDb->addData([
//            'order_id' => $orderData['id'],
//            'order_id_response' => $orderDataResponse['id']
//        ]);
        $saveOrderToDb->addData([
            'order_id' => $orderData['id'],
            'order_id_response' => 9
        ]);

        // TODO if enabled LOGGER
        $this->logger->info(
            'Successfully saved order data response from API in the database'
        );

        $saveOrderToDb->save();
    }
}
