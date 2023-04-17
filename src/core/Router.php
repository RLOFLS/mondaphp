<?php

// * +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// * Copyright 2014 The Herosphp Authors. All rights reserved.
// * Use of this source code is governed by a MIT-style license
// * that can be found in the LICENSE file.
// * +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

declare(strict_types=1);

namespace herosphp\core;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use herosphp\annotation\AnnotationParser;

/**
 * Router class
 *
 * @author RockYang<yangjian102621@gmail.com>
 */
class Router
{
    protected static array $_routes = [];

    public static function add(string $uri, string|array $method, array $handler): void
    {
        if (isset(static::$_routes[$uri])) {
            return;
        }
        static::$_routes[$uri] = ['uri' => $uri, 'method' => $method, 'handler' => $handler];
    }

    public static function getDispatcher(): Dispatcher
    {
        return simpleDispatcher(function (RouteCollector $r) {
            foreach (static::$_routes as $route) {
                if (is_array($route['method'])) {
                    $r->addRoute(AnnotationParser::$_httpMethodAny, $route['uri'], $route['handler']);
                } else {
                    $r->addRoute(strtoupper($route['method']), $route['uri'], $route['handler']);
                }
            }
        });
    }
}
