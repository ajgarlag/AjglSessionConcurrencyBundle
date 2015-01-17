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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Antonio J. García Lagar <aj@garcialagar.es>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ajgl_session_concurrency');

        $rootNode
            ->children()
                ->scalarNode('registry_storage_id')->defaultValue('ajgl.security.session_registry.storage.file')->end()
                ->arrayNode('firewalls')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->children()
                            ->integerNode('max_sessions')->min(0)->end()
                            ->booleanNode('error_if_maximum_exceeded')->defaultTrue()->end()
                            ->booleanNode('register_new_sessions')->defaultTrue()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
