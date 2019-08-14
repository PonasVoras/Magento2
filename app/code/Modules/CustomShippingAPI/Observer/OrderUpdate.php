<?php namespace Modules\CustomShippingAPI\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Modules\CustomShippingAPI\API\OrderDataApi;
use Modules\CustomShippingAPI\Helper;
use Modules\CustomShippingAPI\Model;
use Psr\Log\LoggerInterface;

class OrderUpdate implements ObserverInterface
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
        $this->updateOrder($orderObject);
    }

    public function updateOrder($orderObject)
    {
        $orderId = $orderObject->getId();
        $data = $orderObject->getData();
        $status = $data['status'];
        $items = $orderObject->getAllItems();

        $order = $this->orderDataModelFactory
            ->create()
            ->load($orderId, 'order_id');

        if (!empty($order)) {
            $orderData = $order->getData();
            $orderData['order_id_response'];
            $body = [
                'status' => $status,
                'items' => [
                    $this->constrainItemsArray($items)
                ]
            ];
            $updateOrderResponse = $this->orderDataApi->putRequest($orderData['order_id_response'], $body);
            $this->logger->info('Item update request has bee sent, order data API response: ' . $updateOrderResponse);

        }


    }
}
