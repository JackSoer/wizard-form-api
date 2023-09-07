<?php

declare (strict_types = 1);

namespace App;

require __DIR__ . '/Exceptions/RouteNotFoundException.php';

use App\Exceptions\RouteNotFoundException;

class Router
{
    private array $routes;

    public function register(string $requestMethod, string $route, callable | array $action): self
    {
        $this->routes[$requestMethod][$route] = $action;

        return $this;
    }

    public function get(string $route, callable | array $action): self
    {
        return $this->register('get', $route, $action);
    }

    public function post(string $route, callable | array $action, array | null $objects = null): self
    {
        if (!empty($objects)) {
            foreach ($objects as $object) {
                $id = $object['id'];
                $routeWithId = $route . '/' . $id;

                array_push($action, [$id]);

                $this->register('post', $routeWithId, $action);

                array_pop($action);
            }

            return $this;
        }

        return $this->register('post', $route, $action);
    }

    public function put(string $route, callable | array $action, array $objects): self
    {
        foreach ($objects as $object) {
            $id = $object['id'];
            $routeWithId = $route . '/' . $id;

            array_push($action, [$id]);

            $this->register('put', $routeWithId, $action);

            array_pop($action);
        }

        return $this;
    }

    public function delete(string $route, callable | array $action, array $objects): self
    {
        foreach ($objects as $object) {
            $id = $object['id'];
            $routeWithId = $route . '/' . $id;

            array_push($action, [$id]);

            $this->register('delete', $routeWithId, $action);

            array_pop($action);
        }

        return $this;
    }

    public function routes(): array
    {
        return $this->routes;
    }

    public function resolve(string $requestUri, string $requestMethod)
    {
        $route = explode('?', $requestUri)[0];
        $action = $this->routes[$requestMethod][$route] ?? null;

        if (!$action) {
            throw new RouteNotFoundException();
        }

        if (is_callable($action)) {
            return call_user_func($action);
        }

        if (is_array($action)) {
            if (isset($action[2])) {
                [$class, $method, $args] = $action;
            } else {
                [$class, $method] = $action;
                $args = [];
            }

            if (class_exists($class)) {
                $class = new $class();

                if (method_exists($class, $method)) {
                    return call_user_func_array([$class, $method], [...$args]);
                }
            }
        }

        throw new RouteNotFoundException();
    }
}
