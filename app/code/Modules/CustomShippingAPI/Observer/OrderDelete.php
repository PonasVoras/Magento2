<?php namespace Modules\CustomShippingAPI\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Modules\CustomShippingAPI\API\OrderDataApi;
use Modules\CustomShippingAPI\Helper;
use Modules\CustomShippingAPI\Model;
use Psr\Log\LoggerInterface;

class OrderDelete implements ObserverInterface
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
        $orderObject = $observer->getEvent()->getOrder();
        // TODO make a custom logger
        $this->logger->info('Order was placed');
        $this->deleteOrder($orderObject);
    }

    public function deleteOrder($orderObject)
    {
        $orderId = $orderObject->getId();

        $order = $this->orderDataModelFactory
            ->create()
            ->load($orderId, 'order_id');

        if (!empty($order)) {
            $orderData = $order->getData();

            $deleteOrderResponse = $this->orderDataApi
                ->deleteRequest($orderData['order_id_response']);
            $this->logger
                ->info(
                    'Item delete request has bee sent, order data API response: ' . $deleteOrderResponse
                );

            $order->delete();
        }


    }
}

