<?php

namespace Rcason\StorePicker\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Directory\Api\CountryInformationAcquirerInterface;

class Countries implements OptionSourceInterface
{
    /**
     * @var CountryInformationAcquirerInterface
     */
    private $countryInformationAcquirer;
 
    /**
     * @param CountryInformationAcquirerInterface $countryInformationAcquirer
     */
    public function __construct(
        CountryInformationAcquirerInterface $countryInformationAcquirer
    ) {
        $this->countryInformationAcquirer = $countryInformationAcquirer;
    }
    
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $options = [];
        $countries = $this->countryInformationAcquirer->getCountriesInfo();
        
        foreach ($countries as $country) {
            $options[] = [
                'value' => $country->getId(),
                'label' => $country->getFullNameEnglish()
            ];
        }

        return $options;
    }
}
