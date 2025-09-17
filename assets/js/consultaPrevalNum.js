const fileExcelClickPrevalNum = document.getElementById('excelFilePrevalNum');
const conTablePrevalNum = document.getElementById('conTablePrevalNum');
const sendPrevalNum = document.getElementById('sendPrevalNum');
const conTablePrevalNumFound = document.getElementById('conTablePrevalNumFound');
const tbodyPrevalNum = document.getElementById('tbodyPrevalNum');
const tbodyPrevalNumFound = document.getElementById('tbodyPrevalNumFound');
const tbodyPrevalNumReject = document.getElementById('tbodyPrevalNumReject');
const conTablePrevalNumReject = document.getElementById('conTablePrevalNumReject');
const addNumPrevalNum = document.getElementById('addNumPrevalNum');
const excelFilePrevalNum = document.getElementById('excelFilePrevalNum');
const inputNumPrevalNum = document.getElementById('inputNumPrevalNum');
const alertNumberPrevalNum = document.getElementById('alertPrevalNum');

let numbersList = {};

fileExcelClickPrevalNum.addEventListener("click", () => {
    conTablePrevalNum.hidden = false;
});

sendPrevalNum.addEventListener("click", () => {
    let showLoading = function(){
        Swal.fire({
            title: 'Espere un momento por favor',
            html: 'Buscando registros...',// add html attribute if you want or remove
            allowOutsideClick: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            willOpen: () => {
                Swal.showLoading()
            },
        });
    }
    showLoading();
    let dataPV = new FormData();
    let buscaCompany = {dataComp: []};
    let numsRejected = [];
    let htmlContentRejected = "";
    let countPhones = tbodyPrevalNum.childElementCount;
    if(countPhones > 0){
        for (let n = 0; n < countPhones; n++) {
            buscaCompany.dataComp.push(tbodyPrevalNum.children[n].childNodes[1].innerText);
        }
        if(inputNumPrevalNum.value != "" && inputNumPrevalNum.value.length == 10) {
            buscaCompany.dataComp.push(inputNumPrevalNum.value);
            countPhones += 1;
        }
    } else {
        if(inputNumPrevalNum.value != "" && inputNumPrevalNum.value.length == 10) {
            countPhones += 1;
        }
        buscaCompany.dataComp.push(inputNumPrevalNum.value);
    }

    // Eliminar duplicados creando un nuevo conjunto (Set) a partir de buscaCompany.dataComp.
    // Un Set es una colección de valores que no permite duplicados.
    // Utiliza el operador de propagación (...) para convertir el Set de nuevo en una matriz array.
    buscaCompany.dataComp = [...new Set(buscaCompany.dataComp)].filter(function(item) {
        return item !== null && item !== "" && item !== undefined;
    });
    numsRejected = buscaCompany.dataComp;
    dataPV.append("buscaCompany", JSON.stringify(buscaCompany));

    conTablePrevalNum.hidden = true;
    conTablePrevalNumFound.hidden = false;
    conTablePrevalNumReject.hidden = false;
    addNumPrevalNum.disabled = true;
    excelFilePrevalNum.disabled = true;
    sendPrevalNum.disabled = true;

    // let contentBody = tbodyPrevalNum.innerHTML;
    // let contentTableN = "";
    // console.log(contentBody);
    // for (let i = 0; i < contentBody.length; i++) {
    // let contentTr = contentBody[i].children;
    // contentTableN = "";
    // console.log(contentTr);
    console.log(dataPV.get("buscaCompany"));
    console.log(dataPV);
    consultaPrevalNum(dataPV).then(res =>{
        swal.close();
        console.log(res);
        if (res === false) {
            return Swal.fire("Warning","Error al consultar, vuelva a intentarlo","warning");
        }else{
            if (res.msg == 'Execution Error') {
                return Swal.fire("Warning","Error en la ejecución del programa","warning");
            }else{
                // console.log(res.tabla);
                // Set HTML content
                tbodyPrevalNumFound.innerHTML = res.tabla;
            }
            let countPhonesFounded = tbodyPrevalNumFound.childElementCount;
            // si (hay registros en la tabla Encontrados) y (el número de registros Encontrados son diferentes a los registros Buscados)
            if(countPhonesFounded > 0 && countPhonesFounded !== countPhones){
                for (let n = 0; n < countPhonesFounded; n++) {
                    if(numsRejected.includes(tbodyPrevalNumFound.children[n].childNodes[1].innerText)){
                        // Encontrar el índice del string en el array
                        let index = numsRejected.indexOf(tbodyPrevalNumFound.children[n].childNodes[1].innerText);
                        // Eliminar el string del array
                        if (index > -1) {
                            numsRejected.splice(index, 1);
                        }
                    }
                }
                console.log(numsRejected);
            // si (hay registros en la tabla Encontrados) y (el número de registros Encontrados son iguales a los registros Buscados)
            } else if(countPhonesFounded === countPhones){
                // no habrá numeros rechazados en la busqueda
                numsRejected = [];
            }

            // si no hay numeros encontrados en la BD, no se muestra su tabla Found
            if(countPhonesFounded == 0) {
                conTablePrevalNumFound.hidden = true;
            }
            // si no hay numeros NO encontrados en la BD, no se muestra su tabla Reject
            if(numsRejected.length == 0) {
                conTablePrevalNumReject.hidden = true;
            }

            for (let m = 0; m < numsRejected.length; m++) {
                htmlContentRejected += `<tr><td>${numsRejected[m]}</td></tr>`;
            }
            console.log(htmlContentRejected);
            tbodyPrevalNumReject.innerHTML = htmlContentRejected;

        }
    }).catch(err => {
        console.log(err);
        Swal.fire("Warning","Error en la ejecución del programa","warning");
    });
});

// const deleteBtnPrevalNum = document.querySelectorAll(".delete-button-PrevalNum");
// deleteBtnPrevalNum.forEach((btn) => {
//   console.log(btn);
//   btn.addEventListener("click", (e) => {
//     console.log(e.target);
//     console.log(e.parentElement.parentElement);
//     e.parentElement.parentElement.remove();
//   });
// });

const fileExcelClickE = document.querySelector("#excelFileEliminacion");
const conTableDos = document.querySelector(".conTableYE");
fileExcelClickE.addEventListener("click", () => {
    conTableDos.style.display = "inline";
});

const cleanTablePrevalNum = document.getElementById('cleanTablePrevalNum');
cleanTablePrevalNum.addEventListener("click", () => {
    conTablePrevalNum.hidden = true;
    conTablePrevalNumFound.hidden = true;
    conTablePrevalNumReject.hidden = true;
    addNumPrevalNum.disabled = false;
    excelFilePrevalNum.disabled = false;
    sendPrevalNum.disabled = false;
    inputNumPrevalNum.value = "";
    excelFilePrevalNum.value = "";
    tbodyPrevalNum.innerHTML = "";
    tbodyPrevalNumFound.innerHTML = "";
    tbodyPrevalNumReject.innerHTML = "";
});

const prevalidation = document.getElementById('btnPrevalidation');
const prevalidationView = document.querySelector(".item10");
prevalidation.addEventListener("click", () => {
    // if (mostrar) {
    prevalidationView.style.display = "inline";
    mensaje.style.display = "none";
    mensajeEliminar.style.display = "none";
    //mensaje3.style.display = "none";
    //mensajeDoc.style.display = "inline";
    mensajeConsultaEliminados.style.display = "none";
    mensajeConsulta.style.display = "none";
    mensajeConsultaProgramables.style.display = "none";
    //mensaje.classList.toggle("item1");

    // }
});

async function consultaPrevalNum(formdata,url = '../php/consultaPrevalNum.php'){
    //let url = '../php/consultaSPN.php';

    console.log(...formdata);

    let res = await fetch(url, {
    method: "POST",
    body: formdata,
    /*  headers:{
        "Content-Type":"application/json"
    } */
    })   
    if (res.ok){
        console.log(res);
        // let responseText = await res.text(); // Cambia de res.json() a res.text() para verificar la respuesta
        // console.log(responseText); // Solo se puede generar uno de los await's
        let text = await res.json();// res.text()
        console.log(text);
        return text;
    }else{
        console.log(res);
        Swal.fire("Warning","Ocurrió un error en la red, vuelva a intentar la importación","warning");
        return false;
    }
    //return text;

}

class RowPrevalNum{
    constructor(row){
        this.row = row;
    }
    Telefonos(){
        return this.row[0];
    }
}
class RowCollectionPrevalNum{
    constructor(rows){
        this.rows = rows;
    }
    first(){
        return new RowPrevalNum(this.rows[0]);
    }
    get(index){
        return new  RowPrevalNum(this.rows[index]);
    }
    count(){
        return this.rows.length;
    }
}
class PortabilidadExcelPrevalNum{
    constructor(content){
        this.content = content;
    }
    header(){
        return this.content[0];
    }
    rows(){
        //return this.content.slice(1,this.content.length);
        return new RowCollectionPrevalNum(this.content.slice(1,this.content.length));
    }
}

class ExcelPrinterPrevalNum{
    static print(tableId, excel){
        const table = document.getElementById(tableId);
        let addHeader = false;
        //si la tabla está vacía, se reinicia el objeto numbersList
        if(tbodyPrevalNum.children.length == 0) {
            numbersList = {};
            numbersList[1] = [];
        }
        for(let key in numbersList) {
            if(numbersList[key].length < 100 && addHeader === false) {
                numbersList[key].push(excel.header()[0]);
                addHeader = true;
                break;
            }
        }
        if(addHeader === false) {
            numbersList[Object.keys(numbersList).length + 1] = [];
            numbersList[Object.keys(numbersList).length].push(excel.header()[0]);
        }
        
        // Si el excel tiene un encabezado ...
        if(typeof(excel.header()[0]) === 'number' && excel.header()[0].toString().length === 10) {
            table.innerHTML += `
                <tr>
                    <td>${excel.header()[0]}</td>
                    <td><button class="delete-button-prevalNum btn btn-danger m-0 px-3 py-1" onClick="{console.log(this.parentElement.parentElement.remove())}"><i class="ni ni-fat-remove"></i></button</td>
                </tr>
            `
        } else {
            table.innerHTML += `
                <tr class="table-danger">
                    <td>${excel.header()[0]}</td>
                    <td><button class="delete-button-prevalNum btn btn-danger m-0 px-3 py-1" onClick="{console.log(this.parentElement.parentElement.remove())}"><i class="ni ni-fat-remove"></i></button</td>
                </tr>
            `
        }

        // Por cada una de las filas del excel...
        for (let index = 0; index < excel.rows().count(); index++) {
            const row = excel.rows().get(index);
            let addBody = false;
            for(let key in numbersList) {
                if(numbersList[key].length < 100 && addBody === false) {
                    numbersList[key].push(row.Telefonos());
                    addBody = true;
                    break;
                }
            }
            if(addBody === false) {
                numbersList[Object.keys(numbersList).length + 1] = [];
                numbersList[Object.keys(numbersList).length].push(row.Telefonos());
            }

            if(typeof(row.Telefonos()) === 'number' && row.Telefonos().toString().length === 10) {
                table.innerHTML += `
                    <tr>
                        <td>${row.Telefonos()}</td>
                        <td><button class="delete-button-prevalNum btn btn-danger m-0 px-3 py-1" onClick="{console.log(this.parentElement.parentElement.remove())}"><i class="ni ni-fat-remove"></i></button</td>
                    </tr>
                `
            } else {
                table.innerHTML += `
                    <tr class="table-danger">
                        <td>${row.Telefonos()}</td>
                        <td><button class="delete-button-prevalNum btn btn-danger m-0 px-3 py-1" onClick="{console.log(this.parentElement.parentElement.remove())}"><i class="ni ni-fat-remove"></i></button</td>
                    </tr>
                `
            }
            // console.log(excel.rows().get(index).Telefonos());
            // console.log(excel.rows().get(index));
        }
        console.log(numbersList);
    }
}

class NumberPrinterPrevalNum{
    static print(tableId, num){
        const table = document.getElementById(tableId);
        const newNum = parseInt(num);
        let addBody = false;
        //si la tabla está vacía, se reinicia el objeto numbersList
        if(tbodyPrevalNum.children.length == 0) {
            numbersList = {};
            numbersList[1] = [];
        }
        for(let key in numbersList) {
            if(numbersList[key].length < 100 && addBody === false) {
                numbersList[key].push(num);
                addBody = true;
                break;
            }
        }
        if(addBody === false) {
            numbersList[Object.keys(numbersList).length + 1] = [];
            numbersList[Object.keys(numbersList).length].push(num);
        }
        // console.log(typeof(newNum));
        // console.log(typeof(num));

        // console.log(typeof(newNum) === 'number');
        // console.log(newNum.toString().length === 10);
        if(typeof(newNum) === 'number' && num.length === 10) {
            table.innerHTML += `
                <tr>
                    <td>${num}</td>
                    <td><button class="delete-button-prevalNum btn btn-danger m-0 px-3 py-1" onClick="{console.log(this.parentElement.parentElement.remove())}"><i class="ni ni-fat-remove"></i></button</td>
                </tr>
            `
        } else {
            table.innerHTML += `
                <tr class="table-danger">
                    <td>${num}</td>
                    <td><button class="delete-button-prevalNum btn btn-danger m-0 px-3 py-1" onClick="{console.log(this.parentElement.parentElement.remove())}"><i class="ni ni-fat-remove"></i></button</td>
                </tr>
            `
        }
        console.log(numbersList);
    }
}

fileExcelClickPrevalNum.addEventListener('change', async function () {
    let showLoading = function(){
        Swal.fire({
            title: 'Espere un momento por favor',
            html: 'agregando registros...',// add html attribute if you want or remove
            allowOutsideClick: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            willOpen: () => {
                Swal.showLoading()
            },
        });
    }
    showLoading();
    const content = await readXlsxFile( fileExcelClickPrevalNum.files[0] );
    const portabilidadexcel = new PortabilidadExcelPrevalNum(content);

    const excel = new PortabilidadExcel(content);
    console.log(ExcelPrinterPrevalNum.print('tbodyPrevalNum',excel));
    // console.log(portabilidadexcel.rows().rows);
    // console.log(portabilidadexcel.rows().first().Telefonos());
    swal.close();
});


addNumPrevalNum.addEventListener('click', async function () {
    const content = await document.getElementById('inputNumPrevalNum').value;
    const conTablePrevalNum = document.getElementById('conTablePrevalNum');
    if(content.length !== 10) {
        alertNumberPrevalNum.hidden = false;
        alertNumberPrevalNum.innerHTML = '<p>El número debe ser de 10 dígitos numericos.</p>';
        return;
    } else {
        alertNumberPrevalNum.hidden = true;
        conTablePrevalNum.hidden = false;
        console.log(NumberPrinterPrevalNum.print('tbodyPrevalNum',content));
    }
    document.getElementById('inputNumPrevalNum').value = "";
});