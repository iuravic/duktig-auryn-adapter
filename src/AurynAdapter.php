<?php
namespace Duktig\DI\Adapter\Auryn;

use Duktig\Core\DI\ContainerInterface;
use Elazar\Auryn\Container;

class AurynAdapter implements ContainerInterface
{
    protected $container;
    
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritDoc}
     * @see \Psr\Container\ContainerInterface::has()
     */
    public function has($id)
    {
        return $this->container->has($id);
    }
    
    /**
     * {@inheritDoc}
     * @see \Psr\Container\ContainerInterface::get()
     */
    public function get($id)
    {
        return $this->container->get($id);
    }
    
    /**
     * {@inheritDoc}
     * @see \Duktig\Core\DI\ContainerInterface::alias()
     */
    public function alias($original, $alias) : void
    {
        $this->container->alias($original, $alias);
    }
    
    /**
     * {@inheritDoc}
     * @see \Duktig\Core\DI\ContainerInterface::factory()
     */
    public function factory($name, $callableOrMethodStr) : void
    {
        $this->container->delegate($name, $callableOrMethodStr);
    }
    
    /**
     * {@inheritDoc}
     * @see \Duktig\Core\DI\ContainerInterface::singleton()
     */
    public function singleton($nameOrInstance) : void
    {
        $this->container->share($nameOrInstance);
    }
    
    /**
     * {@inheritDoc}
     * @see \Duktig\Core\DI\ContainerInterface::resolveClosure()
     */
    public function resolveClosure(\Closure $closureUnresolved)
    {
        $serviceId = $this->getRandomServiceName();
        $this->container->delegate($serviceId, $closureUnresolved);
        return $this->container->get($serviceId);
    }
    
    /**
     * Generates a unique name for a service.
     * 
     * @return string
     */
    private function getRandomServiceName() : string
    {
        return 'injectedService'.uniqid();
    }
}