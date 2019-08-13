<?php namespace Modules\CustomShippingAPI\Model\OrderData;

use Magento\Framework\DataObject\IdentityInterface;

class OrderData implements IdentityInterface
{
    const CACHE_TAG = 'custom_shipping_method_orders';

    protected $_cacheTag = 'custom_shipping_method_orders';

    protected $_eventPrefix = 'custom_shipping_method_orders';

    protected function _construct()
    {
        $this->_init('Mageplaza\HelloWorld\Model\ResourceModel\Post');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}