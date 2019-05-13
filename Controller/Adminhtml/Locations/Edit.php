<?php

namespace Rcason\StorePicker\Controller\Adminhtml\Locations;

use Magento\Backend\App\Action;

use Rcason\StorePicker\Api\Data\LocationInterfaceFactory;
use Rcason\StorePicker\Api\LocationRepositoryInterface;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var LocationInterfaceFactory
     */
    protected $locationFactory;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param LocationInterfaceFactory $locationFactory
     * @param LocationRepositoryInterface $locationRepository
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        LocationInterfaceFactory $locationFactory,
        LocationRepositoryInterface $locationRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->locationFactory = $locationFactory;
        $this->locationRepository = $locationRepository;
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        
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
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rcason_StorePicker::locations')
            ->addBreadcrumb(__('Store Picker Locations'), __('Edit'));
        
        return $resultPage;
    }

    /**
     * Edit location
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('country_id');
        $model = $this->getLocationByCountry($id);

        $data = $this->_session->getFormData(true);
        if (!empty($data)) {
            unset($data['country_id']);
            $model->addData($data);
        }

        $this->coreRegistry->register('current_storepicker_location', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Location') : __('New Location'),
            $id ? __('Edit Location') : __('New Location')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Store Picker Locations'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? __('Edit Location') : __('New Location'));

        return $resultPage;
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
