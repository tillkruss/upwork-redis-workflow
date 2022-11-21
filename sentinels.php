<?php

$sentinels = array_map('trim', explode(',', getenv('REDIS_SENTINELS')));

// if (empty(getenv('REDIS_AUTH'))) {
//     throw new Exception('No cluster password given');
// }

// connect to all sentinels
foreach ($sentinels as $sentinel) {
    [$host, $port] = explode(':', $sentinel);

    $sentinel = new RedisSentinel('127.0.0.1', 26379, 0, NULL, 0, 0, "secret"); // connect sentinel with password authentication

    // connect to sentinel
    $sentinel = new RedisSentinel(
        $host,
        $port,
        1.0,
        null,
        100,
        1.0
        getenv('REDIS_AUTH')
    );

    var_dump(
        "{$host}:{$port}",
        $sentinel->ping()
    );

    // get sentinel primary
    $primary = $sentinel->getMasterAddrByName(
        getenv('REDIS_SERVICE')
    );

    if (! $primary) {
        throw new Exception("Failed to retrieve sentinel primary of `{$sentinel}`");
    }

    var_dump($primary);

    // get sentinel replicas
    $replicas = $sentinel->slaves(
        getenv('REDIS_SERVICE')
    );

    if (! $replicas) {
        throw new Exception("Failed to discover Sentinel replicas of `{$sentinel}`");
    }

    foreach ($replicas as $replica) {
        var_dump($replica);
    }

    // connect to sentinel primary
    $redis = new Redis;

    $redis->connect(
        $primary[0],
        $primary[1],
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
}
