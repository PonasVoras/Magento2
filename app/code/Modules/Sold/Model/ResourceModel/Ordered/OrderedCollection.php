<?php

namespace Modules\Sold\Model\ResourceModel\Ordered;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Modules\Sold\Model;
use Modules\Sold\Model\ResourceModel;

class OrderedCollection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'modules_sold_ordered_collection';
    protected $_eventObject = 'ordered_collection';

    protected function _construct()
    {
        $this->_init(Model\OrderedModel::class, ResourceModel\OrderedResourceModel::class);
    }

}