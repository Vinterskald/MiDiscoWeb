/**
 * Funciones auxiliares de javascripts 
 */
function confirmarBorrar(nombre,id){
	if(confirm("¿Quieres eliminar el usuario:  "+nombre+"?")){
		document.location.href="index.php?orden=Borrar&id="+id;
	}else{
		document.location.href="index.php";
	}
}

function confirmarBorrarFichero(nombre){
	if(confirm("¿Quieres eliminar el archivo: "+nombre+"?")){
		document.location.href="index.php?orden=Borrar&nombre="+nombre;
	}else{
		document.location.href="index.php";
	}
}

function renombrar(archivo){
	var nuevo = prompt("Escribe el nuevo nombre del archivo:", archivo);
	document.location.href="index.php?orden=Renombrar&nuevo="+nuevo+"&archivo="+archivo;
}