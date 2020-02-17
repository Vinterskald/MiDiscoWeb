<?php
    if(isset($_SESSION["user"])){
        $usuario = unserialize($_SESSION["user"]);
    }else{
        exit("Error: la página necesita iniciar sesión.");
    }
    
    // Guardo la salida en un buffer(en memoria)
    // No se envia al navegador
    ob_start();
?>
<div id='aviso'><b><?= (isset($msg))?$msg:"" ?></b></div>
<h5 style="text-align: left; margin-left: 5%;">Ficheros del usuario <label style="color: black"><?= $usuario->getId() ?></label>:</h5>
<br>
<div id="botones" style="text-align: right; margin-top: -6%; margin-bottom: 5%;">
	<form action='index.php' style="display: inline-block; margin-right: 40px;">
	    <input type='hidden' name="subir"> 
	 	<input type='submit' value='Subir archivo'>
     </form>
     <form action='index.php' style="display: inline-block; margin-right: 40px;">
	    <input type='hidden' name="modificar">
	    <input type='hidden' name="id" value="<?= $usuario->getId() ?>">
	 	<input type='submit' value='Modificar datos de cuenta'>
     </form>
</div>
<table>
	<tr>
		<th>Nombre</th>
		<th>Tipo</th>
		<th>Fecha</th>
		<th>Tamaño</th>
		<th colspan="3">Operaciones</th>
	</tr>
<?php  
    $auto = $_SERVER['PHP_SELF'];
    foreach(scandir(RUTA_FICHEROS.$usuario->getId()) as $fichero){
?>
	<tr>
<?php 
        if($fichero != "." && $fichero != ".."){
            echo "<td><a href='index.php?orden=Descargar&archivo=".$fichero."'>$fichero</a></td>";
            echo "<td>".mime_content_type(RUTA_FICHEROS.$usuario->getId()."/".$fichero)."</td>";
            echo "<td>".date("d-m-y", filectime(RUTA_FICHEROS.$usuario->getId()."/".$fichero))."</td>";
            echo "<td>".filesize(RUTA_FICHEROS.$usuario->getId()."/".$fichero)." bytes</td>";
        
 ?>
		<td><a href="<?= $auto?>?orden=Borrar&archivo=<?= $fichero ?>" onclick="confirmarBorrarFichero('<?php echo $fichero; ?>');">Eliminar</a></td>
		<td><a href="#" onclick="renombrar('<?= $fichero ?>');">Renombrar</a></td>
		<td><a href="<?= $auto?>?orden=Compartir&archivo=<?= $fichero ?>">Compartir</a></td>
	</tr>
<?php
        }
    } 
?>
</table>
 <?php 
    //Se determina el espacio máximo de almacenaje y de subida para el usuario conectado:
     $max_espacio = LIMITE_ESPACIO[array_search($usuario->getPlan(), PLANES)];
     $max_archivos = LIMITE_FICHEROS[array_search($usuario->getPlan(), PLANES)];
  ?>
<ul style="text-align: left; list-style: none; color: black;">
	<li>Ficheros: <?= count(preg_grep("/^([^.])/", scandir(RUTA_FICHEROS.$usuario->getId()."/"))) ?> (de un total de <?= $max_archivos ?>)</li>
	<li>Espacio ocupado: <?= tam_dir(RUTA_FICHEROS.$usuario->getId()."/") ?> bytes (de un total de <?php echo $max_espacio; ?> bytes)
	 <?php if($usuario->getPlan() == "Máster") echo "<br><br>[Los administradores no tienen límites de espacio y fichero.]"; ?></li>
</ul>
<hr>
<br>
<?php 
    if($usuario->getPlan() == "Máster"){
        echo "
            <form action='index.php'>
	           <input type='hidden' name='cambiar'> 
	           <input type='submit' value='Volver a gestión de usuarios'>
            </form>
            <br>
        ";
    }
?>
<form action='index.php'>
	<input type='hidden' name='orden' value='Cerrar'> 
	<input type='submit' value='Cerrar sesión'>
</form>
<?php
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido de la página principal
$contenido = ob_get_clean();
include_once "principal.php";

?>