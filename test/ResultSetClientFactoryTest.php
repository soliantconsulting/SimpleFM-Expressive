<?php
declare(strict_types = 1);

namespace SoliantTest\SimpleFM\Expressive;

use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClientInterface;
use Soliant\SimpleFM\Connection\ConnectionInterface;
use Soliant\SimpleFM\Expressive\ResultSetClientFactory;

final class ResultSetClientFactoryTest extends TestCase
{
    public function testSuccessfulCreation()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'simplefm' => [
                'result_set_client' => [
                    'time_zone' => 'UTC',
                ],
            ],
        ]);
        $container->get(ConnectionInterface::class)->shouldBeCalled()->willReturn(
            $this->prophesize(ConnectionInterface::class)->reveal()
        );

        $factory = new ResultSetClientFactory();
        $this->assertInstanceOf(ResultSetClientInterface::class, $factory($container->reveal()));
    }
}
