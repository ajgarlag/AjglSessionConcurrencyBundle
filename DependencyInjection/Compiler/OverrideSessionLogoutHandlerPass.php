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

/**
 * @author Antonio J. García Lagar <aj@garcialagar.es>
 */
class OverrideSessionLogoutHandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('security.logout.handler.session')) {
            return;
        }

        $container->removeDefinition('security.logout.handler.session');
        $container->setAlias('security.logout.handler.session', 'ajgl.security.logout.handler.session');
    }
}
