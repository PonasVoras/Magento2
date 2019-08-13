<?php namespace Modules\CustomShippingAPI\Helper;

class OrderDataFactory
{
    public function create($orderObject)
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
            $orderData['items'] = [
                'name' => $item->getName(),
                'price' => $item->getPrice(),
                'qty' => $item->getQtyOrdered()
            ];
        }
        $orderData['shippingMethod'] = $orderInfo['shipping_method'];
        return $orderData;
    }
}
