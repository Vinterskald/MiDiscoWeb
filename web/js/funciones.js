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
	var nuevo = prompt("Introduce el nuevo nombre: ", archivo);
	
	if(nuevo == null || nuevo == ""){
		alert("Ningún nombre nuevo introducido.");
		document.location.href="?";
	}else{
		alert("Nombre del archivo modificado.");
		document.location.href="index.php?orden=Renombrar&nombre="+archivo;
	}
}