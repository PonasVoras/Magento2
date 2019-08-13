<?php namespace Modules\CustomShippingAPI\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class OrderData extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'custom_shipping_method_orders';

    protected $_cacheTag = 'custom_shipping_method_orders';

    protected $_eventPrefix = 'custom_shipping_method_orders';

    protected function _construct()
    {
        $this->_init('Modules\CustomShippingAPI\Model\ResourceModel\OrderData');
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