const sizeValidate = (file, identificador)=>{
    if(file.size > 1 * 1024 * 1024)
    {
        document.getElementById(identificador).value = '';
    	Swal.fire("Warning","Cada archivo no puede exceder 1 Mega byte de tamaño","warning");

        return;
    }
}

document.querySelector('#formFileSmIdentifica').addEventListener('change', ()=>{
    let fileIdentifica = document.getElementById('formFileSmIdentifica').files[0];
    if (fileIdentifica !== "") {
        sizeValidate(fileIdentifica,"formFileSmIdentifica");
    }

});

document.querySelector('#formFileSmSolOriginal').addEventListener('change', ()=>{
    let fileIdentifica = document.getElementById('formFileSmSolOriginal').files[0];
    if (fileIdentifica !== "") {
        sizeValidate(fileIdentifica,"formFileSmSolOriginal");
    }

});

document.querySelector('#formFileSmJurada').addEventListener('change', ()=>{
    let fileIdentifica = document.getElementById('formFileSmJurada').files[0];
    if (fileIdentifica !== "") {
        sizeValidate(fileIdentifica,"formFileSmJurada");
    }

});

const btnEnviar = document.querySelector('#enviarApi');
btnEnviar.addEventListener('click', async ()=>{
    const numTel = document.getElementById('numeroFrom');
    const numTo = document.getElementById('numeroTo');
    const numNip = document.getElementById('nip');
    //const client = document.getElementById('cliente').value;
    const donante = document.getElementById('donador').value;
    const radio1fisica = document.querySelector('#exampleRadios1');
    const radio2moral = document.querySelector('#exampleRadios2');
    const radio3gobierno = document.querySelector('#exampleRadios3');
    const radio4activo = document.querySelector('#exampleRadios4');
    const radio5 = document.querySelector('#exampleRadios5');
    const PortTypeId = document.querySelector('#PortType').value;
    const ido = document.querySelector('#ido').value;
    const fechaEntregado = document.querySelector('#fechaEntregado').value;
    const comentarios = document.querySelector('#comentarios').value;
    
    let flagRadio = 0;
    let activoDesconectado = "";

    //validación para que los controles estén llenos
    if (document.getElementById('excelFile').value !== "") {
        if (document.getElementById('formFileSmJurada').value == "" || document.getElementById('formFileSmIdentifica').value == "" || document.getElementById('formFileSmSolOriginal').value == "") {
            Swal.fire({
                icon: 'error',
                //title: '<p>Oops...</p>',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>Debe seleccionar los archivos adjuntos correspondientes en la sección de Attachments</p>',
                footer: '<p>Sección de Archivos Adjuntos</p>'
            })
            return false;
        }
        if (document.querySelector('#selectDocumentOne').value == "" || document.querySelector('#selectDocumentTwo').value == "" || document.querySelector('#selectDocumentThree').value == "") {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>Los selectores de documentos no pueden estar vacíos</p>',
                footer: '<p>Selectores de Documentos</p>'
            })
            return false;
        }
    }else{
        if (ido == "") {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>Debe seleccionar un IDO</p>',
                footer: '<p>Empresa Receptora</p>'
            })
            return false;
        }
        if (PortTypeId == "") {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>Debe seleccionar un tipo de Portación</p>',
                footer: '<p>Tipo de Portación</p>'
            })
            return false;
        }
        if (document.getElementById('numeroFrom').value === "") {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>El número de teléfono no puede estar vacío</p>',
                footer: '<p>Campos de Teléfono</p>'
            })
            return false;
        }
        if (numTel.value.length < 10 || numTel.value.length > 10) {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>El número de teléfono debe tener 10 dígitos exáctos</p>',
                footer: '<p>Campos de Teléfono</p>'
            })
            return false;
        }
        if (document.getElementById('numeroTo').value === "") {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>El campo "To:" no puede estar vacío</p>',
                footer: '<p>Campos de Teléfono</p>'
            })
            return false;
        }
        if (numTo.value.length < 10 || numTo.value.length > 10) {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>El número de teléfono en el campo "To:" debe tener 10 dígitos exáctos</p>',
                footer: '<p>Campos de Teléfono</p>'
            })
            return false;
        }
        if ((numNip.value.length > 4 || numNip.value.length < 4) && numNip.value !== "Attachments") {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>El NIP debe ser de 4 dígitos exactos</p>',
                footer: '<p>Debe llenar el NIP</p>'
            })
            return false;
        }
        if (radio2moral.checked || radio3gobierno.checked || radio5.checked) {
            if (document.querySelector('#selectDocumentOne').value == "" || document.querySelector('#selectDocumentTwo').value == "" || document.querySelector('#selectDocumentThree').value == "") {
                Swal.fire({
                    icon: 'error',
                    //title: 'Oops...',
                    text: 'Something went wrong!',
                    html: '<h4>Oops...</h4>' + '<p>Los selectores de documentos no pueden estar vacíos</p>',
                    footer: '<p>Selectores de Documentos</p>'
                });
                return false;
            }
            if (document.getElementById('formFileSmJurada').value == "" || document.getElementById('formFileSmIdentifica').value == "" || document.getElementById('formFileSmSolOriginal').value == "") {
                Swal.fire({
                    icon: 'error',
                    //title: '<p>Oops...</p>',
                    text: 'Something went wrong!',
                    html: '<h4>Oops...</h4>' + '<p>Debe seleccionar los archivos adjuntos correspondientes en la sección de Attachments</p>',
                    footer: '<p>Sección de Archivos Adjuntos</p>'
                });
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

    if (radio4activo.checked) {
        activoDesconectado = "N";
    }else{
        activoDesconectado = "Y";
    }
    
    data.append("activoDesconectado",activoDesconectado);
    
    //validación para el envío de la data
    if (radio1fisica.checked) {
        //física
        flagRadio = 1;
        data.append("numTel", numTel.value);
        data.append("numTo", numTo.value);
        data.append("numNip", numNip.value);
        
        if (radio4activo.checked){
            //activo
            
            
        }else{
            //desconectado
            data.append("selectDocumentOne",document.querySelector('#selectDocumentOne').value); //agregado para el tipo de documento
            data.append("selectDocumentTwo",document.querySelector('#selectDocumentTwo').value); //agregado para el tipo de documento
            data.append("selectDocumentThree",document.querySelector('#selectDocumentThree').value); //agregado para el tipo de documento
            data.append("formFileSmIdentifica", document.getElementById('formFileSmIdentifica').files[0]);
            data.append("formFileSmSolOriginal", document.getElementById('formFileSmSolOriginal').files[0]);
            data.append("formFileSmJurada", document.getElementById('formFileSmJurada').files[0]);
        }
    }else {
        //moral/Gobierno -> activo
        if (radio2moral.checked){
            //moral
            flagRadio = 2;
        }else{
            //gobierno
            flagRadio = 3;
        }
        data.append("selectDocumentOne",document.querySelector('#selectDocumentOne').value); //agregado para el tipo de documento
        data.append("selectDocumentTwo",document.querySelector('#selectDocumentTwo').value); //agregado para el tipo de documento
        data.append("selectDocumentThree",document.querySelector('#selectDocumentThree').value); //agregado para el tipo de documento
        data.append("formFileSmIdentifica", document.getElementById('formFileSmIdentifica').files[0]);
        data.append("formFileSmSolOriginal", document.getElementById('formFileSmSolOriginal').files[0]);
        data.append("formFileSmJurada", document.getElementById('formFileSmJurada').files[0]);
        if (numTel.value != "") {
            data.append("numTel", numTel.value);
            data.append("numTo", numTo.value);
            data.append("numNip", numNip.value); //agregado por mi
            
        }else{
            //leemos el archivo para moral
            const content = await readXlsxFile( appExcel.files[0] );
            let jsonBlob = new Blob([JSON.stringify(content)], {type: "application/json"});//text
            data.append("numNip", numNip.value);
            data.append("numTel", "");
            data.append("numTo", "");
            data.append("listadoNum", jsonBlob);
        }
        //moral/gobierno -> desconectado
        
        
    /* }else if (radio3gobierno.checked){
        flagRadio = 3;*/
    } 

    data.append("flagRadio",flagRadio);

    //----------------------------------------AQUÍ EMPIEZA LA FUNCIÓN QUE PROCESA PARA EL PHP-------------------------------
    //call api->php
    ProcessSender(data).then(res =>{
        if (res === false || res.xml === false) {
            return Swal.fire("Warning","Error, el proceso se interrumpió; vuelva a intentarlo","warning");
        }else{
            if (res.msg !== 'Execution Error') {
                
                let portTypeText = document.getElementById('PortType');
                let portTypeTx = portTypeText.options[portTypeText.selectedIndex].text;
                let idoText = document.getElementById('ido');
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
                    document.querySelector('#ido').value = "";
                    document.querySelector('#PortType').value = "";
                    document.getElementById('numeroFrom').value = "";
                    document.getElementById('numeroTo').value = "";
                    document.getElementById('nip').value = "";
                    document.getElementById('comentarios').value = "";
                    document.getElementById('selectDocumentOne').value = "";
                    document.getElementById('selectDocumentTwo').value = "";
                    document.getElementById('selectDocumentThree').value = "";
                    document.getElementById('formFileSmIdentifica').value = "";
                    document.getElementById('formFileSmSolOriginal').value = "";
                    document.getElementById('formFileSmJurada').value = "";
                    document.querySelector('#exampleRadios2').checked = false;
                    document.querySelector('#exampleRadios3').checked = false;
                    document.querySelector('#exampleRadios1').checked = true;
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
        //console.log(res.status);
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




