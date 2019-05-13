<?php

namespace Rcason\StorePicker\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManagerInterface;

class Stores implements OptionSourceInterface
{
    /**
     * @var StoreManager
     */
    protected $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }
    
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $options = [];
        $stores = $this->storeManager->getStores();
        
        foreach ($stores as $store) {
            $options[] = [
                'value' => $store->getId(),
                'label' => $store->getName(),
            ];
        }

        return $options;
    }
}
