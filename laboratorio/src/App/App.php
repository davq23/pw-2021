<?php

namespace App;

use App\Exceptions\UnauthorizedRequestException;
use Controllers\Controller;
use Controllers\Exceptions\BadRequestException;
use Database\DBConnection;
use Domains\Exceptions\InvalidDomainException;
use Exception;
use ReflectionClass;
use ReflectionException;
use Views\View;

/**
 * App instance
 */
class App
{
    protected ?DBConnection $dbConnection;
    protected ?array $injectables;
    protected ?Router $router;
    protected ?SessionManager $sessionManager;

    /**
     * @param Router $router
     * @param DBConnection $dbConnection
     */
    public function __construct(
        Router $router,
        DBConnection $dbConnection,
        SessionManager $sessionManager
    ) {
        $this->router = $router;
        $this->dbConnection = $dbConnection;
        $this->sessionManager = $sessionManager;

        $this->injectables[get_class($dbConnection)] = $dbConnection;
        $this->injectables[get_class($sessionManager)] = $sessionManager;
    }

    public function __destruct() {
        $this->dbConnection = null;
        $this->injectables = null;
        $this->router = null;
        $this->sessionManager = null;
    }

    /**
     * Executes a controller method
     *
     * @param Controller $controllerInstance
     * @param string $classMethod
     * @return false|string|null
     */
    private function executeControllerMethod(
        Controller $controllerInstance,
        string $classMethod
    ) {
        try {
            $result = call_user_func(array($controllerInstance, $classMethod));

            if ($result instanceof View) {
                return $result->render();
            } else if (is_array($result) || is_object($result)) {
                return json_encode($result);
            }
        } catch (BadRequestException $badRequestException) {
            http_response_code(400);
        } catch (UnauthorizedRequestException $unauthorizedRequestException) {
            http_response_code(401);

            if ($unauthorizedRequestException->getCode() !== Controller::AJAX_REQUEST) {
                $controllerInstance->redirect($unauthorizedRequestException->getMessage(), true);
            } else {
                return $unauthorizedRequestException->getMessage();
            }
        } catch (InvalidDomainException $invalidDomainException) {
            http_response_code(422);
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            http_response_code(500);
        }

        return null;
    }

    /**
     * Handle interface dependencies
     *
     * @param string $className
     * @return string|null
     */
    private function getImplementationTypeName(string $className): ?string {
        if (!interface_exists($className)) {
            return null;
        }

        if (strpos($className, 'Repositories\\') !== false) {
            $dbPrefixName = str_replace('DBConnection', '', get_class($this->dbConnection));
            $dbPrefixName = str_replace('Database\\', '', $dbPrefixName);
            $bareClassName = str_replace('Repositories\\', '', $className);

            return "Repositories\\$dbPrefixName\\$dbPrefixName$bareClassName";
        } else if ($className === 'SessionManager') {
            return get_class($this->sessionManager);
        }

        return null;
    }

    /**
     * Injects a class into the App
     *
     * @param string $className
     * @return mixed
     * @throws ReflectionException
     * @throws Exception
     */
    public function injectClass(string $className, ...$parametersGiven) {
        $params = array();
        $reflection = new ReflectionClass($className);

        if (count($parametersGiven) === 0) {
            $constructor = $reflection->getConstructor();

            $parameters = $constructor ? $constructor->getParameters() : array();

            foreach ($parameters as $parameter) {
                $type = $parameter->getType();

                if ($type) {
                    $classInstance = null;
                    $typeName = $type->getName();

                    foreach ($this->injectables as $injectable) {
                        if ($injectable instanceof $typeName) {
                            $classInstance = $injectable;
                            break;
                        }
                    }

                    if (!$classInstance) {
                        $typeName = $this->getImplementationTypeName($typeName) ?? $typeName;
                        $classInstance = $this->injectClass($typeName);

                        $injectables[$typeName] = $classInstance;
                    }

                    $params[] = $classInstance;
                } else {
                    throw new \Exception('All injectable parameters must be classes or interfaces');
                }
            }

            return new $className(...$params);
        }

        $classInstance = new $className(...$parametersGiven);
        $this->injectables[$className] = $classInstance;

        return $classInstance;
    }

    /**
     * Runs the App
     *
     * @throws ReflectionException
     */
    public function run(): void {
        $controllerClassMethodArray = $this->router->run();

        if (!$controllerClassMethodArray) {
            http_response_code(404);
            return;
        }

        [$className, $classMethod] = $controllerClassMethodArray;

        $controller = $this->injectClass($className);

        $result = $this->executeControllerMethod($controller, $classMethod);

        if (is_string($result)) {
            echo $result;
        }
    }

}
