<?php
declare(strict_types = 1);

namespace SoliantTest\SimpleFM\Expressive;

use DASPRiD\TreeReader\Exception\UnexpectedTypeException;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClientInterface;
use Soliant\SimpleFM\Expressive\RepositoryBuilderFactory;
use Soliant\SimpleFM\Repository\Builder\RepositoryBuilderInterface;
use Soliant\SimpleFM\Repository\Builder\Type\TypeInterface;

final class RepositoryBuilderFactoryTest extends TestCase
{
    public function testNonTraversableAdditionalTypes()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'simplefm' => [
                'repository_builder' => [
                    'xml_folder' => 'xml_folder',
                    'additional_types' => 'additional_types',
                ],
            ],
        ]);

        $factory = new RepositoryBuilderFactory();
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('is of type string, but array was expected');
        $factory($container->reveal());
    }

    public function testProperAdditionalTypes()
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
        $container->get(ResultSetClientInterface::class)->willReturn(
            $this->prophesize(ResultSetClientInterface::class)->reveal()
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
        $container->get(ResultSetClientInterface::class)->shouldBeCalled()->willReturn(
            $this->prophesize(ResultSetClientInterface::class)->reveal()
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
        $container->get(ResultSetClientInterface::class)->shouldBeCalled()->willReturn(
            $this->prophesize(ResultSetClientInterface::class)->reveal()
        );

        $factory = new RepositoryBuilderFactory();
        $this->assertInstanceOf(RepositoryBuilderInterface::class, $factory($container->reveal()));
    }
}
