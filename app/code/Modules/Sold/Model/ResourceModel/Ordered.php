<?php

namespace Modules\Sold\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Ordered extends AbstractDb
{
    const TABLE = 'catalog_product_entity';
    const ID_FIELD = 'entity_id';

    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init(self::TABLE, self::ID_FIELD);
    }
}
