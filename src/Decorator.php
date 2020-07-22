<?php

namespace Mehradsadeghi\Decorator;

class Decorator {

    private static $decorations = [];

    public function decorateWith($callable, $decorator)
    {
        $callable = $this->normalizeCallable($callable);
        self::$decorations[$callable][] = $decorator;
    }

    public function decorate($callable, array $params = [])
    {
        $callable = $this->normalizeCallable($callable);

        if(is_null($decorators = $this->getDecorations($callable))) {
            return app()->call($callable, $params);
        }

        foreach($decorators as $decorator) {
            $callable = app()->call($decorator, [$callable]);
        }

        return app()->call($callable, $params);
    }

    public function getDecorations($callable)
    {
        return self::$decorations[$callable] ?? null;
    }

    private function normalizeCallable($callable)
    {
        if (!is_array($callable)) {
            throw new InvalidArgumentException('callable is invalid');
        }

        return join('@', $callable);
    }
}
