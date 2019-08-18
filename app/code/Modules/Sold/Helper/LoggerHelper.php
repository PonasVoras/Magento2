<?php

namespace Modules\Sold\Helper;

use Modules\Sold\Logger\Logger;

class LoggerHelper
{
    private $adminConfigData;
    private $logger;

    public function __construct(
        Logger $logger,
        AdminConfigDataHelper $adminConfigData
    ) {
        $this->logger = $logger;
        $this->adminConfigData = $adminConfigData;
    }

    public function logIfEnabled(string $message)
    {
        if ($this->adminConfigData->getConfigValue('logging')) {
            $this->logger->info($message);
        }
    }
}
