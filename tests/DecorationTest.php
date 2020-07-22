<?php

namespace Mehradsadeghi\DecoratorTest;

use Closure;
use InvalidArgumentException;
use Mehradsadeghi\DecoratorTest\Decorators\ArticleRepoDecorator;
use Mehradsadeghi\DecoratorTest\Decorators\UserRepoDecorator;
use Mehradsadeghi\DecoratorTest\Fakes\FakeArticleRepo;
use Mehradsadeghi\DecoratorTest\Fakes\FakeUserRepo;

class DecorationTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        decorator()->flush();
    }

    /** @test */
    public function input_of_an_existing_method_can_be_decorated_by_one_decorator()
    {
        decorator([FakeUserRepo::class, 'getUsers'])
            ->set(function($callable) {
                return function($params) use ($callable) {

                    if(is_string($params)) {
                        $params = explode(',', $params);
                    }

                    return app()->call($callable, [$params]);
                };
            });

        $users = decorate([FakeUserRepo::class, 'getUsers'], ['1,2']);

        $this->assertEquals([1, 2], $users);

        decorator([FakeUserRepo::class, 'getUsers'])
            ->set([UserRepoDecorator::class, 'decorateParams']);

        $users = decorate([FakeUserRepo::class, 'getUsers'], ['1,2,3']);

        $this->assertEquals([1, 2, 3], $users);
    }

    /** @test */
    public function input_of_an_existing_method_can_be_decorated_by_two_different_decorators()
    {
        decorator([FakeUserRepo::class, 'getUsers'])
            ->set(function($callable) {
                return function($params) use ($callable) {

                    if(is_string($params)) {
                        $params = explode(',', $params);
                    }

                    return app()->call($callable, [$params]);
                };
            })
            ->set([UserRepoDecorator::class, 'changeToBoolean']);

        $users = decorate([FakeUserRepo::class, 'getUsers'], ['1,2']);

        $this->assertEquals([true, 2], $users);
    }

    /** @test */
    public function invalid_callables_can_not_be_assigned_as_decorator()
    {
        $this->expectException(InvalidArgumentException::class);

        decorator([FakeUserRepo::class, 'getUsers'])
            ->set('UserRepoDecorator@changeToBoolean');
    }

    /** @test */
    public function separate_assigned_decorators_on_a_callable_wont_get_replaced()
    {
        decorator([FakeUserRepo::class, 'getUsers'])
            ->set([UserRepoDecorator::class, 'decorateParams']);

        decorator([FakeUserRepo::class, 'getUsers'])
            ->set([UserRepoDecorator::class, 'changeToBoolean']);

        $users = decorate([FakeUserRepo::class, 'getUsers'], ['1,2']);

        $this->assertEquals([true, 2], $users);
    }
    
    /** @test */
    public function two_separated_methods_can_get_decorated_by_different_decorators()
    {
        decorator([FakeUserRepo::class, 'getUsers'])
            ->set([UserRepoDecorator::class, 'decorateParams']);

        decorator([FakeArticleRepo::class, 'getArticles'])
            ->set([ArticleRepoDecorator::class, 'decorateParams']);

        $users = decorate([FakeUserRepo::class, 'getUsers'], ['1,2']);
        $articles = decorate([FakeArticleRepo::class, 'getArticles'], ['5,6']);

        $this->assertEquals([1, 2], $users);
        $this->assertEquals([5, 6], $articles);
    }
}