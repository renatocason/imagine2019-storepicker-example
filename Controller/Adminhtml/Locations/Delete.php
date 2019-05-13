<?php

namespace Rcason\StorePicker\Controller\Adminhtml\Locations;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

use Rcason\StorePicker\Api\LocationRepositoryInterface;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @param Action\Context $context
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(
        Action\Context $context,
        LocationRepositoryInterface $locationRepository
    ) {
        $this->locationRepository = $locationRepository;
        
        parent::__construct($context);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Rcason_StorePicker::locations_delete');
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('country_id');
        
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if ($id) {
            try {
                $model = $this->locationRepository->deleteByCountryId($id);
                
                $this->messageManager->addSuccess(__('The location has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/');
            }
        }
        
        $this->messageManager->addError(__('We can\'t find the location to delete.'));
        
        return $resultRedirect->setPath('*/*/');
    }
}
