const btnEnviar = document.querySelector('#enviarApi');
const docSizeOne = document.querySelector('#docSizeOne');
const docSizeTwo = document.querySelector('#docSizeTwo');
const docSizeThree = document.querySelector('#docSizeThree');
const docSizeFour = document.querySelector('#docSizeFour');
let fileSizeOne = 0;
let fileSizeTwo = 0;
let fileSizeThree = 0;
let fileSizeFour = 0;


const sizeValidate = (file, identificador, docSelector)=>{
    let id = document.getElementById(identificador);
    // let fileSize = (idFile.size)/(1024*1024);
    console.log(file.size);
    if(file.size > 4 * 1024 * 1024) {
        id.value = '';
    	Swal.fire("Warning","Los archivos no pueden valer más de 4 Mega bytes de tamaño","warning");
        return false;
    } else {
        return true;
    }
}

document.querySelector('#formFileSmIdentifica').addEventListener('change', ()=>{
    let fileIdentifica = document.getElementById('formFileSmIdentifica').files[0];
    if (document.getElementById('formFileSmIdentifica').value !== "") {
        let correctSize = sizeValidate(fileIdentifica,"formFileSmIdentifica", "selectDocumentOne");
        if(correctSize) {
            fileSizeOne = (fileIdentifica.size)/(1024*1024);
            docSizeOne.value = `${fileSizeOne.toFixed(2)} MB`;
        } else {
            docSizeOne.value = "";
            fileSizeOne = 0;
        }
    }
});

document.querySelector('#formFileSmSolOriginal').addEventListener('change', ()=>{
    let fileIdentifica = document.getElementById('formFileSmSolOriginal').files[0];
    if (document.getElementById('formFileSmSolOriginal').value !== "") {
        let correctSize = sizeValidate(fileIdentifica,"formFileSmSolOriginal", "selectDocumentTwo");
        if(correctSize) {
            fileSizeTwo = (fileIdentifica.size)/(1024*1024);
            docSizeTwo.value = `${fileSizeTwo.toFixed(2)} MB`;
        } else {
            docSizeTwo.value = "";
            fileSizeTwo = 0;
        }
    }
});

document.querySelector('#formFileSmJurada').addEventListener('change', ()=>{
    let fileIdentifica = document.getElementById('formFileSmJurada').files[0];
    if (document.getElementById('formFileSmJurada').value !== "") {
        let correctSize = sizeValidate(fileIdentifica,"formFileSmJurada", "selectDocumentThree");
        if(correctSize) {
            fileSizeThree = (fileIdentifica.size)/(1024*1024);
            docSizeThree.value = `${fileSizeThree.toFixed(2)} MB`;
        } else {
            docSizeThree.value = "";
            fileSizeThree = 0;
        }
    }
});

document.querySelector('#formFileSmRecovery').addEventListener('change', ()=>{
    let fileIdentifica = document.getElementById('formFileSmRecovery').files[0];
    if (document.getElementById('formFileSmRecovery').value !== "") {
        let correctSize = sizeValidate(fileIdentifica,"formFileSmRecovery", "selectDocumentFour");
        if(correctSize) {
            fileSizeFour = (fileIdentifica.size)/(1024*1024);
            docSizeFour.value = `${fileSizeFour.toFixed(2)} MB`;
        } else {
            docSizeFour.value = "";
            fileSizeFour = 0;
        }
    }
});

btnEnviar.addEventListener('click', async ()=>{
    const numTel = document.getElementById('numeroFrom');
    const numTo = document.getElementById('numeroTo');
    const numNip = document.getElementById('nip');
    //const client = document.getElementById('cliente').value;
    const donante = document.getElementById('donador').value;
    // Tipo de suscriptor
    const radio1fisica = document.querySelector('#exampleRadios1');
    const radio2moral = document.querySelector('#exampleRadios2');
    const radio3gobierno = document.querySelector('#exampleRadios3');
    // numero activo o desconectado
    const radio4activo = document.querySelector('#exampleRadios4');
    const radio5 = document.querySelector('#exampleRadios5');
    const PortTypeId = document.querySelector('#PortType').value;
    const ido = document.querySelector('#ido').value;
    const fechaEntregado = document.querySelector('#fechaEntregado').value;
    const comentarios = document.querySelector('#comentarios').value.replace(/[^a-zA-Z0-9 ]/g, "");
    const numsByExcelFile = document.getElementById('excelFile');
    
    let flagRadio = 0;
    let activoDesconectado = "";
    let personaMoral = "";

    //validación para que los controles estén llenos
    if (radio1fisica.checked) {
        
        if (document.getElementById('numeroFrom').value === "") {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>El campo <b>From:</b> no puede estar vacío</p>',
                footer: '<p>Campos de Teléfono</p>'
            })
            return false;
        }
        if (document.getElementById('numeroTo').value === "") {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>El campo <b>To:</b> no puede estar vacío, coloque el número del campo <b>From:</b> para mandar un solo número</p>',
                footer: '<p>Campos de Teléfono</p>'
            })
            return false;
        }
        if (numTo.value.length !== 10 || numTel.value.length !== 10) {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>Los números de teléfono a portar deben formarse de <b>10 dígitos numéricos</b></p>',
                footer: '<p>Campos de Teléfono</p>'
            })
            return false;
        }
        if(numTel.value !== numTo.value) {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>Las personas físicas solo pueden tener 1 solo número, <b>coloque el mismo número</b> en el <b>From</b> y en el <b>To</b></p>',
                footer: '<p>Campos de Teléfono</p>'
            })
            return false;
        }
        if(radio4activo.checked) {
            if ((numNip.value !== "Attachments") && (numNip.value.length !== 4)) {
                Swal.fire({
                    icon: 'error',
                    //title: 'Oops...',
                    text: 'Something went wrong!',
                    html: '<h4>Oops...</h4>' + '<p>El NIP debe formarse de <b>4 dígitos numéricos</b></p>',
                    footer: '<p>Debe llenar el NIP</p>'
                })
                return false;
            }
        }
    }

    if (radio2moral.checked || radio3gobierno.checked || radio5.checked) {
        /*if (document.querySelector('#selectDocumentOne').value == "" || document.querySelector('#selectDocumentTwo').value == "" || document.querySelector('#selectDocumentThree').value == "" || (document.getElementById('attachDocRecovery').style.display !== "none"  && document.querySelector('#selectDocumentFour').value == "")) {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>Los selectores de documentos no pueden estar vacíos</p>',
                footer: '<p>Selectores de Documentos</p>'
            })
            return false;
        }*/
        if((numNip.value == "Attachments") && (document.getElementById('formFileSmJurada').value == "" || document.getElementById('formFileSmIdentifica').value == "" || document.getElementById('formFileSmSolOriginal').value == "")) {
            Swal.fire({
                icon: 'error',
                //title: '<p>Oops...</p>',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>Debe seleccionar los archivos adjuntos correspondientes en la sección de <b>Attachments</b></p>',
                footer: '<p>Sección de Archivos Adjuntos</p>'
            })
            return false;
        } else if(
                (numNip.value == "Attachments") && (
                (document.getElementById('formFileSmJurada').files[0].type !== 'application/pdf' && document.getElementById('formFileSmJurada').files[0].type !== 'image/jpeg') || 
                (document.getElementById('formFileSmIdentifica').files[0].type !== 'application/pdf' && document.getElementById('formFileSmIdentifica').files[0].type !== 'image/jpeg') || 
                (document.getElementById('formFileSmSolOriginal').files[0].type !== 'application/pdf' && document.getElementById('formFileSmSolOriginal').files[0].type !== 'image/jpeg'))
        ){
            console.log(document.getElementById('formFileSmJurada').files[0].type !== 'application/pdf' && document.getElementById('formFileSmJurada').files[0].type !== 'image/jpeg');
            console.log(document.getElementById('formFileSmIdentifica').files[0].type !== 'application/pdf' && document.getElementById('formFileSmIdentifica').files[0].type !== 'image/jpeg');
            console.log(document.getElementById('formFileSmSolOriginal').files[0].type !== 'application/pdf' && document.getElementById('formFileSmSolOriginal').files[0].type !== 'image/jpeg');
            console.log(numNip.value);
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>Los archivos deben ser solo de tipo <b>PDF o JPG</b></p>',
                footer: '<p>Sección de Archivos Adjuntos</p>'
            })
            return false;
        }
        if(document.getElementById('attachDocRecovery').hidden == false) {
            if(document.getElementById('formFileSmRecovery').value == "") {
                Swal.fire({
                    icon: 'error',
                    //title: 'Oops...',
                    text: 'Something went wrong!',
                    html: '<h4>Oops...</h4>' + '<p>Debe seleccionar los archivos adjuntos correspondientes en la sección de <b>Attachments</b></p>',
                    footer: '<p>Sección de Archivos Adjuntos</p>'
                })
                return false;
            } else if(document.getElementById('formFileSmRecovery').files[0].type !== 'application/pdf' && document.getElementById('formFileSmRecovery').files[0].type !== 'image/jpeg') {
                Swal.fire({
                    icon: 'error',
                    //title: 'Oops...',
                    text: 'Something went wrong!',
                    html: '<h4>Oops...</h4>' + '<p>Los archivos deben ser solo de tipo <b>PDF o JPG</b></p>',
                    footer: '<p>Sección de Archivos Adjuntos</p>'
                })
                return false;
            }
        }

        if ((fileSizeOne + fileSizeTwo + fileSizeThree + fileSizeFour) > 4) {
            console.log(`${fileSizeOne} + ${fileSizeTwo} + ${fileSizeThree} + ${fileSizeFour} = ${fileSizeOne+fileSizeTwo+fileSizeThree+fileSizeFour}`);
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>El total de tamaño de los archivos adjuntos no puede ser mayor a <b>4 Mega Bytes</b></p>' + '<p>Tamaño actual: ' + (fileSizeOne+fileSizeTwo+fileSizeThree+fileSizeFour).toFixed(2) + '</p>',
                footer: '<p>Sección de Archivos Adjuntos</p>'
            })
            return false;
        }
        if (numsByExcelFile.value === "") {
            if (document.getElementById('numeroFrom').value === "") {
                Swal.fire({
                    icon: 'error',
                    //title: 'Oops...',
                    text: 'Something went wrong!',
                    html: '<h4>Oops...</h4>' + '<p>El campo <b>From:</b> no puede estar vacío</p>',
                    footer: '<p>Campos de Teléfono</p>'
                })
                return false;
            }
            if (document.getElementById('numeroTo').value === "") {
                Swal.fire({
                    icon: 'error',
                    //title: 'Oops...',
                    text: 'Something went wrong!',
                    html: '<h4>Oops...</h4>' + '<p>El campo <b>To:</b> no puede estar vacío, coloque el número del campo <b>From:</b> para mandar un solo número</p>',
                    footer: '<p>Campos de Teléfono</p>'
                })
                return false;
            }
            if (numTo.value.length !== 10 || numTel.value.length !== 10) {
                Swal.fire({
                    icon: 'error',
                    //title: 'Oops...',
                    text: 'Something went wrong!',
                    html: '<h4>Oops...</h4>' + '<p>Los números de teléfono a portar deben formarse de <b>10 dígitos numéricos</b></p>',
                    footer: '<p>Campos de Teléfono</p>'
                })
                return false;
            }
        } else {
            let nameFileXlsm = numsByExcelFile.files[0].name;
            let extensionFileXlsm = nameFileXlsm.split('.').pop();
            if(!(numsByExcelFile.files[0].type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || extensionFileXlsm === 'xlsm' || extensionFileXlsm === 'xlsx')){
                Swal.fire({
                    icon: 'error',
                    //title: 'Oops...',
                    text: 'Something went wrong!',
                    html: '<h4>Oops...</h4>' + '<p>El archivo debe ser de tipo <b>XLSX</b></p>',
                    footer: '<p>Archivo Excel</p>'
                })
                return false;
            }
        }
    }

    //desactivar botón enviar
    btnEnviar.disabled = true;

    let timeObject = new Date();
    const milliseconds = 60 * 1000; // 10 seconds = 10000 milliseconds
    timeObject = new Date(timeObject.getTime() - milliseconds);

    let fechaTimeStamp=new Date(new Date(timeObject.getTime() - milliseconds).toString().split('GMT')[0]+' UTC').toISOString().replaceAll('-', '')
    .replaceAll('T', '')
    .replaceAll(':', '')
    .replaceAll('.000Z', '');
    
    //sending form
    let data = new FormData();
    //data.append("cliente",client);
    data.append("donador",donante);
    data.append("PortType",PortTypeId);
    data.append("ido",ido);
    data.append("TransTimestamp",fechaEntregado);
    data.append("comentarios", comentarios);
    data.append("fechaTimeStamp",fechaTimeStamp);

    // radio1fisica && radio4activo = numNip;
    // radio1fisica && radio5desconectado == formFileSmIdentifica, formFileSmSolOriginal, formFileSmJurada;
    // radio2moral && radio4activo = formFileSmIdentifica, formFileSmSolOriginal, formFileSmJurada;
    // radio2moral && radio5desconectado = formFileSmIdentifica, formFileSmSolOriginal, formFileSmJurada, formFileSmRecovery;
    // radio3gobierno && radio4activo = formFileSmIdentifica, formFileSmSolOriginal, formFileSmJurada;
    // radio3gobierno && radio5desconectado = formFileSmIdentifica, formFileSmSolOriginal, formFileSmJurada;

    if (radio2moral.checked) {
        personaMoral = "Y";
        flagRadio = 2; //moral
    }else{
        personaMoral = "N";
    }

    if (radio4activo.checked) {
        activoDesconectado = "N";
    }else{
        activoDesconectado = "Y";
    }

    if (radio1fisica.checked) {
        flagRadio = 1; //física
    }

    if (radio3gobierno.checked) {
        flagRadio = 3; //gobierno
    }
    
    //validación para el envío de la data
    if(radio1fisica.checked && radio4activo.checked) {
    } else {
        data.append("selectDocumentOne","I"); //agregado para el tipo de documento
        data.append("selectDocumentTwo","S"); //agregado para el tipo de documento
        data.append("selectDocumentThree","P"); //agregado para el tipo de documento
        data.append("formFileSmIdentifica", document.getElementById('formFileSmIdentifica').files[0]);
        data.append("formFileSmSolOriginal", document.getElementById('formFileSmSolOriginal').files[0]);
        data.append("formFileSmJurada", document.getElementById('formFileSmJurada').files[0]);

        if (radio2moral.checked && radio5.checked) {
            data.append("selectDocumentFour","R"); //agregado para el tipo de documento
            data.append("formFileSmRecovery", document.getElementById('formFileSmRecovery').files[0]);
        }
    }
    
    if (numsByExcelFile.value !== "") {
        //leemos el archivo para moral
        const content = await readXlsxFile( appExcel.files[0] );
        let jsonBlob = new Blob([JSON.stringify(content)], {type: "application/json"});//text
        data.append("listadoNum", jsonBlob);
        data.append("numTel", "");
        data.append("numTo", "");
    } else {
        data.append("numTel", numTel.value);
        data.append("numTo", numTo.value);
    }

    data.append("personaMoral",personaMoral);
    data.append("activoDesconectado",activoDesconectado);
    data.append("numNip", numNip.value);
    data.append("flagRadio",flagRadio);

    //----------------------------------------AQUÍ EMPIEZA LA FUNCIÓN QUE PROCESA PARA EL PHP-------------------------------
    //call api->php
    ProcessSender(data).then(res =>{
        console.log(res);
        if (res === false || res.xml === false) {
            return Swal.fire("Warning","Error, el proceso se interrumpió; vuelva a intentarlo","warning");
        }else{
            if (res.msg !== 'Execution Error') {
                let portTypeText = document.getElementById('PortType');
                let portTypeTx = portTypeText.options[portTypeText.selectedIndex].text;
                let idoText = document.getElementById('ido');
                // guardar el texto del option seleccionado:
                let idoTx = idoText.options[idoText.selectedIndex].text;
                
                Swal.fire({
                    icon: 'success',
                    html: '<h3>PortId: ' + res.portid + '</h3>' + 
                    '<p>Folio Cliente: ' + res.folioID + '</p>' +
                    '<p>Tipo: ' + portTypeTx + '</p>' +
                    '<p>Donador: ' + donante + '</p>' +
                    '<p>Receptor: ' + idoTx + '</p>' +
                    '<p>Números Portados: ' + res.TotalPhoneNums + '</p>',
                    footer: '<h4 style="color:#28a745">Operación exitosa</h4>'
                });
                function cleanScreenPort() {
                    document.querySelector('#ido').value = "102";
                    document.querySelector('#PortType').value = "8";
                    document.getElementById('numeroFrom').value = "";
                    document.getElementById('numeroTo').value = "";
                    document.getElementById('nip').value = "";
                    document.getElementById('comentarios').value = "";
                    document.getElementById('docSizeOne').value = "";
                    document.getElementById('docSizeTwo').value = "";
                    document.getElementById('docSizeThree').value = "";
                    document.getElementById('docSizeFour').value = "";
                    document.getElementById('selectDocumentOne').value = "";
                    document.getElementById('selectDocumentTwo').value = "";
                    document.getElementById('selectDocumentThree').value = "";
                    document.getElementById('selectDocumentFour').value = "";
                    document.getElementById('formFileSmIdentifica').value = "";
                    document.getElementById('formFileSmSolOriginal').value = "";
                    document.getElementById('formFileSmJurada').value = "";
                    document.getElementById('formFileSmRecovery').value = "";
                    document.getElementById('excelFile').value = "";
                    document.getElementById('tablePhoneId').innerHTML = "";
                    document.querySelector('#exampleRadios1').checked = true;
                    document.querySelector('#exampleRadios2').checked = false;
                    document.querySelector('#exampleRadios3').checked = false;
                    document.querySelector('#exampleRadios4').checked = true;
                    document.querySelector('#exampleRadios5').checked = false;
                    document.querySelector('#conTable').style.display = "none";
                    document.getElementById("donador").value = 'A definir por el ABD';
                    fileSizeOne = 0;
                    fileSizeTwo = 0;
                    fileSizeThree = 0;
                    fileSizeFour = 0;
                    numeroFisica();
                    btnEnviar.disabled = false;
                }
                return cleanScreenPort();
            }else{
                return Swal.fire("Warning","Error","warning");
            }
        }
    });
            
});

async function ProcessSender(formdata){
    let url = '../php/senderSPN.php';
    console.log(...formdata);
    let res = await fetch(url, {
    method: "POST",
    body: formdata,
    /*  headers:{
        "Content-Type":"application/json"
    } */
    })
    if (res.ok){
        let text = await res.json();// res.text()
        //console.log(text.xml);
        
        //alert(filasrecorridas);
        return text;
    }else{
        //console.log(res.status);
        alert("Ocurrió un error en la red, vuelva a intentar.");
        return false;
    }
    //return text;

} 




