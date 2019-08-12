<?php namespace Modules\CustomShippingAPI\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Modules\CustomShippingAPI\API\OrderDataApi;
use Psr\Log\LoggerInterface;

class OrderData implements ObserverInterface
{
    private $logger;
    private $orderDataApi;

    public function __construct(
        LoggerInterface $logger,
        OrderDataApi $orderDataApi
    )
    {
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
        $orderInfo = $orderObject->getData();
        $orderShippingInfo = $orderObject->getShippingAddress()->getData();
        $orderItemInfo = $orderObject->getAllItems();

        $orderData['id'] = $orderObject->getId();
        $orderData['createdAt'] = $orderInfo['created_at'];
        $orderData['customerName'] = $orderInfo['customer_firstname'];

        $orderData['address']['city'] = $orderShippingInfo['city'];
        $orderData['address']['country'] = $orderShippingInfo['country_id'];
        $orderData['address']['postCode'] = $orderShippingInfo['postcode'];
        $orderData['address']['street'] = $orderShippingInfo['street'];

        foreach ($orderItemInfo as $item) {
            $orderData['items']['name'] = $item->getName();
            $orderData['items']['price'] = $item->getPrice();
            $orderData['items']['qty'] = $item->getQtyToShip();
        }
        $orderData['shippingMethod'] = $orderInfo['shipping_method'];

        $this->logger->info('Formatted order data, ready to send to an API: '
            . json_encode($orderData));
        $this->orderDataApi
            ->createPostRequest($orderData)
            ->sendRequest();

        $this->logger->info('Response from the API: '
            . $this->orderDataApi->getResponse());
    }
}
