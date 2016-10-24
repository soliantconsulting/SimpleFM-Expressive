<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use Assert\Assertion;
use Interop\Container\ContainerInterface;
use Soliant\SimpleFM\Authentication\BlockCipherIdentityHandler;
use Zend\Crypt\BlockCipher;

final class IdentityHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        Assertion::isArrayAccessible($config);
        Assertion::keyExists($config, 'simplefm');

        $simpleFmConfig = $config['simplefm'];
        Assertion::isArrayAccessible($simpleFmConfig);
        Assertion::keyExists($simpleFmConfig, 'identity_handler');

        $identityHandlerConfig = $simpleFmConfig['identity_handler'];
        Assertion::isArrayAccessible($identityHandlerConfig);

        Assertion::keyExists('key', $identityHandlerConfig);

        return new BlockCipherIdentityHandler(
            BlockCipher::factory('openssl', ['algo' => 'aes'])->setKey($identityHandlerConfig['key'])
        );
    }
}
