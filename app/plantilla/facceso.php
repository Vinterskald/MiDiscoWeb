<?php 
    // Guardo la salida en un buffer(en memoria)
    // No se envia al navegador
    ob_start();
?>
    <div id='aviso'><b><?= (isset($msg))?$msg:"" ?></b></div>
    <form name='ACCESO' method="POST" action="index.php">
    	<table>
    		<tr>
    			<td>Usuario</td>
    			<td><input type="text" name="user"
    				value="<?= $user ?>"></td>
    		</tr>
    		<tr>
    			<td>Contraseña:</td>
    			<td><input type="password" name="clave"
    				value="<?= $clave ?>"></td>
    		</tr>
    	</table>
    	<br>
    	<input type="submit" name="orden" value="Entrar">
    </form>
    <br>
    <hr>
    <br>
    <a href="index.php?registro" style="margin-right: 12%;">¿No tienes usuario? Date de alta</a>
    <!-- Referencia a sistema de recuperación de credenciales, sin implementar de momento -->
    <a href="#">He olvidado mi contraseña o identificador de usuario</a>
<?php 
    //Vacio el bufer y lo copio a contenido
    //Para que se muestre en div de contenido
    $contenido = ob_get_clean();
    include_once "principal.php";
?>