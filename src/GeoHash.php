<?php


namespace MuCTS\GeoHash;


class GeoHash
{
    public const MIN_LAT = -90;
    public const MAX_LAT = 90;
    public const MIN_LON = -180;
    public const MAX_LON = 180;

    public const ERR_LNG = 90;
    public const ERR_LAT = 45;

    /** @var int $bits */
    private $bits;
    /** @var int $lonBits */
    private $lonBits;
    /** @var int $latBits */
    private $latBits;

    private $digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

    /**
     * Longitude and latitude separately encoded length
     * @param null|int $bits
     * @return $this
     */
    function setBits(?int $bits)
    {
        $this->bits = $this->lonBits = $this->latBits = $bits;
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
                array_push($latSet, $binary[$i]);
            } else {
                array_push($lonSet, $binary[$i]);
            }
        }
        $lon = $this->bitsDecode($lonSet, self::MIN_LON, self::MAX_LON);
        $lat = $this->bitsDecode($latSet, self::MIN_LAT, self::MAX_LAT);
        $latErr = $this->calcError(count($latSet), self::MIN_LAT, self::MAX_LAT);
        $lonErr = $this->calcError(count($lonSet), self::MIN_LON, self::MAX_LON);

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
        $this->adjustBits($lat, $lon);
        $latBits = $this->getBits($lat, self::MIN_LAT, self::MAX_LAT, $this->latBits);
        $lonBits = $this->getBits($lon, self::MIN_LON, self::MAX_LON, $this->lonBits);
        $binary = '';
        while ($latBits || $lonBits) {
            $binary .= array_shift($lonBits) ?? '';
            $binary .= array_shift($latBits) ?? '';
        }
        return $this->base32($binary);
    }

    /**
     * According to latitude and longitude and range, obtain the corresponding binary
     *
     * @param float $number
     * @param float $floor
     * @param float $ceiling
     * @param int $bits
     * @return array
     */
    private function getBits(float $number, float $floor, float $ceiling, int $bits): array
    {
        $buffer = [];
        for ($i = 0; $i < $bits; $i++) {
            $mid = ($floor + $ceiling) / 2;
            if ($number >= $mid) {
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

    /**
     * get precision
     * @param string $number
     * @return float
     */
    private function precision(string $number): float
    {
        $precision = 0;
        $pt = strpos($number, '.');
        if ($pt !== false) {
            $precision = -(strlen($number) - $pt - 1);
        }
        return pow(10, $precision) / 2;
    }

    private function preBits(float $number, int $err)
    {
        $pre = $this->precision(strval($number));
        $bits = 1;
        while ($err > $pre) {
            $bits++;
            $err /= 2;
        }
        return $bits;
    }

    private function adjustBits(float $lat, float $lon): void
    {
        if (!$this->bits) {
            $this->setBits(max($this->preBits($lat, self::ERR_LAT), $this->preBits($lon, self::ERR_LNG)));
        }
        $bit = 1;
        while (($this->lonBits + $this->latBits) % 5 != 0) {
            $this->lonBits += $bit;
            $this->latBits += !$bit;
            $bit = !$bit;
        }
    }

    private function calcError(int $bits, float $floor, float $ceiling): float
    {
        $err = ($ceiling - $floor) / 2;
        while ($bits--)
            $err /= 2;
        return $err;
    }
}