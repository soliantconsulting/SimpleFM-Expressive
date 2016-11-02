<?php
declare(strict_types = 1);

namespace SoliantTest\SimpleFM\Expressive;

use ArrayObject;
use Assert\InvalidArgumentException;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClientInterface;
use Soliant\SimpleFM\Connection\ConnectionInterface;
use Soliant\SimpleFM\Expressive\ResultSetClientFactory;

final class ResultSetClientFactoryTest extends TestCase
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
                'result_set_client',
            ],
            [
                [
                    'simplefm' => [
                        'result_set_client' => [],
                    ],
                ],
                'time_zone',
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

        $factory = new ResultSetClientFactory();
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

        $factory = new ResultSetClientFactory();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessageContent);
        $factory($container->reveal());
    }

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
