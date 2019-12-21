<?php
    //Guardo la salida en un buffer(en memoria)
    //[No se envia al navegador]
    ob_start();
?>

<form name='ALTA' action="index.php">
    	<table>
    		<tr>
    			<td>Clave de usuario:</td>
    			<td><input type="text" name="clave" required></td>
    		</tr>
    		<tr>
    			<td>Contraseña:</td>
    			<td><input type="password" name="pass" required></td>
    		</tr>
    		<tr>
    			<td>Nombre de usuario:</td>
    			<td><input type="text" name="nombre" required></td>
    		</tr>
    		<tr>
    			<td>Correo:</td>
    			<td><input type="text" name="mail" required></td>
    		</tr>
    		<tr>
    			<td>Plan:</td>
    			<td><input type="number" name="plan" min="0" max="2" required></td>
    		</tr>
    		<tr>
    			<td>Estado:</td>
    			<td><input type="text" name="estado"></td>
    		</tr>
    	</table>
    	<input type='hidden' name='orden' value='Alta'> 
    	<br>
    	<input type="submit" value="Crear usuario">
    </form>
    <br>
	<a href="index.php" style="text-decoration: none;"><button>Volver</button></a>
<?php 
    //Vacío el búfer y lo copio a contenido
    //Para que se muestre en el div de contenido de la página principal
    $contenido = ob_get_clean();
    include_once "principal.php";
?>