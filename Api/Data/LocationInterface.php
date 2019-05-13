<?php

namespace Rcason\StorePicker\Api\Data;

/**
 * Country/Store relationship
 * @api
 */
interface LocationInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const KEY_COUNTRY_ID = 'country_id';
    const KEY_STORE_ID = 'store_id';
    /**#@-*/
    
    /**
     * Get the country ISO2 code
     * 
     * @return string
     */
    public function getCountryId();
    
    /**
     * Set the country ISO2 code
     * 
     * @param string $countryId
     * @return $this
     */
    public function setCountryId($countryId);
    
    /**
     * Get the store id
     * 
     * @return int
     */
    public function getStoreId();
    
    /**
     * Set the store id
     * 
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId);
}
