<?php

namespace Mehradsadeghi\DecoratorTest\Decorators;

class ArticleRepoDecorator {

    public function decorateParams($callable)
    {
        return function($params) use ($callable) {

            if(is_string($params)) {
                $params = explode(',', $params);
            }

            return app()->call($callable, [$params]);
        };
    }
}