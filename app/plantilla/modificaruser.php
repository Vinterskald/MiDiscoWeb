<?php
    //Guardo la salida en un buffer(en memoria)
    //[No se envia al navegador]
    ob_start();
    
?>
<form action='index.php'"> 
    <table>
    	<tr>
        	<td>Identificador de usuario:</td>
        	<td style="color: black;"><?= $_GET["id"] ?></td>
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
        	<td><input type="email"  name="mail" value="<?= $_SESSION["tusuarios"][$_GET["id"]][2] ?>"></td>
        </tr>
        <tr>
        	<td>Plan:</td>
        	<td>
    			<select name="plan" required>
    				<option value="0" selected>Básico</option>
    				<option value="1">Profesional</option>
    				<option value="2">Premium</option>
    				<?php
    				    if($_SESSION["user"] == "admin"){
    				        echo "<option value='3'>Máster</option>";
    				    }
    				?>
    			</select>
    		</td>
        </tr>
        <?php
            if($_SESSION["tipouser"] == "Máster"){
                echo "
                    <tr>
                    	<td>Estado:</td>
                    	<td>
        				<select name='estado' required>
        					<option value='A' selected>Activo</option>
        					<option value='B'>Bloqueado</option>
        					<option value='I'>Inactivo</option>
        				</select>
    			</td>
                    </tr>
                ";
            }
        ?>
    </table>
	<br>
	<input type="hidden" name="clave" value="<?= $_GET["id"] ?>">
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