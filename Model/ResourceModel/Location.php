<?php

// @codingStandardsIgnoreFile

namespace Rcason\StorePicker\Model\ResourceModel;

use Rcason\StorePicker\Api\LocationInterface;
use Rcason\StorePicker\Model\ResourceModel\Location as ResourceModel;

/**
 * Location resource model
 */
class Location extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Primary key auto increment flag
     *
     * @var bool
     */
    protected $_isPkAutoIncrement = false;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('storepicker_location', 'country_id');
    }
}
