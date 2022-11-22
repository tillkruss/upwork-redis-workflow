<?php

$nodes = array_map('trim', explode(',', getenv('REDIS_PRIMARIES')));

if (empty(getenv('REDIS_AUTH'))) {
    throw new Exception('No cluster password given');
}

print 'nodes are';
print $nodes[0];
print $nodes[1];
print $nodes[2];

$redis = new RedisCluster(
    null,
    $nodes,
    1.0,
    1.0,
    false,
    getenv('REDIS_AUTH')
);

// list and ping all masters
foreach ($redis->_masters() as $master) {
    var_dump(
        $master,
        $redis->ping($master)
    );
}

// write to 20 hash slots
foreach (range(1, 20) as $i) {
    $redis->set("prefix:{{$i}}:foo", bin2hex(random_bytes(4)));
}
