<?php

use MuCTS\GeoHash\GeoHash;

if (!function_exists('geo_hash')) {
    function geo_hash(): GeoHash
    {
        return new GeoHash();
    }
}

if (!function_exists('geo_hash_encode')) {
    /**
     * Decode a geo hash and return an array with decimal lat,long in it
     *
     * @param float $lat
     * @param float $lon
     * @param int $numBits
     * @return string
     */
    function geo_hash_encode(float $lat, float $lon, int $numBits = 15): string
    {
        return geo_hash()->setNumBits($numBits)->encode($lat, $lon);
    }
}

if (!function_exists('geo_hash_decode')) {
    /**
     * Geo Hash to Latitude and longitude
     *
     * @param string $geoHash
     * @return array
     */
    function geo_hash_decode(string $geoHash): array
    {
        return geo_hash()->decode($geoHash);
    }
}