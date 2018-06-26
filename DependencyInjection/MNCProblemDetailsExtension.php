<?php

namespace MNC\Bundle\ProblemDetailsBundle\DependencyInjection;

use MNC\Bundle\ProblemDetailsBundle\EventSubscriber\ApiExceptionSubscriber;
use MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer\AuthExceptionNormalizer;
use MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer\ChainExceptionNormalizer;
use MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer\DriverExceptionNormalizer;
use MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer\FormValidationExceptionNormalizer;
use MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer\HttpExceptionNormalizer;
use MNC\Bundle\ProblemDetailsBundle\ExceptionNormalizer\PhpExceptionNormalizer;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class MNCProblemDetailsExtension extends Extension
{
    const DOCTRINE = 'Doctrine\DBAL\Exception\DriverException';
    const SYMFONY_AUTH = 'Symfony\Component\Security\Core\Exception\AuthenticationException';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $subscriberDef = $container->getDefinition(ApiExceptionSubscriber::class);
        $subscriberDef->addArgument($config['exception_normalizer']['normalize_in_dev']);

        // Normalizers configuration
        $references = [];

        if ($config['exception_normalizer']['normalizers']['form'] === true) {
            $references[] = new Reference(FormValidationExceptionNormalizer::class);
        }

        if ($config['exception_normalizer']['normalizers']['symfony_http'] === true) {
            $references[] = new Reference(HttpExceptionNormalizer::class);
        }

        if ($config['exception_normalizer']['normalizers']['symfony_auth'] === true) {
            if (!class_exists(self::SYMFONY_AUTH)) {
                throw new LogicException(sprintf('Cannot normalize Symfony Auth exceptions because class %s does not exist. You must install symfony/security-bundle.', self::SYMFONY_AUTH));
            }
            $references[] = new Reference(AuthExceptionNormalizer::class);
        }

        if ($config['exception_normalizer']['normalizers']['doctrine'] === true) {
            if (!class_exists(self::DOCTRINE)) {
                throw new LogicException(sprintf('Cannot normalize Doctrine Exceptions because class %s does not exist. You must install doctrine/doctrine-bundle.', self::DOCTRINE));
            }
            $references[] = new Reference(DriverExceptionNormalizer::class);
        }

        if ($config['exception_normalizer']['normalizers']['php'] === true) {
            $references[] = new Reference(PhpExceptionNormalizer::class);
        }

        $chainNormalizerDef = $container->getDefinition(ChainExceptionNormalizer::class);
        $chainNormalizerDef->addArgument($references);
    }
}
