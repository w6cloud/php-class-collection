# WEB6 PHP Class Collection Trait

Organize classes in folders and auto/lazy load them as attributes.

## Install

Install via Composer

```bash
$ composer require web6/class-collection
```

## Usage

### Configure autoload

Configure autoloading by including Composer's generated file :

```php
include_once('vendor/autoload.php');
```

### Create member classes

Create classes and save them in a folder.

```php
class App {

    use \W6\ClassCollection\ClassCollectionTrait;

    public $message = 'Not inited';

    protected function init() {
        $this->message = 'Inited';
    }
}
```

### Use your class

Anywhere in your application you can request the same instance of the class.

```php
$app = App::instance();
echo $app->message;
```
