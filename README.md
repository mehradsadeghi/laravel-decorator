# Laravel Decorator
#### Decorate and extend functionalities of methods without causing any break to the existing codebase.

### Installation
`$ composer require mehradsadeghi/laravel-decorator`

### Usage
Assuming you have a class named `Person` with a method named `getFullName` which its inputs and output should get decorated:

```php
class Person {

    public function makeFullName($firstName, $lastName)
    {
        return "$firstName $lastName";
    }
}

$person = new Person();
$person->makeFullName('mehrad', 'sadeghi'); // mehrad sadeghi

```
When using `decorator` without setting any decoration, The default behavior of `makeFullName` method will remain the same:

```php
decorate([Person::class, 'makeFullName'], ['mehrad', 'sadeghi']); // mehrad sadeghi
```

In order to decorate `makeFullName` method:
 
```php
$decorator = function ($callable) {
    return function (...$params) use ($callable) {
    
        // decorating the inputs
        foreach($params as $key => $param) {
            $params[$key] = trim($param);
        }

        // real call to makeFullName method
        $output = app()->call($callable, $params);

        // decorating the output
        $output = strtoupper($output);

        return $output;
    };
};

decorator([Person::class, 'makeFullName'])->set($decorator);

```
**Note** that the `decorator` should be a valid PHP callable. So it can be a `Closure` or an array callable, Which can be defined as follows:

```php
class PersonDecorator {

    public function decorateFullName($callable)
    {
        return function (...$params) use ($callable) {
        
            // decorating the inputs
            foreach($params as $key => $param) {
                $params[$key] = trim($param);
            }

            // real call to makeFullName method
            $output = app()->call($callable, $params);

            // decorating the output
            $output = strtoupper($output);

            return $output;
        };
    }
}

decorator([Person::class, 'makeFullName'])->set([PersonDecorator::class, 'decorateFullName']);

```

Now we've assigned our decorator to the `makeFullName` method. Calling `makeFullName` with `decorate` helper function will apply its decoration:

```php
decorate([Person::class, 'makeFullName'], ['  mehrad ', '     sadeghi ']); // MEHRAD SADEGHI

```
#### Multiple Decorators
You can easily set multiple decorators on a method:

```php
decorator([Person::class, 'makeFullName'])
        ->set(function($callable) {
            // decoration
        })
        ->set(function($callable) {
            // decoration
        });
```
or
```php
decorator([Person::class, 'makeFullName'])
    ->set([PersonDecorator::class, 'secondDecorator'])
    ->set([PersonDecorator::class, 'firstDecorator']);
```

#### Forgetting (Removing) Decorator(s)
You can easily remove one or all decorators assigned to a callable. From example above, Assume we have two decorators:

```php
class PersonDecorator {

    public function decorateInput($callable)
    {
        return function (...$params) use ($callable) {

            // decorating the inputs
            foreach($params as $key => $param) {
                $params[$key] = trim($param);
            }

            // real call to makeFullName method
            $output = app()->call($callable, $params);

            return $output;
        };
    }

    public function decorateOutput($callable)
    {
        return function (...$params) use ($callable) {

            // real call to makeFullName method
            $output = app()->call($callable, $params);

            // decorating the output
            $output = strtoupper($output);

            return $output;
        };
    }
}

decorator([Person::class, 'makeFullName'])
    ->set([PersonDecorator::class, 'decorateInput'])
    ->set([PersonDecorator::class, 'decorateOutput']);
```
The output of calling `decorate` would be:  

```php
decorate([Person::class, 'makeFullName'], ['  mehrad ', '     sadeghi ']); // MEHRAD SADEGHI
```
Then for removing `decorateOutput`:

```php
decorator([Person::class, 'makeFullName'])
    ->forget([PersonDecorator::class, 'decorateOutput']);
```
And the output of calling `decorate` would be:  
```php
decorate([Person::class, 'makeFullName'], ['  mehrad ', '     sadeghi ']); // mehrad sadeghi
```
**Note** that for removing all decorations of a callable, just leave the `forget` parameter empty:
```php
decorator([Person::class, 'makeFullName'])->forget();
```