<?php

namespace Minulislam\Countries\DataSources;

use Exception;
use Minulislam\Countries\Country;
use Minulislam\Countries\Interfaces\DataSourceInterface;

class MledozeCountriesJson implements DataSourceInterface
{
    private $countryData;

    public function __construct()
    {
        $paths = [];
        $paths[] = __DIR__.'/../../../../mledoze/countries/dist/countries.json';
        $paths[] = __DIR__.'/../../vendor/mledoze/countries/dist/countries.json';

        if (!$this->countryData) {
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    $this->countryData = json_decode(file_get_contents($path));
                    break;
                }
            }
        }

        if (!$this->countryData) {
            throw new Exception('Unable to retrieve MledozeCountries JSON data file. Have you ran composer update?');
        }
    }

    public function all()
    {
        $countries = [];

        foreach ($this->countryData as $countryDataItem) {
            $country = new Country();
            $country->name = $countryDataItem->name->common;
            $country->officialName = $countryDataItem->name->official;
            $country->isoCodeAlpha2 = $countryDataItem->cca2;
            $country->isoCodeAlpha3 = $countryDataItem->cca3;
            $country->isoCodeNumeric = $countryDataItem->ccn3;
            $country->region = $countryDataItem->region;
            $country->subregion = $countryDataItem->subregion;

            $countries[] = $country;
        }

        return $countries;
    }
}
