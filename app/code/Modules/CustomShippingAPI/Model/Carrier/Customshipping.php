<?php namespace Modules\CustomShippingAPI\Model\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Modules\CustomShippingAPI\API\ShippingMethodDataApi;
use Modules\CustomShippingAPI\Helper\CurrencyConverter;
use Modules\CustomShippingAPI\Helper\Data;
use Modules\CustomShippingAPI\Helper\NameHelper;
use Psr\Log\LoggerInterface;

/**
 * Custom shipping model
 */
class Customshipping extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'customshipping';
    /**
     * @var bool
     */
    protected $_isFixed = true;
    /**
     * @var ResultFactory
     */
    private $rateResultFactory;
    /**
     * @var MethodFactory
     */
    private $rateMethodFactory;
    /**
     * @var ShippingMethodDataApi
     */
    private $shippingMethodDataApi;

    /**
     * @var CurrencyConverter
     */
    private $currencyConverter;

    private $logger;

    private $nameHelper;

    private $configData;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        ShippingMethodDataApi $shippingMethodDataApi,
        CurrencyConverter $currencyConverter,
        NameHelper $nameHelper,
        Data $configData

    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger);
        $this->configData = $configData;
        $this->currencyConverter = $currencyConverter;
        $this->shippingMethodDataApi = $shippingMethodDataApi;
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->logger = $logger;
        $this->nameHelper = $nameHelper;
    }

    /**
     * Custom Shipping Rates Collector
     *
     * @param RateRequest $request
     * @return Result|bool
     */
    public function collectRates(RateRequest $request)
    {
        $freeShippingEnabled = $request->getFreeShipping();
        if (!$this->getConfigFlag('active')) {
            return false;
        }
        $countryId = $request->getDestCountryId();
        $currencyCodeFrom = $this->shippingMethodDataApi->getShippingCurrency($countryId);
        $currencyCodeTo = 'USD';
        $shippingCost = 0.0;
        $result = $this->rateResultFactory->create();
        $carrierName = $this->shippingMethodDataApi->getShippingCarrierName($countryId);
        $carrierName = $this->nameHelper->normalizeName($carrierName);
        $methodName = $this->shippingMethodDataApi->getShippingMethod($countryId);
        $methodName = $this->nameHelper->normalizeName($methodName);
        if (!$freeShippingEnabled && !$this->isFreeShippingEligible($methodName)) {
            $price = $this->shippingMethodDataApi->getShippingPrice($countryId);
            $shippingCost = $this->currencyConverter->convert($price, $currencyCodeFrom, $currencyCodeTo);
        }

        $method = $this->rateMethodFactory->create();
        $method->setCarrier($this->_code);
        $method->setCarrierTitle($carrierName);
        $method->setMethod($this->_code);
        $method->setMethodTitle($methodName);
        $method->setPrice($shippingCost);
        $method->setCost($shippingCost);

        $result->append($method);
        return $result;
    }

    private function isFreeShippingEligible($shippingMethod)
    {
        $shippingData = json_decode($this->configData->getConfigValue('fields'), true);
        foreach ($shippingData as $methodData) {
            if ($methodData['primary'] === $shippingMethod) {
                return $methodData['enable'] === '1';
            }
        }
        return true;
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->_code => $this->getConfigData('name')];
    }
}