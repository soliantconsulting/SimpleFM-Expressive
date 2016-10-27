<?php
declare(strict_types = 1);

namespace SoliantTest\SimpleFM\Expressive;

use Assert\InvalidArgumentException;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\SimpleFM\Authentication\IdentityHandlerInterface;
use Soliant\SimpleFM\Expressive\IdentityHandlerFactory;

final class IdentityHandlerFactoryTest extends TestCase
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
                'identity_handler',
            ],
            [
                [
                    'simplefm' => [
                        'identity_handler' => [],
                    ],
                ],
                'key',
            ],
        ];
    }

    /**
     * @dataProvider missingConfigProvider
     */
    public function testMissingConfig(array $config, string $exceptionMessageContent)
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);

        $factory = new IdentityHandlerFactory();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessageContent);
        $factory($container->reveal());
    }

    public function testSuccessfulCreation()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'simplefm' => [
                'identity_handler' => [
                    'key' => 'key',
                ],
            ],
        ]);

        $factory = new IdentityHandlerFactory();
        $this->assertInstanceOf(IdentityHandlerInterface::class, $factory($container->reveal()));
    }
}
