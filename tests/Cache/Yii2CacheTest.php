<?php

namespace BotMan\BotMan\Tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use BotMan\BotMan\Cache\Yii2Cache;
use Doctrine\Common\Cache\CacheProvider;

class Yii2CacheTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function has()
    {
        $driver = m::mock(CacheProvider::class);
        $driver->shouldReceive('contains')->once()->andReturn(true);

        $cache = new Yii2Cache($driver);
        $this->assertTrue($cache->has('foo'));
    }

    /** @test */
    public function has_not()
    {
        $driver = m::mock(CacheProvider::class);
        $driver->shouldReceive('contains')->once()->andReturn(false);

        $cache = new Yii2Cache($driver);
        $this->assertFalse($cache->has('foo'));
    }

    /** @test */
    public function get_existing_key()
    {
        $driver = m::mock(CacheProvider::class);
        $driver->shouldReceive('fetch')->once()->andReturn('bar');

        $cache = new Yii2Cache($driver);
        $this->assertEquals('bar', $cache->get('foo', null));
    }

    /** @test */
    public function get_non_existing_key()
    {
        $driver = m::mock(CacheProvider::class);
        $driver->shouldReceive('fetch')->once()->andReturn(false);

        $cache = new Yii2Cache($driver);
        $this->assertNull($cache->get('foo', null));
    }

    /** @test */
    public function pull_existing_key()
    {
        $driver = m::mock(CacheProvider::class);
        $driver->shouldReceive('contains')->once()->andReturn(true);
        $driver->shouldReceive('fetch')->once()->andReturn('bar');
        $driver->shouldReceive('delete')->once();

        $cache = new Yii2Cache($driver);
        $this->assertEquals('bar', $cache->pull('foo', null));
    }

    /** @test */
    public function pull_non_existing_key()
    {
        $driver = m::mock(CacheProvider::class);
        $driver->shouldReceive('contains')->once()->andReturn(false);

        $cache = new Yii2Cache($driver);
        $this->assertNull($cache->pull('foo', null));
    }

    /** @test */
    public function put()
    {
        $driver = m::mock(CacheProvider::class);
        $driver->shouldReceive('save')->once()->withArgs(['foo', 'bar', 300]);

        $cache = new Yii2Cache($driver);
        $cache->put('foo', 'bar', 5);
    }

    /** @test */
    public function put_with_datetime()
    {
        $driver = m::mock(CacheProvider::class);
        $driver->shouldReceive('save')->once()->withArgs(['foo', 'bar', 300]);

        $cache = new Yii2Cache($driver);
        $cache->put('foo', 'bar', new \DateTime('+5 minutes'));
    }

    /** @test */
    public function pull_non_existing_key_with_default_value()
    {
        $driver = m::mock(CacheProvider::class);
        $driver->shouldReceive('contains')->once()->andReturn(false);

        $cache = new Yii2Cache($driver);
        $this->assertEquals('bar', $cache->pull('foo', 'bar'));
    }
}
