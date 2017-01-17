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
...     sso.vagrant
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

* write tests for: register, login,
* convert blade emails to foil
* auto escaping
* tests working in vagrant - try converting old jt_sso to foil and new tests (not martynbiz/controller) and testing in vagrant

* modules
* http://www.tutorialspoint.com/foundation/
