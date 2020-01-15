<?php
    //Guardo la salida en un buffer(en memoria)
    //[No se envia al navegador]
    ob_start();
    
    
    //ACTUALIZAR A BUCLE QUE MUESTRE DATOS DE LA VARIABLE
?>
<?php if(isset($_GET["id"])){ ?>
<table>
	<tr>
    	<td>Identificador de usuario:</td>
    	<td><?php echo $_GET["id"]; ?></td>
    </tr>
    <tr>
    	<td>Contraseña:</td>
    	<td><?php echo $_SESSION["tusuarios"][$_GET["id"]][0]; ?></td>
    </tr>
    <tr>
    	<td>Nombre de usuario:</td>
    	<td><?php echo $_SESSION["tusuarios"][$_GET["id"]][1]; ?></td>
    </tr>
    <tr>
    	<td>Correo:</td>
    	<td><?php echo $_SESSION["tusuarios"][$_GET["id"]][2]; ?></td>
    </tr>
    <tr>
    	<td>Tipo de plan:</td>
    	<td><?php echo $_SESSION["tusuarios"][$_GET["id"]][3]." - ".PLANES[$_SESSION["tusuarios"][$_GET["id"]][3]]; ?></td>
    </tr>
    <tr>
    	<td>Estado:</td>
    	<td><?php echo $_SESSION["tusuarios"][$_GET["id"]][4]." - ".ESTADOS[$_SESSION["tusuarios"][$_GET["id"]][4]]; ?></td>
    </tr>
    <tr>
    	<td>Archivos en directorio personal:</td>
    	<!-- Se están excluyendo archivos ocultos y especiales con preg_grep(). -->
    	<td><?= count(preg_grep("/^([^.])/", scandir(RUTA_FICHEROS.$_GET["id"]))) ?></td>
    </tr>
    <tr>
    	<td>Espacio ocupado:</td>
    	<td><?= tam_dir(RUTA_FICHEROS.$_GET['id'])." bytes" ?></td>
    </tr>
</table>
<?php } ?>
<br>
<form action='index.php'>  
	<input type='submit' value='Volver'> 
</form>       

<?php 
    //Vacío el búfer y lo copio a contenido
    //Para que se muestre en el div de contenido de la página principal
    $contenido = ob_get_clean();
    include_once "principal.php";
?>