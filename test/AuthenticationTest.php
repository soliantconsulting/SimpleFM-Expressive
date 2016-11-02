<?php
declare(strict_types = 1);

namespace SoliantTest\SimpleFM\Expressive;

use ArrayObject;
use Assert\InvalidArgumentException;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\SimpleFM\Authentication\Authenticator;
use Soliant\SimpleFM\Authentication\IdentityHandlerInterface;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClientInterface;
use Soliant\SimpleFM\Expressive\AuthenticatorFactory;

final class AuthenticatorFactoryTest extends TestCase
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
                'authenticator',
            ],
            [
                [
                    'simplefm' => [
                        'authenticator' => [],
                    ],
                ],
                'identity_layout',
            ],
            [
                [
                    'simplefm' => [
                        'authenticator' => [
                            'identity_layout' => '',
                        ],
                    ],
                ],
                'username_field',
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

        $factory = new AuthenticatorFactory();
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

        $factory = new AuthenticatorFactory();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessageContent);
        $factory($container->reveal());
    }

    public function properConfigProvider() : array
    {
        return [
            [
                [
                    'simplefm' => [
                        'authenticator' => [
                            'identity_layout' => 'identity_layout',
                            'username_field' => 'username_field',
                        ],
                    ],
                ],
                'soliant.simplefm.expressive.identity-handler',
            ],
            [
                [
                    'simplefm' => [
                        'authenticator' => [
                            'identity_handler' => 'identity_handler',
                            'identity_layout' => 'identity_layout',
                            'username_field' => 'username_field',
                        ],
                    ],
                ],
                'identity_handler'
            ],
        ];
    }

    /**
     * @dataProvider properConfigProvider
     */
    public function testSuccessfulCreation(array $config, string $identityHandlerKey)
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($config);
        $container->get(ResultSetClientInterface::class)->shouldBeCalled()->willReturn(
            $this->prophesize(ResultSetClientInterface::class)->reveal()
        );
        $container->get($identityHandlerKey)->shouldBeCalled()->willReturn(
            $this->prophesize(IdentityHandlerInterface::class)->reveal()
        );

        $factory = new AuthenticatorFactory();
        $this->assertInstanceOf(Authenticator::class, $factory($container->reveal()));
    }
}
