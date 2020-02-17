<?php
    if(!isset($_SESSION["user"])){
        echo "Error: no se puede acceder sin usuario activo o si el usuario no es administrador. \n Volviendo a inicio...";
        header("Refresh:0; url=index.php");
    }else{
        if(!isset($_GET["id"])){
            echo "Error: ningún envío de datos. \n Volviendo a inicio...";
            header("Refresh:0; url=index.php");
        }else{
            $datosusuario = ModeloUserDB::UserGet($_GET["id"]);
            //var_dump($datosusuario);
        }
        
    }
    //Guardo la salida en un buffer(en memoria)
    //[No se envia al navegador]
    ob_start();
    
?>
<form action='index.php'> 
    <table>
    	<tr>
        	<td>Identificador de usuario:</td>
        	<td style="color: black;"><?= $datosusuario[0] ?></td>
        </tr>
        <tr>
        	<td>Contraseña:</td>
        	<td><input type="password" name="pass" value="<?= $datosusuario[1] ?>"></td>
        </tr>
        <?php 
            if(unserialize($_SESSION["user"])->getPlan() != "Máster"){
                echo "<tr>
                <td>Nueva contraseña:</td>
                <td><input type='password' name='pass2'></td>
                </tr>";
            }
        ?>
        <tr>
        	<td>Nombre de usuario:</td>
        	<td><input type="text" name="nombre" value="<?= $datosusuario[2] ?>"></td>
        </tr>
        <tr>
        	<td>Correo:</td>
        	<td><input type="email"  name="mail" value="<?= $datosusuario[3] ?>"></td>
        </tr>
        <tr>
        	<td>Plan:</td>
        	<td>
    			<select name="plan" required>
    				<option value="0" selected>Básico</option>
    				<option value="1">Profesional</option>
    				<option value="2">Premium</option>
    				<?php
    				    if(unserialize($_SESSION["user"])->getPlan() == "Máster"){
    				        echo "<option value='3'>Máster</option>";
    				    }
    				?>
    			</select>
    		</td>
        </tr>
        <?php
            if(unserialize($_SESSION["user"])->getPlan() == "Máster"){
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
	<input type="hidden" name="id" value="<?= $datosusuario[0] ?>">
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