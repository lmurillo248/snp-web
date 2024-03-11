const btnEliminarNumero = document.querySelector('#deleteNumber');
btnEliminarNumero.addEventListener('click', async () => {
    const radioNoGeo = document.querySelector('#exampleRadios6');
    const radioFijo = document.querySelector('#exampleRadios7');
    const txtfromDelete = document.querySelector('#fromDelete').value;
    const txttoDelete = document.querySelector('#toDelete').value;
    const textAreaEx = document.querySelector('#exampleFormControlTextarea1').value;

    //bandera para saber si es fijo o geográfico
    let flagRadio = 0;

    if (document.getElementById('excelFileEliminacion').value !== "") {
        
    }else{
        if (txtfromDelete === "") {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>El número de teléfono no puede estar vacío</p>',
                footer: '<p>Campos de Teléfono</p>'
            })
            return false;
        }
        if (txtfromDelete.length < 10 || txtfromDelete.vlength > 10) {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>El número de teléfono debe tener 10 dígitos exáctos</p>',
                footer: '<p>Campos de Teléfono</p>'
            })
            return false;
        }
        if (txttoDelete === "") {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>El número de teléfono en el campo "To" no puede estar vacío</p>',
                footer: '<p>Campos de Teléfono</p>'
            })
            return false;
        }
        if (txttoDelete.length < 10 || txttoDelete.vlength > 10) {
            Swal.fire({
                icon: 'error',
                //title: 'Oops...',
                text: 'Something went wrong!',
                html: '<h4>Oops...</h4>' + '<p>El número de teléfono en el campo "To" debe tener 10 dígitos exáctos</p>',
                footer: '<p>Campos de Teléfono</p>'
            })
            return false;
        }
    }
    //sending form
    let data = new FormData();
    let fechaTimeStamp=new Date(new Date().toString().split('GMT')[0]+' UTC').toISOString().replaceAll('-', '')
    .replaceAll('T', '')
    .replaceAll(':', '')
    .replaceAll('.000Z', '');
    
    //validación para el envío de la data con los input text llenos
    if (txtfromDelete !== "" && txttoDelete !== "") {
        if (radioFijo.checked) {
            //física o moral
            flagRadio = 1;
            data.append("fromDelete", txtfromDelete);
            data.append("toDelete", txttoDelete);
            data.append("exampleFormControlTextarea1", textAreaEx);
            data.append("fechaTimeStamp", fechaTimeStamp);
    
        }else{
            //moral
            flagRadio = 2;
            data.append("fromDelete", txtfromDelete);
            data.append("toDelete", txttoDelete);
            data.append("exampleFormControlTextarea1", textAreaEx);
            data.append("fechaTimeStamp", fechaTimeStamp);
        }
    }else{
        //envío del listado de números moral gobierno
        if (txtfromDelete === "" && txttoDelete === "") {
            if (radioFijo.checked) {
                //física
                flagRadio = 1;
                const content = await readXlsxFile( appExcelEliminar.files[0] );
                let jsonBlob = new Blob([JSON.stringify(content)], {type: "application/json"});//text
                data.append("listadoNumEliminar", jsonBlob);
                data.append("exampleFormControlTextarea1", textAreaEx);
                data.append("fechaTimeStamp", fechaTimeStamp);
                
        
            }else{
                //envío del listado de números
                flagRadio = 2;
                const content = await readXlsxFile( appExcelEliminar.files[0] );
                let jsonBlob = new Blob([JSON.stringify(content)], {type: "application/json"});//text
                data.append("listadoNumEliminar", jsonBlob);
                data.append("exampleFormControlTextarea1", textAreaEx);
                data.append("fechaTimeStamp", fechaTimeStamp);
            }
        }
    }
    //recibimos el flagradio
    data.append("flagRadio",flagRadio);
//----------------------------------------AQUÍ EMPIEZA LA FUNCIÓN QUE PROCESA PARA EL PHP-------------------------------
    //call api->php
    ProcessElimination(data).then(res =>{
        if (res === false || res.response === false) {
            alert("Error");
        }else{
            if (res.msg !== 'Execution Error') {
                
                /* let portTypeTextE = document.getElementById('PortType');
                let portTypeTxE = portTypeTextE.options[portTypeTextE.selectedIndex].text;
                let idoTextE = document.getElementById('ido');
                let idoTxE = idoText.options[idoText.selectedIndex].text;
                 */
                 Swal.fire({
                    icon: 'success',
                    html: '<h3>PortId: ' + res.portid + '</h3>' + 
                    '<p>Tipo: ' + 'Cancelación' + '</p>' +
                    '<p>Números Cancelados: ' + res.TotalPhoneNums + '</p>',
                    footer: '<h4 style="color:#28a745">Operación exitosa</h4>'
                });
                function cleanScreenPort() {
                    document.querySelector('#fromDelete').value = "";
                    document.querySelector('#toDelete').value = "";
                    document.querySelector('#exampleFormControlTextarea1').value = "";
                    btnEnviar.disabled = false;
                }
                return cleanScreenPort();
            }else{
                return Swal.fire("Warning","Error","warning");
            }
        }
    });
});

async function ProcessElimination(formdata){
    let url = '../php/eliminarNumero.php';

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
        alert("Ocurrió un error en la red, vuelva a intentar la importación.");
        return false;
    }
    //return text;

} 