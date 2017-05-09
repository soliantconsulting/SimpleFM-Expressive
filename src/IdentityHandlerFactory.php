<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use DASPRiD\TreeReader\TreeReader;
use Interop\Container\ContainerInterface;
use Soliant\SimpleFM\Authentication\BlockCipherIdentityHandler;
use Soliant\SimpleFM\Authentication\IdentityHandlerInterface;
use Zend\Crypt\BlockCipher;

final class IdentityHandlerFactory
{
    public function __invoke(ContainerInterface $container) : IdentityHandlerInterface
    {
        $config = (new TreeReader($container->get('config'), 'config'))
            ->getChildren('simplefm')
            ->getChildren('identity_handler')
        ;

        return new BlockCipherIdentityHandler(
            BlockCipher::factory('openssl', ['algo' => 'aes'])->setKey($config->getString('key'))
        );
    }
}
