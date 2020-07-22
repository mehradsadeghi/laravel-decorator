<?php

namespace Mehradsadeghi\DecoratorTest\Fakes;

class FakeUserRepo {

    function getUsers($ids)
    {
        return $ids;
    }
}