<?php

$code = 7190;
$result = getWeatherData($code);

return [
    'date' => getData($result),
    'city' => getCity($result),
    'sunrise' => getSunTime($result, "sunrise"),
    'sunset' => getSunTime($result, "sunset"),
    'day_length' => getDayLength($result),
    'temperature' => getTemperature($result),
];

function getWeatherData($code)
{
    $curl = curl_init();
    $url = "http://www.gismeteo.ua/city/hourly/" . $code . "/";

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

    $result = curl_exec($curl);

    if (!$result) return "Помилка cURL: " . curl_error($curl);

    curl_close($curl);

    return $result;
}

function getData($result)
{
    $pattern = '/(\d{4})-(\d{1,2})-(\d{1,2})/';
    preg_match($pattern, $result, $matches);

    return "$matches[3].$matches[2].$matches[1]";
}

function getCity($result)
{
    $pattern = "/<title>METEOFOR: .*, прогноз погоды (.*) на сегодня,.*<\/title>/";
    preg_match($pattern, $result, $matches);

    return $matches[1];
}

function getDayLength($result)
{
    $pattern = '/<div class="astro-progress">Долгота дня: (\d{1,2}) ч (\d{1,2}) мин<\/div>/';
    preg_match($pattern, $result, $matches);

    $hours = $matches[1];
    $minutes = $matches[2];

    $hoursDeclension = getHours($hours);

    return ($minutes == 0) ? "рівно $hours $hoursDeclension." : "$hours $hoursDeclension $minutes хв";
}

function getHours($hours)
{
    switch ($hours) {
        case 1:
        case 21:
            return 'година';
        case 2:
        case 3:
        case 4:
        case 22:
        case 23:
        case 24:
            return 'години';
        default:
            return 'годин';
    }
}

function getSunTime($result, $event)
{
    $pattern = $event === "sunrise" ? '/Восход — ([\d]{1,2}:\d{2})/u' : '/Заход — ([\d]{1,2}:\d{2})/u';
    if (preg_match($pattern, $result, $matches)) return $matches[1];

    return "не знайдено";
}

function getTemperature($result)
{
    $pattern = '/<temperature-value value="(-?\d+)"/';
    preg_match_all($pattern, $result, $matches);

    $values = $matches[1];
    $temperatures = ["0г: ", "3г: ", "6г: ", "9г: ", "12г: ", "15г: ", "18г: ", "21г: "];

    for ($i = 7, $j = 0; $i < 15; $i++, $j++) {
        $temp = $values[$i];

        if ($temp > 0) $temp = "+" . $temp;

        $temperatures[$j] .= "{$temp}°C";
    }

    return implode(", ", $temperatures);
}