<?php

namespace Minulislam\Countries;

use Minulislam\Countries\DataSources\MledozeCountriesJson;
use Minulislam\Countries\Interfaces\DataSourceInterface;

class Countries
{
    public $dataSource;

    public function __construct()
    {
        $this->setDataSource(new MledozeCountriesJson());
    }

    public function setDataSource(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    public function all()
    {
        $countries = $this->dataSource->all();

        usort($countries, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        return $countries;
    }

    public function getByName(string $name)
    {
        foreach ($this->all() as $country) {
            if ($country->name == $name || $country->officialName == $name) {
                return $country;
            }
        }
    }

    public function getByIsoCode(string $code)
    {
        foreach ($this->all() as $country) {
            if ($country->isoCodeAlpha2 == $code || $country->isoCodeAlpha3 == $code || $country->isoCodeNumeric == $code) {
                return $country;
            }
        }
    }


}
