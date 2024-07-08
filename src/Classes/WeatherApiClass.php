<?php
declare(strict_types=1);

namespace Andre\MeteoApp\Classes;

use Andre\MeteoApp\Interfaces\MeteoInterface;
use DateTime;
use GuzzleHttp\Client;
use ParagonIE\EasyDB\Factory;


class WeatherApiClass implements MeteoInterface
{

    public function getMeteo($location)
    {

        $service = 'weatherapi';
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

        $api_key = "0a64092891924d99a4084405240807";
        $base_url = "https://api.weatherapi.com/v1/current.json?";
        $client = new Client();
        $response = null;
        try {
            $response = $client->request('GET', $base_url . 'key=' . $api_key . '&q=' . $location);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            $response = $e->getMessage();
        }
        $data = json_decode($response->getBody()->getContents());

        $temp = $data->current->temp_c;


        $date = new DateTime();
        $formattedDate = $date->format('Y-m-d H:i:s');

        $db->insert("temperatures", ['temperature'=>$temp, 'location' => $location, 'service' => $service, 'time' => $formattedDate]);

        return $temp;
    }
}