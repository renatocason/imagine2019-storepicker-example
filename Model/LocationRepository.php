<?php

// @codingStandardsIgnoreFile

namespace Rcason\StorePicker\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;

use Rcason\StorePicker\Api\Data\LocationInterface;
use Rcason\StorePicker\Api\Data\LocationSearchResultInterfaceFactory as SearchResultFactory;
use Rcason\StorePicker\Api\Data\LocationInterfaceFactory;
use Rcason\StorePicker\Api\LocationRepositoryInterface;
use Rcason\StorePicker\Model\ResourceModel\Location as ResourceModel;

/**
 * Location repository
 */
class LocationRepository implements LocationRepositoryInterface
{
    /**
     * @var LocationInterfaceFactory
     */
    private $locationFactory;
    
    /**
     * @var ResourceModel
     */
    private $resourceModel;
    
    /**
     * @var SearchResultFactory
     */
    private $searchResultFactory;
    
    /**
     * @var array
     */
    private $registry = [];
    
    /**
     * @param LocationInterfaceFactory $locationFactory
     * @param ResourceModel $resourceModel
     * @param SearchResultFactory $searchResultFactory
     */
    public function __construct(
        LocationInterfaceFactory $locationFactory,
        ResourceModel $resourceModel,
        SearchResultFactory $searchResultFactory
    ) {
        $this->locationFactory = $locationFactory;
        $this->resourceModel = $resourceModel;
        $this->searchResultFactory = $searchResultFactory;
    }
    
    /**
     * @inheritdocs
     */
    public function save(LocationInterface $location)
    {
        $this->resourceModel->save($location);
        return $location;
    }

    /**
     * @inheritdocs
     */
    public function getByCountryId($countryId)
    {
        if (!$countryId) {
            throw new InputException(__('Country ID is required'));
        }
        
        if (!isset($this->registry[$countryId])) {
            $entity = $this->locationFactory->create();
            $this->resourceModel->load($entity, $countryId);
            
            if (!$entity->getId()) {
                throw new NoSuchEntityException(__('Requested entity does not exist'));
            }
            
            $this->registry[$countryId] = $entity;
        }
        
        return $this->registry[$countryId];
    }

    /**
     * @inheritdocs
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResult = $this->searchResultFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $this->addFilterGroupToCollection($filterGroup, $searchResult);
        }

        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders === null) {
            $sortOrders = [];
        }
        
        foreach ($sortOrders as $sortOrder) {
            $field = $sortOrder->getField();
            $searchResult->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
            );
        }

        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setCurPage($searchCriteria->getCurrentPage());
        $searchResult->setPageSize($searchCriteria->getPageSize());
        
        return $searchResult;
    }

    /**
     * @inheritdocs
     */
    public function delete(LocationInterface $location)
    {
        return $this->resourceModel->delete($location);
    }

    /**
     * @inheritdocs
     */
    public function deleteByCountryId($countryId)
    {
        return $this->delete(
            $this->getByCountryId($countryId)
        );
    }
    
    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @param \Rcason\StorePicker\Api\Data\LocationSearchResultInterface $searchResult
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     */
    protected function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Rcason\StorePicker\Api\Data\LocationSearchResultInterface $searchResult
    ) {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $conditions[] = [$condition => $filter->getValue()];
            $fields[] = $filter->getField();
        }
        if ($fields) {
            $searchResult->addFieldToFilter($fields, $conditions);
        }
    }
}
