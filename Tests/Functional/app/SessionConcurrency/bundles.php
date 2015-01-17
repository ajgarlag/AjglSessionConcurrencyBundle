<?php

/*
 * This file is part of the AJGL packages
 *
 * Copyright (C) Antonio J. García Lagar <aj@garcialagar.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Ajgl\Bundle\SessionConcurrencyBundle\AjglSessionConcurrencyBundle;
use Ajgl\Bundle\SessionExpirationBundle\AjglSessionExpirationBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\SecurityBundle\Tests\Functional\Bundle\FormLoginBundle\FormLoginBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;

return array(
    new FrameworkBundle(),
    new SecurityBundle(),
    new TwigBundle(),
    new FormLoginBundle(),
    new AjglSessionExpirationBundle(),
    new AjglSessionConcurrencyBundle(),
);
