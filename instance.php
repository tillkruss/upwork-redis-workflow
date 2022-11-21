<?php

$redis = new Redis;

$redis->connect(
    getenv('REDIS_HOST'),
    getenv('REDIS_PORT'),
    1.0,
    null,
    100,
    1.0
);

$redis->auth(
    getenv('REDIS_AUTH')
);

$ping = $redis->ping('hello');

var_dump($ping);
