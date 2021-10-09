<?php
$requestMethod = $_SERVER['REQUEST_METHOD'];

$buttonActivated = filter_input(INPUT_POST, 'calcular-area');
?>
<!doctype html>
<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Octágono regular</title>
    </head>
    <body>
        <?php if ($requestMethod === 'GET') : ?>
        
            <form name="calculo-hipotenusa" method="POST">
                <label for="lados">Longitud de los lados:</label><br />
                <input type="text" name="lados" value="0" /><br />
                <label for="medida">Unidades:</label><br />
                <select name="medida">
                    <option value="m">metros cuadrados</option>
                    <option value="cm">centímetros cuadrados</option>
                    <option value="mm">milímetros cuadrados</option>
                </select><br />
                <input type="submit" value="Calcular" name="calcular-area" />
                <input type="reset" value="Limpiar" name="limpiar" />
            </form>
        
        <?php elseif ($requestMethod === 'POST' && $buttonActivated) : ?>
        
        <div>
            <?php
            $lados = filter_input(INPUT_POST, 'lados', FILTER_VALIDATE_FLOAT);
            $medida = filter_input(INPUT_POST, 'medida');
            
            if (!isset($medida) || $medida === false)
            {
                $medida = '';
            }
            
            # Limpiar arreglo
            $_POST = array();
            ?>
            
            <?php if (!isset($lados)) : ?>
            
                <h1>Datos insuficientes</h1>
                
            <?php elseif ($lados === false || $lados < 0) : ?>
                
                <h3>La longitud de los lados del octágono es inválida. Por favor, inténtelo nuevamente.</h3>
                
            <?php else : ?>
                <?php 
                $apotema = $lados/(2*tan(M_PI_4/2));
                $area = $lados*8*$apotema/2; 
                ?>
                
                <p>
                    Un octágono regular con lados de <?php echo htmlspecialchars(number_format($lados, 5).$medida);?>
                    y apotema de <?php echo htmlspecialchars(number_format($apotema, 5).$medida); ?> tiene un área de 
                    <strong><?php echo htmlspecialchars(number_format($area, 5).$medida.'^2');?></strong>
                </p>
               
            <?php endif; ?>
                
            <a href="">Volver a formulario</a>
        </div>
        
        <?php else : ?>
        
            <h1>Petición inválida</h1>
            
            <a href="">Volver a formulario</a>
            
        <?php endif; ?>
    </body>
</html>

