<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use Assert\Assertion;
use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Zend\Log\Logger;
use Zend\Log\PsrLoggerAdapter;
use Zend\Log\Writer\Stream;

final class LoggerFactory
{
    public function __invoke(ContainerInterface $container) : LoggerInterface
    {
        $config = $container->get('config');
        Assertion::isArrayAccessible($config);
        Assertion::keyIsset($config, 'simplefm');

        $simpleFmConfig = $config['simplefm'];
        Assertion::isArrayAccessible($simpleFmConfig);
        Assertion::keyIsset($simpleFmConfig, 'logger');

        $loggerConfig = $simpleFmConfig['logger'];
        Assertion::isArrayAccessible($loggerConfig);

        Assertion::keyIsset($loggerConfig, 'path');

        $logger = new Logger();
        $logger->addWriter(new Stream($loggerConfig['path']));

        return new PsrLoggerAdapter($logger);
    }
}
