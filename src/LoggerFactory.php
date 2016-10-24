<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use Assert\Assertion;
use Interop\Container\ContainerInterface;
use Zend\Log\Logger;
use Zend\Log\PsrLoggerAdapter;
use Zend\Log\Writer\Stream;

final class IdentityHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        Assertion::isArrayAccessible($config);
        Assertion::keyExists($config, 'simplefm');

        $simpleFmConfig = $config['simplefm'];
        Assertion::isArrayAccessible($simpleFmConfig);
        Assertion::keyExists($simpleFmConfig, 'logger');

        $loggerConfig = $simpleFmConfig['logger'];
        Assertion::isArrayAccessible($loggerConfig);

        Assertion::keyExists('path', $loggerConfig);

        return new PsrLoggerAdapter(
            new Logger([
                'writers' => [
                    new Stream($loggerConfig['path'])
                ],
            ])
        );
    }
}
