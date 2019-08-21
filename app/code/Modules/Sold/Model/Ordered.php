<?php

namespace Modules\Sold\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Modules\Sold\Model\ResourceModel;

class Ordered extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'modules_sold_ordered';

    protected $_cacheTag = 'modules_sold_ordered';

    protected $_eventPrefix = 'modules_sold_ordered';

    protected function _construct()
    {
        $this->_init(ResourceModel\OrderedResourceModel::class);
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
