<?php
declare(strict_types = 1);

namespace SoliantTest\SimpleFM\Expressive;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Soliant\SimpleFM\Client\Connection;
use Soliant\SimpleFM\Expressive\ConnectionFactory;

final class ConnectionFactoryTest extends TestCase
{
    public function testSuccessfulCreation()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'simplefm' => [
                'connection' => [
                    'base_uri' => 'http://foo',
                    'username' => 'username',
                    'password' => 'password',
                    'database' => 'database',
                    'time_zone' => 'Europe/Berlin',
                ],
            ],
        ]);

        $factory = new ConnectionFactory();
        $this->assertInstanceOf(Connection::class, $factory($container->reveal()));
    }
}
