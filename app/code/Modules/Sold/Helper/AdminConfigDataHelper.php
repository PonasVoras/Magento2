<?php

namespace Modules\Sold\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class AdminConfigDataHelper extends AbstractHelper
{
    const XML_PATH = 'grid/column/';

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
