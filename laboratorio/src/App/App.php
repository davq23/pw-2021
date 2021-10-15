<?php
namespace App;

use Controllers\Controller;
use Controllers\Exceptions\BadRequestException;
use Database\DBConnection;
use Domains\Exceptions\InvalidDomainException;
use Exception;
use ReflectionClass;
use ReflectionException;

/**
 * App instance
 */
class App
{
    protected ?DBConnection $db;
    protected array $injectables;
    protected ?Router $router;

    /**
     * @param Router $router
     * @param DBConnection $db
     */
    public function __construct(Router $router, DBConnection $db)
    {
        $this->router = $router;        
        $this->db = $db;
        $this->injectables[get_class($db)] = $db;
    }

    public function __destruct()
    {
        $this->db = null;
        $this->router = null;
    }

    /**
     * Executes a controller method
     *
     * @param Controller $controllerInstance
     * @param string $classMethod
     */
    private function executeControllerMethod(Controller $controllerInstance, string $classMethod)
    {
        try {
            $result = call_user_func(array($controllerInstance, $classMethod));

            if (is_array($result) || is_object($result)) {
                echo json_encode($result);
            }
        } catch (BadRequestException $badRequestException) {
            http_response_code(400);
        } catch (InvalidDomainException $invalidDomainException) {
            http_response_code(422);
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            http_response_code(500);
        }
    }

    /**
     * Handle interface dependencies
     *
     * @param string $className
     * @return string|null
     */
    private function handleInterface(string $className): ?string
    {
        if (!interface_exists($className)) {
            return null;
        }

        if (strpos($className, 'Repositories\\') !== false) {
            $dbPrefixName = str_replace('DBConnection', '', get_class($this->db));
            $dbPrefixName = str_replace('Database\\', '', $dbPrefixName);
            $bareClassName = str_replace('Repositories\\', '', $className);

            return "Repositories\\$dbPrefixName\\$dbPrefixName$bareClassName";
        }

        return null;
    }

    /**
     * Injects a class into the App
     *
     * @param string $className
     * @return mixed
     * @throws ReflectionException
     */
    public function injectClass(string $className)
    {
        $params = array();
        $reflection = new ReflectionClass($className);
        $parameters = $reflection->getConstructor()->getParameters();
        foreach ($parameters as $parameter) {
            if ($type = $parameter->getType()) {
                $classInstance = null;
                $typeName = $type->getName();

                foreach ($this->injectables as $injectable) {

                    if ($injectable instanceof $typeName) {
                        $classInstance = $injectable;
                        break;
                    }
                }

                if (is_null($classInstance)) {
                    $typeName = $this->handleInterface($typeName) ?? $typeName;
                    $classInstance = $this->injectClass($typeName);

                    $injectables[$typeName] = $classInstance;
                }

                $params[] = $classInstance;
            }
        }

        return new $className(...$params);
    }

    /**
     * Runs the App
     *
     * @throws ReflectionException
     */
    public function run()
    {
        $controllerClassMethodArray = $this->router->run();

        if (is_null($controllerClassMethodArray)) {
            http_response_code(404);
            return;
        }

        [$className, $classMethod] = $controllerClassMethodArray;

        $controller = $this->injectClass($className);

        $this->executeControllerMethod($controller, $classMethod);
    }
}