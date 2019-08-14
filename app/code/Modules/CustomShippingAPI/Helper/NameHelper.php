<?php namespace Modules\CustomShippingAPI\Helper;

use Psr\Log\LoggerInterface;

class NameHelper
{
    private $logger;
    private $configData;
    private $normalizerData = '';

    public function __construct(
        LoggerInterface $logger,
        Data $configData
    ) {
        $this->logger = $logger;
        $this->configData = $configData;
    }

    public function getNormalizerData()
    {
        $this->normalizerData = $this->configData->getConfigValue('fields');
        $this->normalizerData = json_decode($this->normalizerData, true);
    }

    public function normalizeName(string $rawName)
    {
        $this->getNormalizerData();
        $result = "Not found";
        foreach ($this->normalizerData as $value) {
            $value['primary'] == $rawName ?
                $result = $value['result'] : null;
        }
        return $result;
    }
}