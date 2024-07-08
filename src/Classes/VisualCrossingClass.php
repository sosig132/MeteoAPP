<?php

namespace Andre\MeteoApp\Classes;

use Andre\MeteoApp\Interfaces\MeteoInterface;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use ParagonIE\EasyDB\Factory;

class VisualCrossingClass extends Weather {

    function __construct($location){
        $this->api_key="PDDP582M9TFWVWCA99AT8GWBU";
        $this->service="visualcrossing";
        $this->base_url = "https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/{$location}?unitGroup=metric&key={$this->api_key}&contentType=json";
    }

    public function getTempFromApi($data){
        $temp = $data->currentConditions->temp;
        $this->setTemp($temp);
        return $temp;
    }
}
