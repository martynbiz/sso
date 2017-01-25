# SSOAuth

...

## Install the Application ##

```
$ git clone ... sso
$ cd sso
$ composer install
$ chgrp
```

SSOAuth uses Vagrant for development to make installation simple. Please install Vagrant for this purpose. Otherwise, please refer to the /provision.sh script to understand dependencies required for other installations.

```
$ vagrant up
```

Add the following to /etc/hosts

```
192.168.33.11     sso.vagrant
```

## Testing ##

```
$ vagrant ssh
$ cd /var/www/sso/website
$ vendor/bin/phpunit tests/
```

When writing new tests, some custom controller assertions have been added:

```php
$this->assertQuery('form#register', (string)$response->getBody());
$this->assertQueryCount('ul.errors li', 3, (string)$response->getBody());
```

TODO

* oauth: middleware; o-eco /login cannot access session file;
* write tests for: oauth
* convert blade emails to foil - reset,
* auto escaping
* remember me token? passive login? .. how does it affect seo
* getMetaAttribute: [ "meta": [ "facebook_id": "1234567890", ... ] ] .. modules can add to these

* modules - core (renederer, bas controller etc), auth (included auth, make it minimal for custom auth to be build), oauth2 (additional service to auth), facebook (additional service to auth), 
* http://www.tutorialspoint.com/foundation/
