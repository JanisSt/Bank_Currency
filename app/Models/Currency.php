<?php
declare(strict_types=1);
namespace App\Models;

class Currency
{
    private string $country;
    private float $rate;

    public function __construct(string $country, float $rate)
    {

        $this->country = $country;
        $this->rate = $rate;


    }

    public function getCountry():string
    {
       return $this->country;
    }

    public function getRate():float
    {
        return $this->rate;
    }
}