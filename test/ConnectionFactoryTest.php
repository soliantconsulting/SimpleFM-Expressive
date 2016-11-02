<?php
declare(strict_types = 1);

namespace SoliantTest\SimpleFM\Expressive;

use ArrayObject;
use Assert\InvalidArgumentException;
use Http\Client\HttpClient;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Log\LoggerInterface;
use Soliant\SimpleFM\Authentication\IdentityHandlerInterface;
use Soliant\SimpleFM\Connection\ConnectionInterface;
use Soliant\SimpleFM\Expressive\ConnectionFactory;

final class ConnectionFactoryTest extends TestCase
{
    public static function missingConfigProvider() : array
    {
        return [
            [
                [],
                'simplefm',
            ],
            [
                [
                    'simplefm' => [],
                ],
                'connection',
            ],
            [
                [
                    'simplefm' => [
                        'connection' => [],
                    ],
                ],
                'uri',
            ],
            [
                [
                    'simplefm' => [
                        'connection' => [
                            'uri' => 'uri',
                        ],
                    ],
                ],
                'database',
            ],
            [
                [
                    'simplefm' => [
                        'connection' => [
                            'uri' => 'uri',
                            'database' => 'database',
                        ],
                    ],
                ],
                'http_client',
            ],
        ];
    }

    /**
     * @dataProvider missingConfigProvider
     */
    public function testMissingConfigWithArray(array $config, string $exceptionMessageContent)
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);

        $factory = new ConnectionFactory();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessageContent);
        $factory($container->reveal());
    }

    /**
     * @dataProvider missingConfigProvider
     */
    public function testMissingConfigWithArrayObject(array $config, string $exceptionMessageContent)
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn(new ArrayObject($config));

        $factory = new ConnectionFactory();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessageContent);
        $factory($container->reveal());
    }

    public function testSuccessfulCreationWithoutOptionalConfig()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'simplefm' => [
                'connection' => [
                    'uri' => 'uri',
                    'database' => 'database',
                    'http_client' => 'http_client',
                ],
            ],
        ]);
        $container->get('http_client')->shouldBeCalled()->willReturn(
            $this->prophesize(HttpClient::class)->reveal()
        );

        $factory = new ConnectionFactory();
        $this->assertInstanceOf(ConnectionInterface::class, $factory($container->reveal()));
    }

    public function testSuccessfulCreationWithIdentityHandler()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'simplefm' => [
                'connection' => [
                    'uri' => 'uri',
                    'database' => 'database',
                    'http_client' => 'http_client',
                    'identity_handler' => 'identity_handler',
                ],
            ],
        ]);
        $container->get('http_client')->willReturn(
            $this->prophesize(HttpClient::class)->reveal()
        );
        $container->get('identity_handler')->shouldBeCalled()->willReturn(
            $this->prophesize(IdentityHandlerInterface::class)->reveal()
        );

        $factory = new ConnectionFactory();
        $this->assertInstanceOf(ConnectionInterface::class, $factory($container->reveal()));
    }

    public function testSuccessfulCreationWithLogger()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'simplefm' => [
                'connection' => [
                    'uri' => 'uri',
                    'database' => 'database',
                    'http_client' => 'http_client',
                    'logger' => 'logger',
                ],
            ],
        ]);
        $container->get('http_client')->willReturn(
            $this->prophesize(HttpClient::class)->reveal()
        );
        $container->get('logger')->shouldBeCalled()->willReturn(
            $this->prophesize(LoggerInterface::class)->reveal()
        );

        $factory = new ConnectionFactory();
        $this->assertInstanceOf(ConnectionInterface::class, $factory($container->reveal()));
    }
}
