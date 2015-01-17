<?php

/*
 * This file is part of the AJGL packages
 *
 * Copyright (C) Antonio J. García Lagar <aj@garcialagar.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajgl\Bundle\SessionConcurrencyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Antonio J. García Lagar <aj@garcialagar.es>
 */
class AddSessionConcurrencyControlPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach ($container->getParameter('ajgl.security.authentication.sessionconcurrency_firewalls') as $id => $strategy) {
            $this->overrideFirewallSessionStrategyReferences($container, $id, $strategy);
        }
    }

    private function overrideFirewallSessionStrategyReferences($container, $id, $strategy)
    {
        foreach ($container->getParameter('ajgl.security.authentication.session_strategy.refererers') as $refererConfig) {
            if ($definition = $this->findFirewallServiceDefinitionWithPrefix($container, $refererConfig['prefix'], $id)) {
                $definition->replaceArgument($refererConfig['index'], new Reference($strategy));
            }
        }
    }

    private function findFirewallServiceDefinitionWithPrefix($container, $prefix, $id)
    {
        $serviceId = $prefix.'.'.$id;
        if ($container->has($serviceId)) {
            return $container->findDefinition($serviceId);
        }
    }
}
