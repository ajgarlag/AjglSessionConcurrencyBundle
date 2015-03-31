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

/**
 * @author Antonio J. García Lagar <aj@garcialagar.es>
 */
class OverrideSessionExpirationListenerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach (array_keys($container->getParameter('ajgl.security.authentication.session_concurrency_firewalls')) as $id) {
            $sessionExpirationListenerId = 'ajgl.security.authentication.session_expiration_listener.'.$id;
            if ($container->has($sessionExpirationListenerId)) {
                $newDefinition = new DefinitionDecorator('ajgl.security.authentication.session_registry_expiration_listener');
                $oldDefinition = $container->findDefinition($sessionExpirationListenerId);
                $oldArguments = $oldDefinition->getArguments();
                foreach ($oldArguments as $oldIndex => $argument) {
                    $oldPos = (int) substr($oldIndex, strpos($oldIndex, '_')+1);
                    $newDefinition->replaceArgument($oldPos+1, $argument);
                }
                $container->setDefinition($sessionExpirationListenerId, $newDefinition);
            }
        }
    }
}
