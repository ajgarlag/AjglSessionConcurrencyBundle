AjglSessionConcurrencyBundle
============================

Symfony bundle to add concurrency control for user sessions.


Instalation
-----------

### Download AjglSessionConcurrencyBundle

Add AjglSessionConcurrencyBundle requirement:

``` bash
$ php composer.phar require ajgl/session-concurrency-bundle
```

Composer will install the bundle to your project's `vendor/ajgl` directory.


### Enable the Bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Ajgl\Bundle\SessionConcurrencyBundle\AjglSessionConcurrencyBundle(),
    );
}
```


Configuration
-------------

This feature has two different operation modes:

1. **Disable new sessions**. In this mode, an user is not allowed to authenticate
   to a firewall if the total number of sessions exceeds the limit.
2. **Expire old sessions**. In this mode, whenever an user gets authenticated in
   a firewall, old sessions are marked as expired if the number of active
   session exceeds the limit.

### Disable new sessions

To configure the session concurrency control, you have to define the maximun
number of sessions allowed for each firewall with the `max_sessions` option.

``` yaml
# app/config/config.yml
ajgl_session_concurrency:
    firewalls:
        firewall_name:
            max_sessions: 2
            expiration_url: /expired
```

### Expire old sessions

This option will prevent the user authentication if the limit is reached. If you
want to allow new user sessions and to expire old sessions you must set the
`error_if_maximum_exceeded` option to false, and you should enable the session
expiration control for the firewal with [AjglSessionExpirationBundle](https://github.com/ajgarlag/AjglSessionExpirationBundle).
That bundle should be loaded before this one.

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Ajgl\Bundle\SessionExpirationBundle\AjglSessionExpirationBundle(),
        new Ajgl\Bundle\SessionConcurrencyBundle\AjglSessionConcurrencyBundle(),
    );
}
```

``` yaml
# app/config/config.yml
ajgl_session_expiration:
    firewalls:
        firewall_name:
            max_idle_time: 1440

ajgl_session_concurrency:
    firewalls:
        firewall_name:
            max_sessions: 2
            expiration_url: /expired
```
