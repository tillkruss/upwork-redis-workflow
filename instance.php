<?php

$redis = new Redis;

$redis->connect(
    getenv('REDIS_HOST'),
    getenv('REDIS_PORT')
);

$redis->auth(
    getenv('REDIS_AUTH')
);

$ping = $redis->ping('hello');

var_dump($ping);
