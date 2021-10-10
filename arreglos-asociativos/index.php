<?php
session_start();

$requestMethod = $_SERVER['REQUEST_METHOD'];

$buttonActivated = filter_input(INPUT_POST, 'registrar');
?>
<?php if ($requestMethod === 'GET') : ?>
    <html>
        <header>
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <meta http-equiv="Content-Language" content="es-VE" />
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Arreglos asociativos</title>
            <style>
                body {
                    font-family: Arial, Helvetica, sans-serif;
                }
                
                .d-flex {
                    display: flex;
                }

                .flex-wrap {
                    flex-wrap: wrap;
                }

                .justify-content-between {
                    justify-content: space-between;
                }

                .mx-5 {
                    margin-left: 5%;
                    margin-right: 5%;
                }

                .mt-2 {
                    margin-top: 2%;
                }

                .error-label {
                    color: red;
                }
                
                .overflow-wrap {
                    overflow-wrap:break-word;
                }
                
                
            </style>

        </header>
        <body>
            <form name="registro-empleados" method="POST" class="mx-5">
                <div class="d-flex flex-wrap justify-content-between">

                    <?php if (isset($_SESSION['empleados']) && !isset($_SESSION['errores'])) : ?>

                        <?php for ($i = 0; $i < 3; $i++) : ?>
                            <?php
                            $empleado = isset($_SESSION['empleados'][$i]) ? $_SESSION['empleados'][$i] : array();
                            $errores = isset($_SESSION['errores'][$i]) ? $_SESSION['errores'][$i] : array();
                            ?>
                            <div class="overflow-wrap">
                                <p><strong>Nombre completo:</strong> <?php echo htmlspecialchars(isset($empleado['nombre']) ? $empleado['nombre'] : '') ?>
                                    <?php echo htmlspecialchars(isset($empleado['apellido']) ? $empleado['apellido'] : '') ?></p>
                                <p><strong>Cédula:</strong> <?php echo htmlspecialchars(isset($empleado['cedula']) ? $empleado['cedula'] : '') ?></p>
                                <p><strong>Sueldo:</strong> <?php echo htmlspecialchars(isset($empleado['sueldo']) ? number_format((float) $empleado['sueldo'], 3) : '') ?></p>
                                <p><strong>Departamento:</strong></p>
                                <p><?php echo htmlspecialchars(isset($empleado['departamento']) ? $empleado['departamento'] : '') ?></p>
                                <p><strong>Lugar de trabajo:</strong></p>
                                <p><?php echo htmlspecialchars(isset($empleado['lugar']) ? $empleado['lugar'] : '') ?></p>
                            </div>
                        <?php endfor; ?>

                    <?php else : ?>

                        <?php for ($i = 0; $i < 3; $i++) : ?>

                            <?php
                            $empleado = isset($_SESSION['empleados']) && isset($_SESSION['empleados'][$i]) ? $_SESSION['empleados'][$i] : array();
                            $errores = isset($_SESSION['errores']) && isset($_SESSION['errores'][$i]) ? $_SESSION['errores'][$i] : array();
                            ?>
                            <div class="overflow-wrap">
                                <h4>Empleado #<?php echo $i + 1 ?></h4>
                                <label for="nombres[]">Nombre:</label><br />
                                <input type="text" name="nombre[]" value="<?php echo htmlspecialchars(isset($empleado['nombre']) ? $empleado['nombre'] : '') ?>"/><br />
                                <strong class="error-label" for="nombre[]"><?php echo htmlspecialchars(isset($errores['nombre']) ? $errores['nombre'] : '' ) ?></strong><br />
                                <label for="apellido[]">Apellido:</label><br />
                                <input type="text" name="apellido[]" value="<?php echo htmlspecialchars(isset($empleado['apellido']) ? $empleado['apellido'] : '') ?>" /><br />
                                <strong class="error-label" for="apellido[]"><?php echo htmlspecialchars(isset($errores['apellido']) ? $errores['apellido'] : '' ) ?></strong><br />
                                <label for="cedula[]">Cédula:</label><br />
                                <input type="number" name="cedula[]" value="<?php echo htmlspecialchars(isset($empleado['cedula']) ? $empleado['cedula'] : '') ?>" /><br />
                                <strong class="error-label" for="cedula[]"><?php echo htmlspecialchars(isset($errores['cedula']) ? $errores['cedula'] : '' ) ?></strong><br />
                                <label for="sueldo[]">Sueldo:</label><br />
                                <input type="number" name="sueldo[]" step=".001" value="<?php echo htmlspecialchars(isset($empleado['sueldo']) ? $empleado['sueldo'] : '') ?>" /><br />
                                <strong class="error-label" for="sueldo[]"><?php echo htmlspecialchars(isset($errores['sueldo']) ? $errores['sueldo'] : '' ) ?></strong><br />
                                <label for="departamento[]">Departamento:</label><br />
                                <input type="text" name="departamento[]" value="<?php echo htmlspecialchars(isset($empleado['departamento']) ? $empleado['departamento'] : '') ?>" /><br />
                                <strong class="error-label" for="departamento[]"><?php echo htmlspecialchars(isset($errores['departamento']) ? $errores['departamento'] : '' ) ?></strong><br />
                                <label for="lugar[]">Lugar de trabajo:</label><br />
                                <input type="text" name="lugar[]" value="<?php echo htmlspecialchars(isset($empleado['lugar']) ? $empleado['lugar'] : '') ?>" /><br />
                                <strong class="error-label" for="lugar[]"><?php echo htmlspecialchars(isset($errores['lugar']) ? $errores['lugar'] : '' ) ?></strong><br />
                            </div>

                        <?php endfor; ?>

                    <?php endif; ?>

                </div>
                <div class="mt-2">
                    <?php if (!isset($_SESSION['empleados']) || isset($_SESSION['errores'])) : ?>
                        <input type="submit" value="Registrar empleados" name="registrar" />
                    <?php else : ?>
                        <a href="">Volver a registro</a>
                    <?php endif; ?>
                </div>
            </form>
        </body>
    </html>
    <?php elseif ($requestMethod === 'POST' && $buttonActivated) :

    require_once 'helpers.php';

    function checkEmpleado(array $empleado): array
    {
        $errors = array();

        $errors['nombre'] = checkText(isset($empleado['nombre']) ? $empleado['nombre'] : '', 120);
        $errors['apellido'] = checkText(isset($empleado['apellido']) ? $empleado['apellido'] : '', 120);
        $errors['cedula'] = checkText(isset($empleado['cedula']) ? $empleado['cedula'] : '', 12, true);
        $errors['departamento'] = checkText(isset($empleado['departamento']) ? $empleado['departamento'] : '', 120);
        $errors['lugar'] = checkText(isset($empleado['lugar']) ? $empleado['lugar'] : '', 120);
        $errors['sueldo'] = checkFloat(isset($empleado['sueldo']) ? $empleado['sueldo'] : '');

        return $errors;
    }

    unset($_POST['registrar']);
    $empleados = array();

    foreach ($_POST as $key => $value)
    {
        if (is_array($value))
        {
            for ($i = 0; $i < count($value); $i++)
            {
                if (!isset($empleados[$i]))
                {
                    $empleados[$i] = array();
                }

                $empleados[$i][$key] = $value[$i];
            }
        }
    }

    $errors = array();

    foreach ($empleados as $empleado)
    {
        $error = checkEmpleado($empleado);

        $errors[] = $error;
    }

    $filteredErrors = array();

    foreach ($errors as $error)
    {
        $filteredError = filterAssociativeArray($error);

        if (count($filteredError) > 0)
        {
            $filteredErrors[] = $filteredError;
        }
    }

    if (count($filteredErrors) > 0)
    {
        $_SESSION['errores'] = $errors;
    }

    $_SESSION['empleados'] = $empleados;

    header('Location: .');
    die();
    ?>
<?php else : ?>
    <h3>Bad Request</h3>
    <a href="">Volver a registro</a>
<?php
endif;

session_destroy();

