<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use DASPRiD\TreeReader\TreeReader;
use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Zend\Log\Logger;
use Zend\Log\PsrLoggerAdapter;
use Zend\Log\Writer\Stream;

final class LoggerFactory
{
    public function __invoke(ContainerInterface $container) : LoggerInterface
    {
        $config = (new TreeReader($container->get('config'), 'config'))
            ->getChildren('simplefm')
            ->getChildren('logger')
        ;

        $logger = new Logger();
        $logger->addWriter(new Stream($config->getString('path')));

        return new PsrLoggerAdapter($logger);
    }
}
