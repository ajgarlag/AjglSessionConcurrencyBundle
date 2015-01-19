<?php

/*
 * This file is part of the AJGL packages
 *
 * Copyright (C) Antonio J. García Lagar <aj@garcialagar.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajgl\Bundle\SessionConcurrencyBundle;

use Ajgl\Bundle\SessionConcurrencyBundle\DependencyInjection\Compiler\AddSessionConcurrencyControlPass;
use Ajgl\Bundle\SessionConcurrencyBundle\DependencyInjection\Compiler\OverrideSessionExpirationListenerPass;
use Ajgl\Bundle\SessionConcurrencyBundle\DependencyInjection\Compiler\OverrideSessionLogoutHandlerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Antonio J. García Lagar <aj@garcialagar.es>
 */
class AjglSessionConcurrencyBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        if (!$container->hasExtension('security')) {
            throw new \LogicException('The AjglSessionConcurrencyBundle must be registered after the SecurityBundle in your AppKernel.php.');
        }

        $container->addCompilerPass(new AddSessionConcurrencyControlPass());
        $container->addCompilerPass(new OverrideSessionExpirationListenerPass());
        $container->addCompilerPass(new OverrideSessionLogoutHandlerPass());

        parent::build($container);
    }
}
