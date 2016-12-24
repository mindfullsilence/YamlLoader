Most of this code came from [slim-config-yaml](https://github.com/techsterx/slim-config-yaml). Gave me an awesome start on this. I've basically just removed the Slim dependency and created a way to get the YAML values directly.

Parses YAML files.
Uses Symfony's YAML Component to parse files (http://github.com/symfony/Yaml).
Allows other YAML files to be imported and parameters to be set and used.

## Getting Started

### Installation

#### Composer

Install composer in your project.
```
curl -s https://getcomposer.org/installer | php
```
Create a ```composer.json``` file in your project root:
```
{
	"require": {
		"mindfullsilence/yamloader": "dev-master@dev"
	},
	"repositories": [
		{
		  "type": "vcs",
		  "url": "https://github.com/mindfullsilence/yamloader"
		}
	]
}
```
Install via composer in terminal:
```
php composer.phar install
```
Add this line to your applications ```index.php``` file:
```php
<?php
require 'vendor/autoload.php';
```

#### Manual Install

Download and extract src/ directory into your project directory and ```require``` it in your
application's ```index.php``` file.
```php
<?php
require 'Yaml.php';

$yamlLoader = new \Mindfullsilence\YamlLoader();
$yamlLoader->addFile('/path/to/some/file.yaml');
$result = $yamlLoader->getConfig();
print_r($result);
```

### Methods

To add a single file, use the ```addFile()``` method.
```php
$yamlLoader->addFile('/path/to/some/file.yaml');
```

You can also chain multiple ```addFile()``` methods togethor.
```php
$yamlLoader
    ->addFile('/path/to/some/file.yaml')
    ->addFile('/path/to/another/file.yaml');
```

You can import a whole directory of YAML files.
```php
$yamlLoader->addDirectory('/path/to/directory');
```

You can chain with the ```addDirectory()``` method as well.
```php
$yamlLoader
    ->addDirectory('/path/to/directory')
    ->addFile('/path/to/some/file.yaml');
```

Specify some global parameters to be used by all YAML files processed.
```php
$yamlLoader
    ->addParameters(array('app.root' => dirname(__FILE__)))
    ->addDirectory('/path/to/config/directory')
    ->addFile('/path/to/file/outside/of/config/directory.yml');
```

### Using Parameters

You can specify parameters in YAML files that will be replaced using keywords. Parameters are only available to the resource currently being processed.

config.yaml
```yaml
parameters:
    key1: value1
    key2: value2

application:
    keya: %key1%
    keyb: %key2%
```

app.php
```php
use \Mindfullsilence\YamlLoader;

$yamlLoader = new YamlLoader();
$yamlLoader->addFile('config.yml');

$config = $app->getConfig('application');

print_r($config);
```

Output:
```
Array
(
    [key1] => value1
    [key2] => value2
)
```

### Importing Files

You can import other YAML files which can be useful to keep all your common parameters in one file and used in others.

parameters.yml
```yaml
parameters:
    db_host:  localhost
    db_user:  username
    db_pass:  password
    db_dbase: database
```

database.yml
```yaml
imports:
    - { resource: parameters.yml }

database:
    hostname: %db_host%
    username: %db_user%
    password: %db_pass%
    database: %db_dbase%
```

app.php
```php
use \Mindfullsilence\YamlLoader;
$yamlLoader = new YamlLoader()->addFile('database.yml');

$db_config = $yamlLoader->getConfig('database');

print_r($db_config);
```

Output:
```
Array
(
    [hostname] => localhost
    [username] => username
    [password] => password
    [database] => database
)
```

## License

YamlLoader is released under the [MIT public license] (https://raw.githubusercontent.com/mindfullsilence/yamloader/master/LICENSE).
