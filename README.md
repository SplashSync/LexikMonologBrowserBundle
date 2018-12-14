SonataAdaminMonologBundle
=========================

[![Build Status](https://travis-ci.org/SplashSync/Php-Bundle.svg?branch=master)](https://travis-ci.org/SplashSync/Php-Bundle)
[![Latest Stable Version](https://poser.pugx.org/splash/sonata-admin-monolog-bundle/v/stable)](https://packagist.org/packages/splash/sonata-admin-monolog-bundle)

This Symfony bundle provides a [Doctrine DBAL](https://github.com/doctrine/dbal) handler for [Monolog](https://github.com/Seldaek/monolog) 
Web UI to display log entries is integrated to Sonata Admin UI. 

You can list, filter and paginate logs as you can see on the screenshot bellow:

![Log entries listing](https://github.com/SplashSync/SonataAdminMonologBundle/raw/master/src/Resources/screen/list.png)
![Log entry show](https://github.com/SplashSync/SonataAdminMonologBundle/raw/master/src/Resources/screen/show.png)

As this bundle query your database on each raised log, it's relevant for small and medium projects, but if you have billion of logs consider using a specific log server.

This Bundle is inspired from [LexikMonologBrowserBundle](https://github.com/lexik/LexikMonologBrowserBundle)

Requirements:
------------

* Symfony 3.4+ | 4.0+ | 4.2+
* Sonata-Project/AdminBundle

Installation
------------

Installation with composer:

``` json
    ...
    "require": {
        ...
        "splash/sonata-admin-monolog-bundle": "@stable",
        ...
    },
    ...
```

Next, be sure to enable these bundles in your `Kernel.php` file:

``` php
public function registerBundles()
{
    return array(
        // SPLASH SONATA ADMIN MONOLOG BUNDLE
        new new Splash\SonataAdminMonologBundle\SplashSonataAdminMonologBundle(),
        // ...
    );
}
```

Basic Configuration
-------------

Then, you can configure Monolog to use the Doctrine DBAL handler:

``` yaml
# app/config/config.yml # or any env
monolog:
    handlers:
        database_handler:
            type:         service
            id:           splash.sonata.admin.monolog.handler
            channels:     ["!event"]
```


Advanced Configuration
-------------

If you don't want to use default Doctrine Entity Manager, you need to configure the Doctrine DBAL connection to use in the handler. 

You have 2 ways to do that:

**By using an existing Doctrine connection:**

Note: we set the `logging` and `profiling` option to false to avoid DI circular reference.

``` yaml
# app/config/config.yml
doctrine:
    dbal:
        connections:
            default:
                ...
            monolog:
                driver:    pdo_sqlite
                dbname:    monolog
                path:      %kernel.root_dir%/cache/monolog2.db
                charset:   UTF8
                logging:   false
                profiling: false

splash_sonata_admin_monolog:
    doctrine:
        connection_name: monolog
```

**By creating a custom Doctrine connection for the bundle:**

``` yaml
# app/config/config.yml
splash_sonata_admin_monolog:
    doctrine:
        connection:
            driver:      pdo_sqlite
            driverClass: ~
            pdo:         ~
            dbname:      monolog
            host:        localhost
            port:        ~
            user:        root
            password:    ~
            charset:     UTF8
            path:        %kernel.root_dir%/db/monolog.db # The filesystem path to the database file for SQLite
            memory:      ~                               # True if the SQLite database should be in-memory (non-persistent)
            unix_socket: ~                               # The unix socket to use for MySQL
```

Please refer to the [Doctrine DBAL connection configuration](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#configuration) for more details.

Now your database is configured, you can generate the schema for your log entry table by running the following command:

```
php bin/console doctrine:schema:update --force
```

Translations
------------

If you wish to use default translations provided in this bundle, make sure you have enabled the translator in your config:

``` yaml
# app/config/config.yml
framework:
    translator: ~
```

Contributing
------------

Any Pull requests are welcome! 

This module is part of [SplashSync](http://www.splashsync.com) project.

