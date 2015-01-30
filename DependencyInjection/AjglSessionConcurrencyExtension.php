<?php

/*
 * This file is part of the AJGL packages
 *
 * Copyright (C) Antonio J. García Lagar <aj@garcialagar.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajgl\Bundle\SessionConcurrencyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Antonio J. García Lagar <aj@garcialagar.es>
 */
class AjglSessionConcurrencyExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (isset($config['firewalls'])) {
            $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('session_concurrency.xml');
            $loader->load('security_listener.xml');

            $container->setAlias('security.session_registry.storage', $config['registry_storage_id']);

            $firewalls = array();
            foreach ($config['firewalls'] as $id => $sessionConcurrencyConfig) {
                $sessionAuthenticationStrategyId = $this->createConcurrentSessionAuthenticationStrategy($container, $id, $sessionConcurrencyConfig);
                $firewalls[$id] = $sessionAuthenticationStrategyId;
            }
            $container->setParameter('ajgl.security.authentication.session_concurrency_firewalls', $firewalls);
        }
    }

    private function createConcurrentSessionAuthenticationStrategy($container, $id, $config)
    {
        $authenticationStrategies = array();
        $sessionStrategyId = 'ajgl.security.authentication.session_strategy.'.$id;

        if (isset($config['max_sessions']) && $config['max_sessions'] > 0) {
            $concurrentSessionControlStrategyId = 'ajgl.security.authentication.session_strategy.concurrency_control.'.$id;
            $container
                ->setDefinition(
                    $concurrentSessionControlStrategyId,
                    new DefinitionDecorator('ajgl.security.authentication.session_strategy.concurrency_control')
                )
                ->replaceArgument(1, $config['max_sessions'])
                ->replaceArgument(2, $config['error_if_maximum_exceeded'])
            ;

            $authenticationStrategies[] = new Reference($concurrentSessionControlStrategyId);
        }

        $authenticationStrategies[] = new Reference('security.authentication.session_strategy');

        if (isset($config['register_new_sessions']) && $config['register_new_sessions'] === true) {
            $registerSessionStrategyId = 'ajgl.security.authentication.session_strategy.register.'.$id;
            $container->setDefinition(
                $registerSessionStrategyId,
                new DefinitionDecorator('ajgl.security.authentication.session_strategy.register')
            );
            $authenticationStrategies[] = new Reference($registerSessionStrategyId);
        }

        $container
            ->setDefinition(
                $sessionStrategyId,
                new DefinitionDecorator('ajgl.security.authentication.session_strategy.composite')
            )
            ->replaceArgument(0, $authenticationStrategies)
        ;

        return $sessionStrategyId;
    }
}
