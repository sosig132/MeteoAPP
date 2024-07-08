<?php

namespace Andre\MeteoApp\Classes;

use Andre\MeteoApp\Interfaces\MeteoInterface;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use ParagonIE\EasyDB\Factory;

class VisualCrossingClass implements MeteoInterface{

    public function getMeteo($location)
    {
        $service = 'visualcrossing';
        $db = Factory::fromArray([
            'mysql:host=localhost;dbname=MeteoAPP',
            'root',
            'root'
        ]);

        $row = $db->run("SELECT * FROM temperatures WHERE location = '$location' and service = '$service' ORDER BY id DESC LIMIT 1");

        if($row){
            $currentTime = new DateTime();
            $fetchedTime = new DateTime($row[0]["time"]);
            $diff = $currentTime->diff($fetchedTime);



            if ($diff->i < 1 && $diff->h === 0 && $diff->d === 0 && $diff->m === 0 && $diff->y === 0) {
                return "fetched from db " . $row[0]["temperature"];
            }

        }

        $api_key = "PDDP582M9TFWVWCA99AT8GWBU";
        $base_url = "https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/";
        $client = new Client();
        $response = null;
        try {
            $response = $client->request('GET', $base_url  . $location . '?unitGroup=metric&key=' . $api_key . '&contentType=json');
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            $response = $e->getMessage();
        }
        $data = json_decode($response->getBody()->getContents());

        $temp = $data->currentConditions->temp;

        $date = new DateTime();
        $formattedDate = $date->format('Y-m-d H:i:s');

        $db->insert("temperatures", ['temperature'=>$temp, 'location' => $location, 'service' => $service, 'time' => $formattedDate]);

        return $temp;
    }
}
