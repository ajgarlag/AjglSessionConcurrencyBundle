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
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Antonio J. García Lagar <aj@garcialagar.es>
 */
class OverrideSessionExpirationListenerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach (array_keys($container->getParameter('ajgl.security.authentication.sessionconcurrency_firewalls')) as $id) {
            $sessionExpirationListenerId = 'ajgl.security.authentication.sessionexpiration_listener.'.$id;
            if ($container->has($sessionExpirationListenerId)) {
                $oldDefinition = $container->findDefinition($sessionExpirationListenerId);
                $arguments = $oldDefinition->getArguments();

                $newDefinition = new DefinitionDecorator('ajgl.security.authentication.sessionregistryexpiration_listener');
                array_unshift($arguments, new Reference('ajgl.security.session_registry'));
                $newDefinition->setArguments($arguments);

                $container->setDefinition($sessionExpirationListenerId, $newDefinition);
            }
        }
    }
}
