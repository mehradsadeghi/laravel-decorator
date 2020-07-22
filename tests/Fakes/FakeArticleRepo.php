<?php

namespace Mehradsadeghi\DecoratorTest\Fakes;

class FakeArticleRepo {

    function getArticles($ids)
    {
        return $ids;
    }
}