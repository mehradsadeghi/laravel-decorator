<?php

namespace Mehradsadeghi\Decorator;

use Closure;
use InvalidArgumentException;

class Decorator {

    const INVALID_ARGUMENT_EXCEPTION = '%s should be type of %s.';

    private static $decorations = [];
    private static $currentCallable;

    public function register($callable)
    {
        $callable = $this->stringifyCallable($callable);

        self::$decorations[$callable] ?? [];
        self::$currentCallable = $callable;

        return $this;
    }

    public function set($decorator)
    {
        $this->validateCallable($decorator, 'Decorator');

        $decorator = $this->getClosure($decorator);

        self::$decorations[self::$currentCallable][] = $decorator;

        return $this;
    }

    public function decorate($callable, array $params = [])
    {
        $callable = $this->stringifyCallable($callable);

        if(is_null($decorators = $this->getDecorations($callable))) {
            return app()->call($callable, $params);
        }

        $callable = $this->performDecorations($decorators, $callable);

        return app()->call($callable, $params);
    }

    public function flush() {
        self::$decorations = [];
        self::$currentCallable = null;
    }

    private function getDecorations($callable)
    {
        return self::$decorations[$callable] ?? null;
    }

    private function stringifyCallable($callable)
    {
        $this->validateCallable($callable);

        return join('@', $callable);
    }

    private function performDecorations($decorators, $callable)
    {
        foreach ($decorators as $decorator) {
            $callable = app()->call($decorator, [$callable]);
        }

        return $callable;
    }

    private function validateCallable($callable, $message = null)
    {
        if (!is_callable($callable)) {
            throw new InvalidArgumentException(sprintf(self::INVALID_ARGUMENT_EXCEPTION, $message ?? 'Callable', 'PHP callables'));
        }
    }

    private function getClosure($decorator)
    {
        if (!$decorator instanceof Closure) {
            $decorator = function ($callable) use ($decorator) {
                [$class, $method] = $decorator;
                return app($class)->{$method}($callable);
            };
        }
        return $decorator;
    }
}
