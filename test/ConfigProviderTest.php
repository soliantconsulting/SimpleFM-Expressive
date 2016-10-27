<?php
declare(strict_types = 1);

namespace SoliantTest\SimpleFM\Expressive;

use Http\Client\HttpClient;
use PHPUnit_Framework_TestCase as TestCase;
use Soliant\SimpleFM\Authentication\Authenticator;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClientInterface;
use Soliant\SimpleFM\Connection\ConnectionInterface;
use Soliant\SimpleFM\Expressive\ConfigProvider;
use Soliant\SimpleFM\Repository\Builder\RepositoryBuilderInterface;

final class ConfigProviderTest extends TestCase
{
    public function testDependenciesSectionExists()
    {
        $config = (new ConfigProvider())();
        $this->assertInternalType('array', $config);

        $this->assertArrayHasKey('dependencies', $config);
        $this->assertInternalType('array', $config['dependencies']);
    }

    public function testRegisteredFactories()
    {
        $config = (new ConfigProvider())();

        $this->assertArrayHasKey('factories', $config['dependencies']);
        $this->assertInternalType('array', $config['dependencies']['factories']);
        $factoryConfig = $config['dependencies']['factories'];

        $this->assertValidFactory(Authenticator::class, $factoryConfig);
        $this->assertValidFactory(ConnectionInterface::class, $factoryConfig);
        $this->assertValidFactory(RepositoryBuilderInterface::class, $factoryConfig);
        $this->assertValidFactory(ResultSetClientInterface::class, $factoryConfig);
        $this->assertValidFactory('soliant.simplefm.expressive.identity-handler', $factoryConfig);
        $this->assertValidFactory('soliant.simplefm.expressive.logger', $factoryConfig);
    }

    public function testRegisteredInvokables()
    {
        $config = (new ConfigProvider())();

        $this->assertArrayHasKey('invokables', $config['dependencies']);
        $this->assertInternalType('array', $config['dependencies']['invokables']);
        $invokableConfig = $config['dependencies']['invokables'];

        $this->assertArrayHasKey('soliant.simplefm.expressive.http-client', $invokableConfig);
        $this->assertTrue(class_exists($invokableConfig['soliant.simplefm.expressive.http-client']));
        $this->assertTrue(is_subclass_of(
            $invokableConfig['soliant.simplefm.expressive.http-client'],
            HttpClient::class
        ));
    }

    private function assertValidFactory(string $key, array $factoryConfig)
    {
        $this->assertArrayHasKey($key, $factoryConfig);
        $this->assertTrue(class_exists($factoryConfig[$key]));
        $this->assertTrue(is_callable(new $factoryConfig[$key]()));
    }
}
