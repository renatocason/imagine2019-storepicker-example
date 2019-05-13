<?php

namespace Rcason\StorePicker\Api;

/**
 * Locations CRUD interface
 * @api
 */
interface LocationRepositoryInterface
{
    /**
     * Save location
     *
     * @param \Rcason\StorePicker\Api\Data\LocationInterface $location
     * @return \Rcason\StorePicker\Api\Data\LocationInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Rcason\StorePicker\Api\Data\LocationInterface $location);

    /**
     * Retrieve location by country id
     *
     * @param string $countryId
     * @return \Rcason\StorePicker\Api\Data\LocationInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByCountryId($countryId);

    /**
     * Retrieve locations matching the specified criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Rcason\StorePicker\Api\LocationSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete location
     *
     * @param \Rcason\StorePicker\Api\Data\LocationInterface $location
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Rcason\StorePicker\Api\Data\LocationInterface $location);

    /**
     * Delete location by country id
     *
     * @param int $countryId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteByCountryId($countryId);
}
