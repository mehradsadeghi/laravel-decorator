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
    return function ($params) use ($callable) {
        
        // decorating the inputs
        foreach($params as $param) {
            $param = strtoupper($param);
        }

        // real call to makeFullName method
        $output = app()->call($callable, [$params]);

        // decorating the output
        $output = trim($output);

        return $output;
    };
};

decorator([Person::class, 'makeFullName'])->set($decorator);

```
**Note** that the `decorator` should be a valid PHP callable. So it can be a `Closure` or an array callable, Which can be defined as follow:

```php
class PersonDecorator {

    public function decorateFullName($callable)
    {
        return function ($params) use ($callable) {
            
            // decorating the inputs
            foreach($params as $param) {
                $param = strtoupper($param);
            }

            // real call to makeFullName method
            $output = app()->call($callable, [$params]);

            // decorating the output
            $output = trim($output);

            return $output;
        };
    }
}

decorator([Person::class, 'makeFullName'])->set([PersonDecorator::class, 'decorateFullName']);

```

Now we've assigned our decorator to the `makeFullName` method. Calling `makeFullName` with `decorate` helper function will apply its decoration:

```php
decorate([Person::class, 'makeFullName'], ['mehrad', 'sadeghi']); //

```