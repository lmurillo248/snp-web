class PortabilidadExcel{
    constructor(content){
        this.content = content;
    }
    header(){
        return this.content[0];
    }
    rows(){
        //return this.content.slice(1,this.content.length);
        return new RowCollection(this.content.slice(1,this.content.length));
    }
}

class RowCollection{
    constructor(rows){
        this.rows = rows;
    }
    first(){
        return new Row(this.rows[0]);
    }
    get(index){
        return new  Row(this.rows[index]);
    }
    count(){
        return this.rows.length;
    }
}

class Row{
    constructor(row){
        this.row = row;
    }
    Telefonos(){
        return this.row[0];
    }
}

class ExcelPrinter{
    static print(tableId, excel){
        const table = document.getElementById(tableId);
        console.log(table);
        /* excel.header().forEach(title => {
            table.querySelector("thead>tr").innerHTML += `<td>${title}</td>`
        }); */
        console.log(table);
        table.querySelector('tbody').innerHTML = "";

        for (let index = 0; index < excel.rows().count(); index++) {
            const row = excel.rows().get(index);
            table.querySelector('tbody').innerHTML += `
                <tr>
                    <td>${row.Telefonos()}</td>
                </tr>
            `
        }
    }
}

const appExcel = document.getElementById('excelFile');
appExcel.addEventListener('change', async function () {
    const content = await readXlsxFile( appExcel.files[0] );
    const portabilidadexcel = new PortabilidadExcel(content);

    const excel = new PortabilidadExcel(content);
    document.getElementById('numeroFrom').value = "";
    document.getElementById('numeroTo').value = "";
    console.log(ExcelPrinter.print('tablePhoneId',excel));
    console.log(portabilidadexcel.rows().rows);
    console.log(portabilidadexcel.rows().first().Telefonos());
});

const appExcelEliminar = document.getElementById('excelFileEliminacion');
appExcelEliminar.addEventListener('change', async function () {
    const content = await readXlsxFile( appExcelEliminar.files[0] );
    const portabilidadexcel = new PortabilidadExcel(content);

    const excel = new PortabilidadExcel(content);
    console.log(ExcelPrinter.print('tablePhoneIdDos',excel));
    console.log(portabilidadexcel.rows().rows);
    console.log(portabilidadexcel.rows().first().Telefonos());
});

// Obtenemos una referencia al elemento
const $elemento = document.querySelector("#tablePhoneId");
const $elementoE = document.querySelector("#tablePhoneIdDos");
const $numeroPara = document.querySelector("#numeroFrom");
const tablaY = document.querySelector("#conTable");
const tablaYE = document.querySelector("#conTableDos");

// El botón solo es para la demostración
const $btnLimpiar = document.querySelector("#cleanTable");
const $btnLimpiarE = document.querySelector("#cleanTableEliminacion");

// Y en el click, limpiamos
$btnLimpiar.addEventListener("click", () => {
    clearTable($elemento, "excelFile");
});

$btnLimpiarE.addEventListener("click", () => {
    clearTable($elementoE, "excelFileEliminacion");
    tablaYE.style.display = "none";
});

function clearTable(tabla, idComponente){
    tabla.querySelector('tbody').innerHTML = "";
    document.getElementById(idComponente).value = "";
}

$numeroPara.addEventListener("change", () => {
    
    if (document.getElementById('numeroFrom').value != "") {
        $elemento.innerHTML = "";
        document.getElementById('excelFile').value = "";
        tablaY.style.display = "none";
    } else if (document.getElementById('numeroFrom').value = ""){
        const excel = new PortabilidadExcel(content);
    }
    

});


