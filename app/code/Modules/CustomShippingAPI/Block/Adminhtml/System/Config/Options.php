<?php namespace Modules\CustomShippingAPI\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Backend\Block\Template\Context;

class Options extends AbstractFieldArray
{
    /**
     * @var Checkbox
     */
    private $checkboxRenderer;
    public function __construct(
        Context $context,
        Checkbox $checkboxRenderer
    ) {
        parent::__construct($context);
        $this->checkboxRenderer = $checkboxRenderer;
    }

    protected function _prepareToRender()
    {
        $this->addColumn('primary', ['label' => __('Primary'), 'renderer' => false]);
        $this->addColumn('result', ['label' => __('Result'), 'renderer' => false]);
        $this->addColumn('enable', ['label' => __('Free'), 'renderer' => $this->checkboxRenderer]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}
