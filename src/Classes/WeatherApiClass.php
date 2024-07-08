<?php
declare(strict_types=1);

namespace Andre\MeteoApp\Classes;

use Andre\MeteoApp\Interfaces\MeteoInterface;
use DateTime;
use GuzzleHttp\Client;
use ParagonIE\EasyDB\Factory;


class WeatherApiClass extends  Weather
{

    function __construct($location){
        $this->api_key="zzz0a64092891924d99a4084405240807";
        $this->service="weatherapi";
        $this->base_url = "https://api.weatherapi.com/v1/current.json?key={$this->api_key}&q={$location}";
    }

    public function getTempFromApi($data){
        $temp = $data->current->temp_c;
        $this->setTemp($temp);
        return $temp;
    }
}
