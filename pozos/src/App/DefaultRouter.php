<?php

namespace App;

use Utils\RouteRules;
use Utils\Utils;

class DefaultRouter implements Router
{
    private array $routes;

    /**
     * Rules that allow variables in the URL path
     *
     * @var array
     */
    private array $rules = [
        '(:num)' => 'Utils\\RouteRules::isNatural',
        '(:empty)' => 'Utils\\RouteRules::isEmpty'
    ];

    public function __construct() {
        $this->routes = array();
    }

    public function server(string $name, $default = null) {
        return $_SERVER[$name] ?? $default;
    }

    /**
     * Check whether the current route matches a registered rule
     *
     * @param array $currentRoute
     * @param string $currentPart
     * @param array $args
     * @return bool
     */
    public function checkForRules(array $currentRoute, string &$currentPart, array &$args): bool {
        $ok = false;
        $rule = '';

        // Check for possible rules
        $patternRules = array_intersect_key($currentRoute, $this->rules);

        if (count($patternRules) > 0) {
            foreach ($patternRules as $patternRule => $val) {
                $ok = call_user_func($this->rules[$patternRule], $currentPart);

                if ($ok) {
                    $rule = $patternRule;
                    break;
                }
            }
        }

        $args[] = $currentPart;
        $currentPart = $rule;

        return $ok;
    }

    /**
     * Strip query string from request path
     *
     * @param string $path
     * @return string
     */
    private function stripQueryString(string $path): string {
        // Strip base URL fragment and query string
        $path = str_replace($_ENV['BASE_URL_PATH_FRAGMENT'] ?? '/pw-2021/pozos/public/', '', $path);
        $path = str_replace('?' . $this->server('QUERY_STRING'), '', $path);

        return $path;
    }

    /**
     * Registers any route
     *
     * @param string $name
     * @param array $params
     */
    public function __call(string $name, array $params) {
        $name = strtoupper($name);

        if (!isset($this->routes[$name])) {
            $this->routes[$name] = array();
        }

        $this->registerRoute($name, $params[0], $params[1], $params[2] ?? 'index');
    }

    /** {@inheritDoc} */
    public function registerRoute(
        string $method,
        string $pattern,
        $controllerClass,
        $controllerMethod = 'index'
    ) {
        $routeArray = &$this->routes[$method];

        if ($pattern === '/') {
            $routeArray['/'] = "$controllerClass::$controllerMethod";
            return;
        }

        // Separate path into an array of segments
        $patternParts = explode('/', $pattern);

        // Set current route pointer
        $currentRoute = &$routeArray;

        // Go through associative array paths to register Controller function
        for ($i = 0; $i < count($patternParts); $i++) {
            if ($patternParts[$i] === '') {
                $patternParts[$i] = '/';
            }

            if ($i !== count($patternParts) - 1) {
                if (!isset($currentRoute[$patternParts[$i]])) {
                    $currentRoute[$patternParts[$i]] = [];
                }

                // Point to the current path if there are still more paths
                $currentRoute = &$currentRoute[$patternParts[$i]];
                continue;
            }

            // Register function to the correct path
            $currentRoute[$patternParts[$i]] = "$controllerClass::$controllerMethod";
        }
    }

    /**
     * Run routing process
     *
     * @return array|null
     */
    public function run(): ?array {
        $path = $this->stripQueryString($this->server('REQUEST_URI'));

        // Add / at the end of all paths
        if (!Utils::endsWith($path, '/')) {
            $path .= '/';
        }

        // Separate path into an array of segments
        $patternParts = explode('/', $path);

        // Delete first segment if it is empty
        if ($patternParts[0] === '') {
            unset($patternParts[0]);
        }

        if (!isset($this->routes[$this->server('REQUEST_METHOD')])) {
            return null;
        }

        // Fetch routes inside current request method
        $currentRoute = &$this->routes[$this->server('REQUEST_METHOD')];

        // Arguments of Controller function
        $args = [];

        foreach ($patternParts as &$part) {
            // If there is no path available, check for the router rules
            if (!isset($currentRoute[$part])) {
                $ok = $this->checkForRules($currentRoute, $part, $args);

                if (!$ok) {
                    break;
                }
            }
            // If the path ends in an array, go through that array
            else if (is_array($currentRoute[$part])) {
                $currentRoute = &$currentRoute[$part];
                continue;
            }

            // Separate class and method from string
            return explode('::', $currentRoute[$part], 2);
        }

        // Route not found
        return null;
    }

}
