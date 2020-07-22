<?php

namespace Mehradsadeghi\DecoratorTest\Decorators;

class UserRepoDecorator {

    public function decorateParams($callable)
    {
        return function($params) use ($callable) {

            if(is_string($params)) {
                $params = explode(',', $params);
            }

            return app()->call($callable, [$params]);
        };
    }

    public function changeToBoolean($callable)
    {
        return function($params) use ($callable) {

            $params[0] = true;

            return app()->call($callable, [$params]);
        };
    }
}