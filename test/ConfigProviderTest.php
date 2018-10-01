<?php
declare(strict_types = 1);

namespace SoliantTest\SimpleFM\Expressive;

use PHPUnit\Framework\TestCase;
use Soliant\SimpleFM\Client\ClientInterface;
use Soliant\SimpleFM\Client\Connection;
use Soliant\SimpleFM\Expressive\ConfigProvider;
use Soliant\SimpleFM\Repository\Builder\RepositoryBuilderInterface;

final class ConfigProviderTest extends TestCase
{
    public function testDependenciesSectionExists()
    {
        $config = (new ConfigProvider())->__invoke();
        $this->assertInternalType('array', $config);

        $this->assertArrayHasKey('dependencies', $config);
        $this->assertInternalType('array', $config['dependencies']);
    }

    public function testRegisteredFactories()
    {
        $config = (new ConfigProvider())->__invoke();

        $this->assertArrayHasKey('factories', $config['dependencies']);
        $this->assertInternalType('array', $config['dependencies']['factories']);
        $factoryConfig = $config['dependencies']['factories'];

        $this->assertValidFactory(Connection::class, $factoryConfig);
        $this->assertValidFactory(RepositoryBuilderInterface::class, $factoryConfig);
        $this->assertValidFactory(ClientInterface::class, $factoryConfig);
    }

    private function assertValidFactory(string $key, array $factoryConfig)
    {
        $this->assertArrayHasKey($key, $factoryConfig);
        $this->assertTrue(class_exists($factoryConfig[$key]));
        $this->assertTrue(is_callable(new $factoryConfig[$key]()));
    }
}
