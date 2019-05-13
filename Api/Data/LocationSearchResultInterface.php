<?php

namespace Rcason\StorePicker\Api\Data;

/**
 * Interface for location search results
 * @api
 */
interface LocationSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get locations list
     *
     * @return \Rcason\StorePicker\Api\LocationInterface[]
     */
    public function getItems();

    /**
     * Set locations list
     *
     * @param \Rcason\StorePicker\Api\LocationInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
