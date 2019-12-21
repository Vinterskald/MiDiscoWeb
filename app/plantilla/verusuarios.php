<?php
    //Guardo la salida en un buffer(en memoria)
    //[No se envia al navegador]
    ob_start();
?>
<div id='aviso'><b><?= (isset($msg))?$msg:"" ?></b></div>
<table>
	<tr>
<?php  
    $auto = $_SERVER['PHP_SELF'];
    /* identificador => Nombre, email, plan y Estado
    */
    foreach($usuarios as $clave => $datosusuario){
?>
	<tr>
<?php 
        echo "<td>$clave</td>";
        for ($j=0; $j < count($datosusuario); $j++){
            echo "<td>$datosusuario[$j]</td>";
        }
 ?>
		<td><a href="<?= $auto?>?orden=Borrar&id=<?= $clave ?>" onclick="confirmarBorrar('<?= $datosusuario[0]."','".$clave."'"?>);" >Borrar</a></td>
		<td><a href="<?= $auto?>?modificar&id=<?= $clave ?>">Modificar</a></td>
		<td><a href="<?= $auto?>?orden=Detalles&id=<?= $clave ?>">Detalles</a></td>
	</tr>
<?php
    } 
?>
</table>  
<br>
<form action='index.php'> 
	<input type='submit' name="darAlta" value='Dar de alta nuevo usuario'> 
</form>       
<br><br>
<form action='index.php'> 
	<input type='hidden' name='orden' value='Cerrar'> 
	<input type='submit' value='Cerrar sesión'> 
</form>  

<?php 
    //Vacío el búfer y lo copio a contenido
    //Para que se muestre en el div de contenido de la página principal
    $contenido = ob_get_clean();
    include_once "principal.php";
?>