function createPaginador(divPaginador, tabla, tamPagina = 100)
{
    this.miDiv = divPaginador;      //un DIV donde irán controles de paginación
    this.tabla = tabla;             //la tabla a paginar
    this.tamPagina = tamPagina;     //el tamaño de la página (filas por página)
 
    this.Mostrar = function()
    {
        //Crear la tabla
        let tblPaginador = document.createElement('table');
 
        //Agregar una fila a la tabla
        let fil = tblPaginador.insertRow(tblPaginador.rows.length);
 
        //Ahora, agregar las celdas que serán los controles
        let ant = fil.insertCell(fil.cells.length);
        ant.innerHTML = 'Anterior';
        ant.className = 'pag_btn'; //con eso le asigno un estilo
 
        let num = fil.insertCell(fil.cells.length);
        num.innerHTML = ''; //en rigor, aún no se el número de la página
        num.className = 'pag_num';
 
        let sig = fil.insertCell(fil.cells.length);
        sig.innerHTML = 'Siguiente';
        sig.className = 'pag_btn';
        //Como ya tengo mi tabla, puedo agregarla al DIV de los controles
        this.miDiv.appendChild(tblPaginador);
    }
}