<?php

namespace Mehradsadeghi\DecoratorTest\Fakes;

class UserRepo {

    function getUsers($ids)
    {
        return $ids;
    }
}