<?php namespace Modules\Promo\Block;

use DateTime;
use Magento\Catalog\Helper;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Modules\Promo\Helper\Data;

class PromoLabel extends Template
{
    private $magentoHelper;
    private $helper;
    private $product;

    public function __construct(
        Product $product,
        Context $context,
        Helper\Data $magentoHelper,
        Data $helper,
        array $data = []
    )
    {
        $this->product = $product;
        $this->helper = $helper;
        $this->magentoHelper = $magentoHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function checkPromotion()
    {
        if (
        ($this->helper->getGeneralConfig('enable') &
            $this->getTimeLeft() !== false)) {
            if (is_null($this->product
                ->getData('PromoEnable_attribute'))) {
                $this->product = $this->magentoHelper->getProduct();
            }
            return $this->product->getData('PromoEnable_attribute');
        }
        return false;
    }

    public function getLabelText(): string
    {
        return $this->helper->getGeneralConfig('title');
    }

    public function getLabelTextColor(): string
    {
        return $this->helper->getGeneralConfig('textColor');
    }

    public function getLabelBackgroundColor(): string
    {
        return $this->helper->getGeneralConfig('backgroundColor');
    }

    public function getCurrentTime(): string
    {
        $currentDateTime = date('Y-m-d H:i:s');
        return $currentDateTime;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getTimeLeft()
    {
        $time1 = new DateTime($this->helper->getGeneralConfig('time'));
        $time2 = new Datetime($this->getCurrentTime());
        $interval = $time1->diff($time2);
        if ($interval->format('%i') !== 0) {
            $elapsed = $interval
                ->format('%a days %h hours %i minutes remaining');
            return $elapsed;
        }
        return false;
    }
}