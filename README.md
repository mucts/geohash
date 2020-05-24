<p align="center"><img src="https://images.mucts.com/image/exp_def_white.png" width="400"></p>
<p align="center">
    <a href="https://scrutinizer-ci.com/g/mucts/geohash"><img src="https://scrutinizer-ci.com/g/mucts/geohash/badges/build.png" alt="Build Status"></a>
    <a href="https://scrutinizer-ci.com/g/mucts/geohash"><img src="https://scrutinizer-ci.com/g/mucts/geohash/badges/code-intelligence.svg" alt="Code Intelligence Status"></a>
    <a href="https://scrutinizer-ci.com/g/mucts/geohash"><img src="https://scrutinizer-ci.com/g/mucts/geohash/badges/quality-score.png" alt="Scrutinizer Code Quality"></a>
    <a href="https://packagist.org/packages/mucts/geohash"><img src="https://poser.pugx.org/mucts/geohash/d/total.svg" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/mucts/geohash"><img src="https://poser.pugx.org/mucts/geohash/v/stable.svg" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/mucts/geohash"><img src="https://poser.pugx.org/mucts/geohash/license.svg" alt="License"></a>
</p>

# GeoHash

GeoHash LBS地理位置距离计算方法geohash，将一个经纬度信息，转换成一个可以排序，可以比较的字符串编码，用于高效搜索


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

