<?php
//Guardo la salida en un buffer(en memoria)
//[No se envia al navegador]
ob_start();
?>
<form action="index.php" method="POST">
		<input type="hidden" name="registro">
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
    			<td>Repite la contraseña:</td>
    			<td><input type="password" name="pass2" required></td>
    		</tr>
    		<tr>
    			<td>Nombre de usuario:</td>
    			<td><input type="text" name="nombre" required></td>
    		</tr>
    		<tr>
    			<td>Correo:</td>
    			<td><input type="email" name="mail" required></td>
    		</tr>
    		<tr>
    			<td>Plan:</td>
    			<td>
    				<select name="plan" required>
    					<option value="0" selected>Básico</option>
    					<option value="1">Profesional</option>
    					<option value="2">Premium</option>
    				</select>
    			</td>
    		</tr>
    	</table>
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