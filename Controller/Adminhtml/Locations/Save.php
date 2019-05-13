<?php

namespace Rcason\StorePicker\Controller\Adminhtml\Locations;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
use Magento\Backend\Model\Auth\Session as AuthSession;

use Rcason\StorePicker\Api\Data\LocationInterfaceFactory;
use Rcason\StorePicker\Api\LocationRepositoryInterface;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var LocationInterfaceFactory
     */
    protected $locationFactory;
    
    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;
    
    /**
     * @var AuthSession
     */
    protected $authSession;

    /**
     * @param Action\Context $context
     * @param LocationInterfaceFactory $locationFactory
     * @param LocationRepositoryInterface $locationRepository
     * @param AuthSession $authSession
     */
    public function __construct(
        Action\Context $context,
        LocationInterfaceFactory $locationFactory,
        LocationRepositoryInterface $locationRepository,
        AuthSession $authSession
    ) {
        $this->locationFactory = $locationFactory;
        $this->locationRepository = $locationRepository;
        $this->authSession = $authSession;
        
        parent::__construct($context);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Rcason_StorePicker::locations_save');
    }

    /**
     * Save location
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if($data) {

            $id = $this->getRequest()->getParam('country_id');
            $model = $this->getLocationByCountry($id);

            unset($data['country_id']);
            $model->addData($data);

            try {
                $this->locationRepository->save($model);

                $this->messageManager->addSuccess(__('Location saved successfully.'));
                $this->_session->setFormData(false);
                
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['country_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the location.'));
            }

            $this->_getSession()->setFormData($data);
            
            return $resultRedirect->setPath('*/*/edit', [
                'country_id' => $this->getRequest()->getParam('country_id')
            ]);
        }
        
        return $resultRedirect->setPath('*/*/');
    }
    
    /**
     * Return location model by country id
     */
    private function getLocationByCountry($countryId)
    {
        $model = null;
        
        if ($countryId) {
            try {
                $model = $this->locationRepository->getByCountryId($countryId);
            } catch (\Magento\Framework\Exception\NoSuchEntityException $ex) { }
        }

        if (is_null($model)) {
            $model = $this->locationFactory->create()
                ->setCountryId($countryId);
        }

        return $model;
    }
}
