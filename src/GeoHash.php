<?php


namespace MuCTS\GeoHash;


class GeoHash
{
    public const MIN_LAT = -90;
    public const MAX_LAT = 90;
    public const MIN_LNG = -180;
    public const MAX_LNG = 180;

    private $numBits = 15;

    private $digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

    /**
     * Longitude and latitude separately encoded length
     * @param int $numBits
     * @return $this
     */
    function setNumBits(int $numBits = 15)
    {
        $this->numBits = $numBits;
        return $this;
    }

    /**
     * Geo Hash to Latitude and longitude
     * @param string $geoHash
     * @return array
     */
    public function decode(string $geoHash): array
    {
        $binary = '';
        for ($i = 0; $i < strlen($geoHash); $i++) {
            $binary .= str_pad(decbin(array_search($geoHash[$i], $this->digits)), 5, '0', STR_PAD_LEFT);
        }
        $lonSet = [];
        $latSet = [];
        for ($i = 0; $i < strlen($binary); $i++) {
            if ($i % 2) {
                array_push($lonSet, $binary[$i]);
            } else {
                array_push($latSet, $binary[$i]);
            }
        }
        $lon = $this->bitsDecode($lonSet, self::MIN_LNG, self::MAX_LNG);
        $lat = $this->bitsDecode($latSet, self::MIN_LAT, self::MAX_LAT);
        $latErr = $this->calcError(count($latSet), self::MIN_LAT, self::MAX_LAT);
        $lonErr = $this->calcError(count($lonSet), self::MIN_LNG, self::MAX_LNG);

        $latPlaces = max(1, -round(log10($latErr))) - 1;
        $lonPlaces = max(1, -round(log10($lonErr))) - 1;

        $lat = round($lat, $latPlaces);
        $lon = round($lon, $lonPlaces);

        return [$lat, $lon];
    }

    /**
     * Encode a hash from given lat and long
     *
     * @param float $lat
     * @param float $lon
     * @return string
     */
    public function encode(float $lat, float $lon)
    {
        $latBits = $this->getBits($lat, self::MIN_LAT, self::MAX_LAT);
        $lonBits = $this->getBits($lon, self::MIN_LNG, self::MAX_LNG);
        $binary = '';
        for ($i = 0; $i < $this->numBits; $i++) {
            $binary .= $lonBits[$i] . $latBits[$i];
        }
        return $this->base32($binary);
    }

    /**
     * According to latitude and longitude and range, obtain the corresponding binary
     *
     * @param float $lat
     * @param float $floor
     * @param float $ceiling
     * @return array
     */
    private function getBits(float $lat, float $floor, float $ceiling)
    {
        $buffer = [];
        for ($i = 0; $i < $this->numBits; $i++) {
            $mid = ($floor + $ceiling) / 2;
            if ($lat >= $mid) {
                array_push($buffer, 1);
                $floor = $mid;
            } else {
                array_push($buffer, 0);
                $ceiling = $mid;
            }
        }
        return $buffer;
    }

    /**
     * Binary to 32
     *
     * @param string $binary
     * @return string
     */
    private function base32(string $binary)
    {
        $hash = '';
        for ($i = 0; $i < strlen($binary); $i += 5) {
            $n = bindec(substr($binary, $i, 5));
            $hash = $hash . $this->digits[$n];
        }
        return $hash;
    }

    /**
     * Latitude Or longitude decoding
     *
     * @param array $bs
     * @param float $floor
     * @param float $ceiling
     * @return float
     */
    private function bitsDecode(array $bs, float $floor, float $ceiling): float
    {
        $mid = 0;
        for ($i = 0; $i < count($bs); $i++) {
            $mid = ($floor + $ceiling) / 2;
            if ($bs[$i] == 1)
                $floor = $mid;
            else
                $ceiling = $mid;
        }
        return $mid;
    }

    private function calcError(int $bits, float $floor, float $ceiling): float
    {
        $err = ($ceiling - $floor) / 2;
        while ($bits--)
            $err /= 2;
        return $err;
    }
}