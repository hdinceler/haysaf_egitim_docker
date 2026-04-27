<?php 
//REdis tüm işlemler class
// class/RedisClient.php
class RedisClient
{
    private \Redis $redis;

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect(REDIS_HOST, REDIS_PORT);
        if (REDIS_PASSWORD !== null) {
            $this->redis->auth(REDIS_PASSWORD);
        }
    }

    public function set(string $key, $value, int $expiration = 3600): bool
    {
        return $this->redis->set($key, serialize($value), $expiration);
    }

    public function get(string $key)
    {
        $value = $this->redis->get($key);
        return $value ? unserialize($value) : null;
    }

    public function delete(string $key): bool
    {
        return $this->redis->del($key) > 0;
    }

    public function exists(string $key): bool
    {
        return $this->redis->exists($key);
    }
    public function remember(string $key, callable $callback, int $expiration = 3600)
    {
        $value = $this->get($key);
        if ($value !== null) {
            return $value;
        }
        $value = $callback();
        $this->set($key, $value, $expiration);
        return $value;
    }
    public function incr(string $key): int
    {
        return $this->redis->incr($key);
    }

    public function expire(string $key, int $ttl): bool
    {
        return $this->redis->expire($key, $ttl);
    }
    
}