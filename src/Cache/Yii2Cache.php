<?php

namespace BotMan\BotMan\Cache;

use BotMan\BotMan\Interfaces\CacheInterface;
use Yii;

class Yii2Cache implements CacheInterface
{

    /**
     * Determine if an item exists in the cache.
     *
     * @param  string $key
     * @return bool
     */
    public function has($key)
    {
        return Yii::$app->cache->exists($key);
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = Yii::$app->cache->get($key);
        if ($value === false) {
            return $default;
        }
        return $value;
    }

    /**
     * Retrieve an item from the cache and delete it.
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        $value = $this->get($key, $default);
        Yii::$app->cache->delete($key);
        return $value;
    }

    /**
     * Store an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @param  \DateTime|int $minutes
     * @return void
     */
    public function put($key, $value, $minutes)
    {
        if ($minutes instanceof \Datetime) {
            $seconds = $minutes->getTimestamp() - time();
        } else {
            $seconds = $minutes * 60;
        }
        Yii::$app->cache->set($key, $value, $seconds);
    }
}
