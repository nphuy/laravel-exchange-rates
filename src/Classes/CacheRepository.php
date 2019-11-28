<?php

namespace AshAllenDesign\LaravelExchangeRates\Classes;

use Carbon\Carbon;
use Illuminate\Cache\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;

class CacheRepository
{
    /**
     * @var Repository
     */
    protected $cache;

    /**
     * Cache constructor.
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $cache = Container::getInstance()->make('cache');
        $config = Container::getInstance()->make('config')->get('cache.default');

        $this->cache = $cache->store($config);
    }

    public function forget(string $key): self
    {
        if ($key) {
            $this->cache->forget($key);
        }

        return $this;
    }

    public function storeInCache(string $key, $value)
    {
        return $this->cache->forever($key, $value);
    }

    public function getFromCache(string $key)
    {
        return $this->cache->get($key);
    }

    public function existsInCache(string $key): bool
    {
        return $this->cache->has($key);
    }

    public function buildCacheKey(string $from, string $to, Carbon $date, Carbon $endDate = null): string
    {
        $key = $from.'_'.$to.'_'.$date->format('Y-m-d');

        if ($endDate) {
            $key .= '_'.$endDate->format('Y-m-d');
        }

        return $key;
    }
}