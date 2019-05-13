<?php

namespace Rcason\StorePicker\Block\Adminhtml\Buttons\Edit;

use Rcason\StorePicker\Block\Adminhtml\Buttons\AbstractButton;

class SaveButton extends AbstractButton
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        if(!$this->authorization->isAllowed('Rcason_StorePicker::locations_save')) {
            return [];
        }
        
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
