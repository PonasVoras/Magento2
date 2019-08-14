<?php namespace Modules\CustomShippingAPI\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Modules\CustomShippingAPI\Model\ResourceModel;

class OrderData extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'custom_shipping_method_orders';

    protected $_cacheTag = 'custom_shipping_method_orders';

    protected $_eventPrefix = 'custom_shipping_method_orders';

    protected function _construct()
    {
        $this->_init(ResourceModel\OrderData::class);
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