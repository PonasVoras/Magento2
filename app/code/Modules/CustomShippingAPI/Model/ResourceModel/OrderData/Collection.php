<?php namespace Modules\CustomShippingAPI\Model\ResourceModel\OrderData;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'custom_shipping_method_orders_collection';
    protected $_eventObject = 'custom_shipping_collection';


    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Modules\CustomShippingAPI\Model\OrderData', 'Modules\CustomShippingAPI\Model\ResourceModel\OrderData');
    }
}
