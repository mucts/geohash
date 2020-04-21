# GeoHash

GeoHash LBS地理位置距离计算方法geohash，将一个经纬度信息，转换成一个可以排序，可以比较的字符串编码，用于高效搜索

### 安装方法 ###

```php
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

