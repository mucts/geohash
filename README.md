# GeoHash

GeoHash LBS地理位置距离计算方法geohash，将一个经纬度信息，转换成一个可以排序，可以比较的字符串编码，用于高效搜索

[![Build Status](https://scrutinizer-ci.com/g/mucts/geohash/badges/build.png)](https://scrutinizer-ci.com/g/mucts/geohash)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/mucts/geohash/badges/code-intelligence.svg)](https://scrutinizer-ci.com/g/mucts/geohash)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mucts/geohash/badges/quality-score.png)](https://scrutinizer-ci.com/g/mucts/geohash)
[![Latest Stable Version](https://poser.pugx.org/mucts/geohash/v/stable.svg)](https://packagist.org/packages/mucts/geohash) 
[![Total Downloads](https://poser.pugx.org/mucts/geohash/downloads.svg)](https://packagist.org/packages/mucts/geohash) 
[![Latest Unstable Version](https://poser.pugx.org/mucts/geohash/v/unstable.svg)](https://packagist.org/packages/mucts/geohash) 
[![License](https://poser.pugx.org/mucts/geohash/license.svg)](https://packagist.org/packages/mucts/geohash)


### 安装方法 ###

```shell
composer require mucts/geohash
```

### 使用方法 ###


```php
<?php
    // 参数：纬度，经度，长度（可选，默认为最长）
    $geo = geo_hash_encode("69.3252", "136.2345", 9);
    echo $geo;
        
    list($lat, $lng) = geo_hash_decode($geo);
    echo $lat, ', ', $lng;

```

