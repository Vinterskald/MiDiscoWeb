<?php
    // Guardo la salida en un buffer(en memoria)
    // No se envia al navegador
    ob_start();
?>
<form action="index.php" method="POST" enctype="multipart/form-data">
	<p>Selecciona el archivo que quieres subir</p>
	<input type="file" name="archivo">
	<input type="submit" name="submit" value="Subir"> 
</form>
<br>
<hr>
<br>
<a href="index.php" style="text-decoration: none;"><button>Volver</button></a>
<?php
    // Vacio el bufer y lo copio a contenido
    // Para que se muestre en div de contenido de la página principal
    $contenido = ob_get_clean();
    include_once "principal.php";
?>