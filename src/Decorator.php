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
        $callable = $this->normalizeCallable($callable);

        self::$decorations[$callable] ?? [];
        self::$currentCallable = $callable;

        return $this;
    }

    public function with($decorator)
    {
        $this->validateCallable($decorator, 'Decorator');

        self::$decorations[self::$currentCallable][] = $decorator;

        return $this;
    }

    public function decorateIt($callable, array $params = [])
    {
        $callable = $this->normalizeCallable($callable);

        if(is_null($decorators = $this->getDecorations($callable))) {
            return app()->call($callable, $params);
        }

        $callable = $this->performDecorations($decorators, $callable);

        return app()->call($callable, $params);
    }

    public function forget()
    {

    }

    public function flush() {
        self::$decorations = [];
        self::$currentCallable = null;
    }

    public function setCurrentCallable($callable)
    {
        $this->validateCallable($callable);
        self::$currentCallable = $callable;
    }

    private function getDecorations($callable)
    {
        if(!self::$decorations[$callable]) {
            return null;
        }

        foreach(self::$decorations[$callable] as $key => $decoration) {

            if(!$decoration instanceof Closure) {
                self::$decorations[$callable][$key] = join('@', $decoration);
            }
        }

        return self::$decorations[$callable];
    }

    private function normalizeCallable($callable)
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
}
