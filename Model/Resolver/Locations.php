<?php

declare(strict_types=1);

namespace Rcason\StorePicker\Model\Resolver;

use Magento\Framework\Api\SearchCriteriaInterfaceFactory;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Directory\Api\CountryInformationAcquirerInterface;
use Magento\Directory\Api\Data\CountryInformationInterface;

use Rcason\StorePicker\Api\Data\LocationInterface;
use Rcason\StorePicker\Api\LocationRepositoryInterface;

/**
 * Locations field resolver, used for GraphQL request processing.
 */
class Locations implements ResolverInterface
{
    /**
     * @var DataObjectProcessor
     */
    private $dataProcessor;

    /**
     * @var LocationRepositoryInterface
     */
    private $locationRepository;

    /**
     * @var SearchCriteriaInterfaceFactory
     */
    private $searchCriteriaFactory;

    /**
     * @var CountryInformationAcquirerInterface
     */
    private $countryInformationAcquirer;

    /**
     * @param DataObjectProcessor $dataProcessor
     * @param LocationRepositoryInterface $locationRepository
     * @param SearchCriteriaInterfaceFactory $searchCriteriaFactory
     * @param CountryInformationAcquirerInterface $countryInformationAcquirer
     */
    public function __construct(
        DataObjectProcessor $dataProcessor,
        LocationRepositoryInterface $locationRepository,
        SearchCriteriaInterfaceFactory $searchCriteriaFactory,
        CountryInformationAcquirerInterface $countryInformationAcquirer
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->locationRepository = $locationRepository;
        $this->searchCriteriaFactory = $searchCriteriaFactory;
        $this->countryInformationAcquirer = $countryInformationAcquirer;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $searchCriteria = $this->searchCriteriaFactory->create();
        $locations = $this->locationRepository->getList($searchCriteria);
        $countries = $this->getCountriesById();

        $output = [];
        foreach ($locations as $location) {
            $item = $this->dataProcessor->buildOutputDataArray($location, LocationInterface::class);

            $item['country'] = $this->dataProcessor->buildOutputDataArray(
                $countries[$location->getCountryId()],
                CountryInformationInterface::class
            );

            $output[] = $item;
        }

        return $output;
    }

    /**
     * Load countries in id to entity associative array
     */
    private function getCountriesById()
    {
        $map = [];
        $countries = $this->countryInformationAcquirer->getCountriesInfo();

        foreach ($countries as $country) {
            $map[$country->getId()] = $country;
        }

        return $map;
    }
}
