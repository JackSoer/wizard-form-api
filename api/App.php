<?php

declare (strict_types = 1);

namespace Api;

use Api\Exceptions\RouteNotFoundException;

class App
{
    public function __construct(protected Router $router, protected array $request)
    {

    }

    public function run()
    {
        try {
            echo $this->router->resolve($this->request['uri'], strtolower($this->request['method']));
        } catch (RouteNotFoundException $e) {
            http_response_code(404);

            return json_encode([
                "status" => 404,
                "message" => $e->getMessage(),
            ]);
        }
    }
}
