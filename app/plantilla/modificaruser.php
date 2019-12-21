<?php
    //Guardo la salida en un buffer(en memoria)
    //[No se envia al navegador]
    ob_start();
    
?>
<form action='index.php'> 
    <table>
    	<tr>
        	<td>Identificador de usuario:</td>
        	<td><input type="text" name="clave" value="<?= $_GET["id"] ?>"></td>
        </tr>
        <tr>
        	<td>Contraseña:</td>
        	<td><input type="text" name="pass" value="<?= $_SESSION["tusuarios"][$_GET["id"]][0] ?>"></td>
        </tr>
        <tr>
        	<td>Nombre de usuario:</td>
        	<td><input type="text" name="nombre" value="<?= $_SESSION["tusuarios"][$_GET["id"]][1] ?>"></td>
        </tr>
        <tr>
        	<td>Correo:</td>
        	<td><input type="text"  name="mail" value="<?= $_SESSION["tusuarios"][$_GET["id"]][2] ?>"></td>
        </tr>
        <tr>
        	<td>Plan:</td>
        	<td><input type="number" name="plan" value="<?= $_SESSION["tusuarios"][$_GET["id"]][3] ?>" min="0" max="2"></td>
        </tr>
        <tr>
        	<td>Estado:</td>
        	<td><input type="text" name="estado" value="<?= $_SESSION["tusuarios"][$_GET["id"]][4] ?>"></td>
        </tr>
    </table>
	<br>
	<input type="hidden" name="orden" value="Modificar">
	<input type='submit' value='Guardar'> 
</form>       
<br>
<a href="index.php" style="text-decoration: none;"><button>Volver</button></a>
<?php 
    //Vacío el búfer y lo copio a contenido
    //Para que se muestre en el div de contenido de la página principal
    $contenido = ob_get_clean();
    include_once "principal.php";
?>