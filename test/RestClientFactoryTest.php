<?php
declare(strict_types = 1);

namespace SoliantTest\SimpleFM\Expressive;

use Http\Client\HttpClient;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Soliant\SimpleFM\Client\Connection;
use Soliant\SimpleFM\Client\RestClient;
use Soliant\SimpleFM\Expressive\RestClientFactory;

final class RestClientFactoryTest extends TestCase
{
    public function testSuccessfulCreation()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'simplefm' => [
                'client' => [
                    'http_client' => 'http_client',
                ],
            ],
        ]);
        $container->get(Connection::class)->shouldBeCalled()->willReturn(
            new Connection('http://foo', 'bar', 'baz', 'bat')
        );

        $container->get('http_client')->shouldBeCalled()->willReturn(
            $this->prophesize(HttpClient::class)->reveal()
        );

        $factory = new RestClientFactory();
        $this->assertInstanceOf(RestClient::class, $factory($container->reveal()));
    }
}
