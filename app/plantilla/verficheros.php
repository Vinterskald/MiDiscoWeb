<?php
    // Guardo la salida en un buffer(en memoria)
    // No se envia al navegador
    ob_start();
?>
<h5 style="text-align: left; margin-left: 5%;">Ficheros del usuario <label style="color: black"><?= $_SESSION["user"] ?></label>:</h5>
<br>
<div style="text-align: right; margin-top: -6%; margin-bottom: 5%;">
	<form action='index.php' style="display: inline-block; margin-right: 40px;">
	    <input type='hidden' name="subir"> 
	 	<input type='submit' value='Subir archivo'>
     </form>
     <form action='index.php' style="display: inline-block; margin-right: 40px;">
	    <input type='hidden' name="modificar">
	    <input type='hidden' name="id" value="<?= $_SESSION["user"] ?>">
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
    foreach(scandir(RUTA_FICHEROS.$_SESSION["user"]) as $fichero){
?>
	<tr>
<?php 
        if($fichero != "." && $fichero != ".."){
            echo "<td><a href='".RUTA_FICHEROS.$_SESSION["user"]."/".$fichero."' download='".$fichero."'>$fichero</a></td>";
            echo "<td>".mime_content_type(RUTA_FICHEROS.$_SESSION["user"]."/".$fichero)."</td>";
            echo "<td>".date("d-m-y", filectime(RUTA_FICHEROS.$_SESSION["user"]."/".$fichero))."</td>";
            echo "<td>".filesize(RUTA_FICHEROS.$_SESSION["user"]."/".$fichero)." bytes</td>";
        
 ?>
		<td><a href="<?= $auto?>?orden=Borrar&nombre=<?= $fichero ?>" onclick="confirmarBorrarFichero('<?php echo $fichero; ?>');">Eliminar</a></td>
		<td><a href="<?= $auto?>?orden=Renombrar&nombre=<?= $fichero ?>">Renombrar</a></td>
		<td><a href="<?= $auto?>?orden=Compartir&nombre=<?= $fichero ?>">Compartir</a></td>
	</tr>
<?php
        }
    } 
?>
</table>
 <?php 
    $max_espacio = 0;
    $max_archivos = 0;
    foreach($_SESSION['tusuarios'] as $clave => $dato){
        if($clave == $_SESSION["user"]){
            $max_espacio = LIMITE_ESPACIO[$dato[3]];
            $max_archivos = LIMITE_FICHEROS[$dato[3]];
        }
    }
  ?>
<ul style="text-align: left; list-style: none; color: black;">
	<li>Ficheros: <?= count(preg_grep("/^([^.])/", scandir(RUTA_FICHEROS.$_SESSION["user"]."/"))) ?> (de un total de <?= $max_archivos ?>)</li>
	<li>Espacio ocupado: <?= tam_dir(RUTA_FICHEROS.$_SESSION["user"]."/") ?> bytes (de un total de <?php echo $max_espacio; ?> bytes)
	 <?php if($_SESSION["tipouser"] == "Máster") echo "<br><br>[Los administradores no tienen límites de espacio y fichero.]"?></li>
</ul>
<hr>
<br>
<?php 
    if($_SESSION["tipouser"] == "Máster"){
        echo "
            <form action='index.php'>
	           <input type='hidden' name='cambiar'> 
	           <input type='submit' value='Volver a gestión de usuarios'>
            </form>
        ";
    }
?>
<br>
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