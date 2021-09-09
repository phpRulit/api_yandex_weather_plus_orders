<?php

namespace App\Services\Yandex\Weather;

class WeatherService
{
    /**
     * Получаем клиента
     *
     * @var WeatherClient
     */
    private $client;

    /**
     * Сюда попадает погода
     */
    private $data = null;

    /**
     * WeatherService constructor.
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->client = new WeatherClient();

        $this->query($params);
    }

    /**
     * Погода, вытаскиваем данные.
     */
    public function weather(): array
    {
        return [
            'temp' => $this->data->fact->temp, //Температура (°C).
            'feels_like' => $this->data->fact->feels_like, //Ощущаемая температура (°C).
            'icon_url' => "https://yastatic.net/weather/i/icons/funky/dark/{$this->data->fact->icon}.svg", //url иконки
            'condition' => $this->data->fact->condition, //Расшифровки погодного описания.
            'wind_speed' => $this->data->fact->wind_speed, //Скорость ветра (в м/с).
            'wind_dir' => $this->data->fact->wind_dir, //Направление ветра.
            'pressure_mm' => $this->data->fact->pressure_mm, //Давление (в мм рт. ст.).
            //И многое другое...
        ];
    }

    /**
     * Получаем погоду
     */
    private function query(array $params = [])
    {
        $query = http_build_query($params);

        //Тарифы: тестовый forecast; платный: informers
        $response = $this->client->makeRequest("/v2/forecast?{$query}");

        if($response)
            $this->data = json_decode($response);
    }
}
