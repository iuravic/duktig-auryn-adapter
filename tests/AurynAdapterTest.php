<?php
namespace Duktig\DI\Adapter\Auryn;

use PHPUnit\Framework\TestCase;
use Elazar\Auryn\Container;

class AurynAdapterTest extends TestCase
{
    public function tearDown()
    {
        parent::tearDown();
        if ($container = \Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        \Mockery::close();
    }
    
    public function testHas()
    {
        $mockContainer = \Mockery::mock(Container::class);
        $mockContainer->shouldReceive('has')->once()->with('serviceID');
        $adapter = new AurynAdapter($mockContainer);
        $adapter->has('serviceID');
    }
    
    public function testGet()
    {
        $mockContainer = \Mockery::mock(Container::class);
        $mockContainer->shouldReceive('get')->once()->with('serviceID');
        $adapter = new AurynAdapter($mockContainer);
        $adapter->get('serviceID');
    }
    
    public function testAlias()
    {
        $mockContainer = \Mockery::mock(Container::class);
        $factory = function() { return new \stdClass(); };
        $mockContainer->shouldReceive('alias')->once()->with('serviceID', $factory);
        $adapter = new AurynAdapter($mockContainer);
        $adapter->alias('serviceID', $factory);
    }
    
    public function testSingleton()
    {
        $mockContainer = \Mockery::mock(Container::class);
        $mockContainer->shouldReceive('share')->once()->with('serviceID');
        $adapter = new AurynAdapter($mockContainer);
        $adapter->singleton('serviceID');
    }
    
    public function testFactory()
    {
        $mockContainer = \Mockery::mock(Container::class);
        $mockContainer->shouldReceive('delegate')->once()->with('ClassName', 'serviceID');
        $adapter = new AurynAdapter($mockContainer);
        $adapter->factory('ClassName', 'serviceID');
    }

    public function testResolvesClosure()
    {
        $unresolvedClosure = function(SomeDependency $param) {};

        $mockContainer = \Mockery::mock(Container::class);
        $mockContainer->shouldReceive('delegate')
            ->once()
            ->withArgs(function ($argRandomServiceName, $argUnresolvedClosure)
                use ($unresolvedClosure) {
                    // ignore response from private method
                    return $argUnresolvedClosure == $unresolvedClosure ? true : false;
                });
        $mockContainer->shouldReceive('get')
            ->once();

        $adapter = new AurynAdapter($mockContainer);
        $adapter->resolveClosure($unresolvedClosure);
    }
}