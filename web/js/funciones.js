/**
 * Funciones auxiliares de javascripts 
 */
function confirmarBorrar(nombre,id){
	if(confirm("¿Quieres eliminar el usuario:  "+nombre+"?")){
		document.location.href="?orden=Borrar&id="+id;
	}
}

function confirmarBorrarFichero(nombre){
	if(confirm("¿Quieres eliminar el archivo: "+nombre+"?")){
		document.location.href="?orden=Borrar&nombre="+nombre;
	}
}