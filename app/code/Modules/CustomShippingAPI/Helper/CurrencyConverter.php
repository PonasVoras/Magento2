<?php namespace Modules\CustomShippingAPI\Helper;

use Magento\Directory\Model\CurrencyFactory;

class CurrencyConverter
{
    protected $storeManager;
    protected $currencyFactory;
    public function __construct(
        CurrencyFactory $currencyFactory
    ) {
        $this->currencyFactory = $currencyFactory;
    }
    public function convert(float $amountValue, string $currencyCodeFrom, string $currencyCodeTo): float
    {
        if ($currencyCodeFrom === $currencyCodeTo) {
            return $amountValue;
        }
        $rate = $this->currencyFactory->create()->load($currencyCodeFrom)->getAnyRate($currencyCodeTo);
        $amountValue = $amountValue * $rate;
        return round($amountValue, 0);
    }
}