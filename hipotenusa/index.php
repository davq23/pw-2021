<?php
$automatic = true;
$requestMethod = $_SERVER['REQUEST_METHOD'];

$buttonActivated = filter_input(INPUT_POST, 'calcular-hipotenusa');
?>
<!doctype html>
<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hipotenusa</title>
    </head>
    <body>
        <?php if ($requestMethod === 'GET') : ?>
        
            <?php if ($automatic) : ?>
        
                <form name="calculo-hipotenusa" method="POST">
                    <label>Este programa le permitirá saber la hipotenusa de un triángulo de lados 3cm y 4cm </label><br />
                    <input type="submit" value="Calcular" name="calcular-hipotenusa" />
                </form>  
        
            <?php else : ?>
                
               <form name="calculo-hipotenusa" method="POST">
                    <label for="cateto-1">Cateto 1:</label><br />
                    <input type="text" name="a" value="0" /><br />
                    <label for="cateto-2">Cateto 2:</label><br />
                    <input type="text" name="b" value="0"/><br />
                    <label for="medida">Unidades:</label><br />
                    <select name="medida">
                        <option value="m">metros</option>
                        <option value="cm">centímetros</option>
                        <option value="mm">milímetros</option>
                    </select><br />
                    <input type="submit" value="Calcular" name="calcular-hipotenusa" />
                    <input type="reset" value="Limpiar" name="limpiar" />
                </form>
        
            <?php endif;?>
        
        <?php elseif ($requestMethod === 'POST' && $buttonActivated) : ?>
        
        <div>
            <?php
            $a = $automatic ? 3.00 : filter_input(INPUT_POST, 'a', FILTER_VALIDATE_FLOAT);
            $b = $automatic ? 4.00 : filter_input(INPUT_POST, 'b', FILTER_VALIDATE_FLOAT);
            $medida = $automatic ? 'cm' : filter_input(INPUT_POST, 'medida');
            
            if (!isset($medida) || empty($medida))
            {
                $medida = '';
            }
            
            # Limpiar arreglo
            $_POST = array();
            ?>
            
            <?php if (!isset($a) || !isset($b)) : ?>
            
                <h1>Datos insuficientes</h1>
                
            <?php elseif ($a === false || $b === false || $a < 0 || $b < 0) : ?>
                
                <h3>Alguna de las longitudes de catetos es inválida. Por favor revise los datos ingresados</h3>
                
            <?php else : ?>
                <?php $c = sqrt($a*$a + $b*$b); ?>
                
                <p>
                    Un triángulo rectángulo con catetos de <?php echo htmlspecialchars(number_format($a, 5).$medida);?>
                    y <?php echo htmlspecialchars(number_format($b, 5).$medida); ?> tiene una hipotenusa de 
                    <strong><?php echo htmlspecialchars(number_format($c, 8).$medida);?></strong>
                </p>
               
            <?php endif; ?>
                
            <?php ?>
                
            <a href="">Volver a formulario</a>
        </div>
        
        <?php else : ?>
        
            <h1>Petición inválida</h1>
            
            <a href="">Volver a formulario</a>
            
        <?php endif; ?>
    </body>
</html>

