const downloadBttnFoundPevalNum = document.getElementById("downloadTblPrevalNumFound");
const downloadBttnRejectPevalNum = document.getElementById("downloadTblPrevalNumReject");


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

        if(typeof(excel.header()[0]) === 'number' && excel.header()[0].toString().length === 10) {
            table.innerHTML = `
                <thead>
                    <tr></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>${excel.header()[0]}</td>
                    </tr>
                </tbody>
            `
        } else {
            table.innerHTML = `
                <thead>
                    <tr></tr>
                </thead>
                <tbody>
                    <tr class="table-danger">
                        <td>${excel.header()[0]}</td>
                    </tr>
                </tbody>
            `
        }

        for (let index = 0; index < excel.rows().count(); index++) {
            const row = excel.rows().get(index);
            if(typeof(row.Telefonos()) === 'number' && row.Telefonos().toString().length === 10) {
                table.querySelector('tbody').innerHTML += `
                    <tr>
                        <td>${row.Telefonos()}</td>
                    </tr>
                `
            } else {
                table.querySelector('tbody').innerHTML += `
                    <tr class="table-danger">
                        <td>${row.Telefonos()}</td>
                    </tr>
                `
            }
            // console.log(excel.rows().get(index).Telefonos());
            // console.log(excel.rows().get(index));
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
    // console.log(portabilidadexcel.rows().rows);
    // console.log(portabilidadexcel.rows().first().Telefonos());
});

const appExcelEliminar = document.getElementById('excelFileEliminacion');
appExcelEliminar.addEventListener('change', async function () {
    const content = await readXlsxFile( appExcelEliminar.files[0] );
    const portabilidadexcel = new PortabilidadExcel(content);

    const excel = new PortabilidadExcel(content);
    console.log(ExcelPrinter.print('tablePhoneIdDos',excel));
    // console.log(portabilidadexcel.rows().rows);
    // console.log(portabilidadexcel.rows().first().Telefonos());
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
    tablaY.style.display = "none";
});

$btnLimpiarE.addEventListener("click", () => {
    clearTable($elementoE, "excelFileEliminacion");
    tablaYE.style.display = "none";
});

function clearTable(tabla, idComponente){
    tabla.innerHTML = "";
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

function createExcel(excThead, excTbody, excName) {
    const TODAY = new Date();
    const TODAY_DAY = TODAY.getDate() < 10 ? ("0" + TODAY.getDate()) : TODAY.getDate();
    const TODAY_MONTH = (TODAY.getMonth() + 1) < 10 ? ("0" + (TODAY.getMonth() + 1)) : (TODAY.getMonth() + 1);
    const TODAY_DATE = TODAY.getFullYear() + "-" + TODAY_MONTH + "-" + TODAY_DAY;
    
    // Datos que deseas enviar al servidor
    const currentTbody = document.getElementById(excTbody).innerHTML;
    const data = {
        thead: excThead,
        tbody: currentTbody
    };
    // let excelName = document.getElementById('idTitle').innerHTML.toLowerCase().replaceAll(" / ", "-").replaceAll(" ", "_");
    let excelName = excName;
    excelName = excelName + "_" + TODAY_DATE + ".xls";
    
    console.log(data);
    console.log(excelName);
    console.log(excTbody);
    // Crear una solicitud AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "getExcelFile.php", true);
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhr.responseType = 'blob';

    xhr.onload = function() {
        if (xhr.status === 200) {
            // Crear un enlace para descargar el archivo
            const url = window.URL.createObjectURL(xhr.response);
            const a = document.createElement("a");
            a.style.display = "none";
            a.href = url;
            a.download = excelName;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        }
    };

    // Enviar los datos al servidor
    xhr.send(JSON.stringify(data));
}

// Botón para descargar el archivo Excel En Verificación-Encontrados ---------------------------------------------
const theadExcelFound = '<tr><th colspan="3">ENCONTRADOS</th></tr><tr><th>Numero</th><th>Operador</th><th>Compania</th></tr>';
// if(document.getElementById("downloadTblPrevalNumFound")){
    downloadBttnFoundPevalNum.addEventListener("click", () => {
        console.log("download founded");
        createExcel(theadExcelFound, "tbodyPrevalNumFound", "num_encontrados");
    });
// }

// Botón para descargar el archivo Excel En Verificación-Rechazados ---------------------------------------------
const theadExcelReject = '<tr><th>NUMEROS RECHAZADOS</th></tr>';
// if(document.getElementById("downloadTblPrevalNumReject")){
    downloadBttnRejectPevalNum.addEventListener("click", () => {
        console.log("download rejected");
        createExcel(theadExcelReject, "tbodyPrevalNumReject", "num_rechazados");
    });
// }

//SE EJECUTA UNICAMENTE LA CREACION DEL EXCEL