<?php

namespace Rcason\StorePicker\Ui\Component\Listing\Grid\Column;

use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class LocationAction extends Column
{
    /** Url paths */
    const URL_PATH_EDIT = 'storepicker/locations/edit';
    const URL_PATH_DELETE = 'storepicker/locations/delete';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    
    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    protected $_authorization;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        AuthorizationInterface $authorization,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->_authorization = $authorization;
        
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if(!isset($dataSource['data']['items'])) {
            return $dataSource;
        }
        
        foreach ($dataSource['data']['items'] as & $item) {
            $name = $this->getData('name');
            if(!isset($item['country_id'])) {
                continue;
            }
            
            $item[$name]['edit'] = [
                'href' => $this->urlBuilder->getUrl(
                    self::URL_PATH_EDIT,
                    ['country_id' => $item['country_id']]
                ),
                'label' => __('Edit')
            ];
            
            if ($this->_isAllowedAction('Rcason_StorePicker::locations_delete')) {
                $item[$name]['delete'] = [
                    'href' => $this->urlBuilder->getUrl(
                        self::URL_PATH_DELETE,
                        ['country_id' => $item['country_id']]
                    ),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete Location'),
                        'message' => __('Are you sure you wan\'t to delete the selected location?')
                    ]
                ];
            }
        }

        return $dataSource;
    }
    
    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
