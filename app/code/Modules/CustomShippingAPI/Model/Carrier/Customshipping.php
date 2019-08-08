<?php namespace Modules\CustomShippingAPI\Model\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Modules\CustomShippingAPI\API\CurrencyCodeApi;
use Modules\CustomShippingAPI\API\ShippingMethodDataApi;
use Modules\CustomShippingAPI\Helper\CurrencyConverter;
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
     * @var $currencyCodeApi
     */
    private $currencyCodeApi;

    /**
     * @var $currencyConverter
     */
    private $currencyConverter;

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
        CurrencyCodeApi $currencyCodeApi,
        CurrencyConverter $currencyConverter
    )
    {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger);

        $this->currencyConverter = $currencyConverter;
        $this->currencyCodeApi = $currencyCodeApi;
        $this->shippingMethodDataApi = $shippingMethodDataApi;
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
    }

    /**
     * Custom Shipping Rates Collector
     *
     * @param RateRequest $request
     * @return Result|bool
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }
        $countryId = $request->getDestCountryId();
        $currencyCodeFrom = $this->currencyCodeApi->getCurrencyCode($countryId);
        $currencyCodeTo = 'USD';
        $price = $this->shippingMethodDataApi->getShippingPrice($countryId);

        /** @var Result $result */
        $result = $this->rateResultFactory->create();

        /** @var Method $method */
        $method = $this->rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle(
            $this->shippingMethodDataApi->getShippingCarrierName($countryId)
        );

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->shippingMethodDataApi->getShippingMethod($countryId));

        $shippingCost = $this->currencyConverter->convert($price, $currencyCodeFrom, $currencyCodeTo);

        $method->setPrice($shippingCost);
        $method->setCost($shippingCost);

        $result->append($method);

        return $result;
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->_code => $this->getConfigData('name')];
    }
}