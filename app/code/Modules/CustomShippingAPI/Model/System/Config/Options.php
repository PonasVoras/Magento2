<?php namespace Modules\CustomShippingAPI\Model\System\Config;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value as ConfigValue;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

class Options extends ConfigValue
{
    protected $serializer;

    public function __construct(
        SerializerInterface $serializer,
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        LoggerInterface $logger,
        array $data = []
    ) {
        $this->logger = $logger;
        $this->serializer = $serializer;
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data);
    }

    public function beforeSave()
    {
        $values = $this->getValue();
        unset($values['__empty']);
        $encodedValue = $this->serializer->serialize($values);
        $this->setValue($encodedValue);
    }

    protected function _afterLoad()
    {
        $value = $this->getValue();
        if ($value) {
            $decodedValue = $this->serializer->unserialize($value);
            $this->values = $decodedValue;
            $this->setValue($decodedValue);
        }
    }
}

