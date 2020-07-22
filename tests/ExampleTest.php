<?php

namespace Mehradsadeghi\DecoratorTest;

use Mehradsadeghi\DecoratorTest\Fakes\UserRepo;

class ExampleTest extends TestCase
{
    public function testBasicTest()
    {

        decorate([UserRepo::class, 'getUsers'])
            ->with(function($callable) {
                return function($params) use ($callable) {

                    $params[0] = true;

                    return app()->call($callable, [$params]);
                };
            })
            ->with(function($callable) {
                return function($params) use ($callable) {

                    if(is_string($params)) {
                        $params = explode(',', $params);
                    }

                    return app()->call($callable, [$params]);
                };
            });

        $users = decorateIt([UserRepo::class, 'getUsers'], ['1,2']);

        dd($users);
    }
}





