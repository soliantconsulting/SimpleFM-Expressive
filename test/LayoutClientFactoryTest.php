<?php
declare(strict_types = 1);

namespace SoliantTest\SimpleFM\Expressive;

use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\SimpleFM\Client\Layout\LayoutClientInterface;
use Soliant\SimpleFM\Connection\ConnectionInterface;
use Soliant\SimpleFM\Expressive\LayoutClientFactory;

final class LayoutClientFactoryTest extends TestCase
{
    public function testSuccessfulCreation()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get(ConnectionInterface::class)->shouldBeCalled()->willReturn(
            $this->prophesize(ConnectionInterface::class)->reveal()
        );

        $factory = new LayoutClientFactory();
        $this->assertInstanceOf(LayoutClientInterface::class, $factory($container->reveal()));
    }
}
