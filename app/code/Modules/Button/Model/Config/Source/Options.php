<?php namespace Modules\Button\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Options extends AbstractSource
{
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['label' => '', 'value' => ''],
            ['label' => 'Small', 'value' => '1'],
            ['label' => 'Medium', 'value' => '2'],
            ['label' => 'Large', 'value' => '3']
        ];
        return $this->_options;
    }

    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string|bool
     */
    public function getOptionText($value)
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }
}