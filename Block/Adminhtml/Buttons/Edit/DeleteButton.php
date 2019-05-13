<?php

namespace Rcason\StorePicker\Block\Adminhtml\Buttons\Edit;

use Rcason\StorePicker\Block\Adminhtml\Buttons\AbstractButton;

class DeleteButton extends AbstractButton
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        if(!$this->authorization->isAllowed('Rcason_StorePicker::locations_delete')) {
            return [];
        }
        
        return [
            'label' => __('Delete'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm(\'' . __(
                'Are you sure you want to do this?'
            ) . '\', \'' . $this->getDeleteUrl() . '\')',
            'sort_order' => 20,
        ];
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['entity_id'=>$this->request->getParam('entity_id')]);
    }
}
