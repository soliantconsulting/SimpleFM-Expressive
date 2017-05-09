<?php
declare(strict_types = 1);

namespace SoliantTest\SimpleFM\Expressive;

use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\SimpleFM\Authentication\IdentityHandlerInterface;
use Soliant\SimpleFM\Expressive\IdentityHandlerFactory;

final class IdentityHandlerFactoryTest extends TestCase
{
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
