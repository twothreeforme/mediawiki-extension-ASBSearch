<?php

class WeatherForecast_ElementMaps {

public static $weatherelements = [
        0 => [ "Hot Spell", "Heat Wave" ],
        1 => [ "Blizzard", "Snow" ],
        2 => [ "Wind", "Gales" ],
        3 => [ "Dust Storm", "Sand Storm"],
        4 => [ "Thunder", "Thunderstorms"],
        5 => [ "Rain", "Squall"],
        6 => [ "Gloom", "Darkness"],
        7 => [ "Auroras", "Stellar Glare"],
        8 => [ "None", "Sunshine", "Clouds", "Fog" ]
    ];

public static $elements = [
        "Fire" => 0,
        "Ice" => 1,
        "Wind" => 2,
        "Earth" => 3,
        "Thunder" => 4,
        "Water" => 5,
        "Dark" => 6,
        "Light" => 7,
        "** All Weather Types **" => 8
    ];

}


?>