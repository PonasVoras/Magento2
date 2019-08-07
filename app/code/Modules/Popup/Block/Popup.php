<?php namespace Modules\Popup\Block;

use Magento\Framework\View\Element\Template;
use Modules\Popup\Helper\Data;

class Popup extends Template
{
    private $helper;

    public function __construct(
        Data $helper,
        Template\Context $context,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    public function getTitle()
    {
        return $this->helper->getGeneralConfig('title');
    }

    public function getDelay()
    {
        return $this->helper->getGeneralConfig('delay');
    }

    public function popupEnabled()
    {
        return $this->helper->getGeneralConfig('enable');
    }
}