<?php
declare(strict_types = 1);

namespace SoliantTest\SimpleFM\Expressive;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Soliant\SimpleFM\Client\ClientInterface;
use Soliant\SimpleFM\Expressive\RepositoryBuilderFactory;
use Soliant\SimpleFM\Repository\Builder\RepositoryBuilderInterface;
use Soliant\SimpleFM\Repository\Builder\Type\TypeInterface;

final class RepositoryBuilderFactoryTest extends TestCase
{
    public function testAdditionalTypes()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'simplefm' => [
                'repository_builder' => [
                    'xml_folder' => 'xml_folder',
                    'additional_types' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
        ]);
        $container->get('bar')->shouldBeCalled()->willReturn($this->prophesize(TypeInterface::class)->reveal());
        $container->get(ClientInterface::class)->willReturn(
            $this->prophesize(ClientInterface::class)->reveal()
        );

        $factory = new RepositoryBuilderFactory();
        $factory($container->reveal());
    }

    public function testSuccessfulCreationWithoutProxyFolder()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'simplefm' => [
                'repository_builder' => [
                    'xml_folder' => 'xml_folder',
                ],
            ],
        ]);
        $container->get(ClientInterface::class)->shouldBeCalled()->willReturn(
            $this->prophesize(ClientInterface::class)->reveal()
        );

        $factory = new RepositoryBuilderFactory();
        $this->assertInstanceOf(RepositoryBuilderInterface::class, $factory($container->reveal()));
    }

    public function testSuccessfulCreationWithProxyFolder()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'simplefm' => [
                'repository_builder' => [
                    'xml_folder' => 'xml_folder',
                    'proxy_folder' => 'proxy_folder',
                ],
            ],
        ]);
        $container->get(ClientInterface::class)->shouldBeCalled()->willReturn(
            $this->prophesize(ClientInterface::class)->reveal()
        );

        $factory = new RepositoryBuilderFactory();
        $this->assertInstanceOf(RepositoryBuilderInterface::class, $factory($container->reveal()));
    }
}
