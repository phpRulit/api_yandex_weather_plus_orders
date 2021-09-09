<?php

namespace App\Http\Controllers\Yandex;

use App\Http\Controllers\Controller;
use App\Services\Yandex\Weather\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function currentWeather (Request $request): JsonResponse
    {
        //Тестовый тариф - https://yandex.ru/dev/weather/doc/dg/concepts/forecast-test.html
        //Платный тариф - https://yandex.ru/dev/weather/doc/dg/concepts/forecast-info.html
        $params = [
            'lat' => $request['lat'], // Широта в градусах. Обязательное поле.
            'lon' => $request['lon'], //Долгота в градусах. Обязательное поле.
            'lang' => 'ru_RU', // Сочетания языка и страны, для которых будут возвращены данные погодных формулировок.
            'limit' => 1, // Количество дней в прогнозе, включая текущий.
            'hours' => true, // значение по умолчанию, почасовой прогноз возвращается.
            'extra' => true // Расширенная информация об осадках, возвращается.
        ];

        $data = new WeatherService($params);

        return response()->json($data->weather());
    }

}
