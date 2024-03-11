const btnConsultar = document.querySelector('#btnSearchConsulta');
btnConsultar.addEventListener('click', async ()=>{
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
    let data = new FormData();
    data.append("tableCons",tableCons);
    
    ProcessConsulta(data).then(res =>{
        swal.close();
        if (res === false) {
            return Swal.fire("Warning","Error al consultar, vuelva a intentarlo","warning");
        }else{
            if (res.msg !== 'Execution Error') {
                
                //Aquí va la tabla que se crea de la consulta
                let elem = document.querySelector('#tableCons');

                // Get HTML content
                //let html = elem.innerHTML;

                // Set HTML content
                elem.innerHTML = res.tabla;

                //cambia el color de la fuente si el mensaje es 1005
                
                

            }else{
                return Swal.fire("Warning","Error en la ejecución del programa","warning");
            }
        }
    });
});

document.querySelector('#programar').addEventListener('click', async ()=>{

    let txFecha = document.querySelector('#txtdate').value;
    let txIdPort = document.querySelector('#idPort').value;
    let showLoading = function(){
        Swal.fire({
            title: 'Espere un momento por favor',
            html: 'Programando la fecha solicitada...',// add html attribute if you want or remove
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

    if (txFecha !== "" && txIdPort !== "") {
        let data = new FormData();
        data.append("txtdate",txFecha);
        data.append("idPort",txIdPort);
        
        ProcessConsulta(data,"../php/sender1006.php").then(res =>{
            swal.close();
            if (res === false) {
                return Swal.fire("Warning","Error al enviar el mensaje, vuelva a intentarlo","warning");
            }else{
                if (res.countX > 0) {
                    return Swal.fire("Warning","El PortID ingresado ya fue programado","warning");
                }
                if (res.msg !== 'Execution Error') {
                    
                    Swal.fire({
                        icon: 'success',
                        html: '<h6>PortId: ' + txIdPort + '</h6>' + 
                        '<p>Fecha Programada: ' + txFecha + '</p>' +
                        '<p style="color:#28a745">El PortID ha sido enviado al ABD con éxito</p>',
                        footer: '<h4 style="color:#28a745">Operación Exitosa</h4>'
                    });
                    
                }else{
                    return Swal.fire("Warning","Error en la ejecución del programa","warning");
                }
            }
        });
    }else{
        return Swal.fire("Warning","El campo de Fecha y el PortID no pueden estar vacíos, verifique la información","warning");
    }

});

document.querySelector('#btnIdPortDelete').addEventListener('click', async ()=>{

    let txIdPort = document.querySelector('#idPortDelete').value;
    let showLoading = function(){
        Swal.fire({
            title: 'Espere un momento por favor',
            html: 'Solicitando la cancelación del PortID...',// add html attribute if you want or remove
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

    if (txIdPort !== "") {
        let data = new FormData();
        let fechaTimeStamp=new Date(new Date().toString().split('GMT')[0]+' UTC').toISOString().replaceAll('-', '')
        .replaceAll('T', '')
        .replaceAll(':', '')
        .replaceAll('.000Z', '');
        data.append("idPortDelete",txIdPort);
        data.append("fechaTimeStamp",fechaTimeStamp);
        
        ProcessConsulta(data,"../php/sender3001.php").then(res =>{
            swal.close();
            if (res === false || res.response === false) {
                return Swal.fire("Warning","Error al enviar el mensaje, vuelva a intentarlo","warning");
            }else{
                if (res.countX == 0 || res.xmlmsg == "Error PortID") {
                    return Swal.fire("Warning","No se encontró el PortID que desea cancelar","warning");
                }
                if (res.msg !== 'Execution Error') {
                    
                    Swal.fire({
                        icon: 'success',
                        html: '<h6>PortId: ' + txIdPort + '</h6>' + 
                        '<p style="color:#28a745">El PortID ha sido enviado al ABD con éxito</p>',
                        footer: '<h4 style="color:#28a745">Operación Exitosa</h4>'
                    });
                    document.querySelector('#idPortDelete').value = "";
                }else{
                    return Swal.fire("Warning","Error en la ejecución del programa","warning");
                }
            }
        });
    }else{
        return Swal.fire("Warning","El campo de Fecha y el PortID no pueden estar vacíos, verifique la información","warning");
    }

});

const btnBuscarPortid = document.querySelector('#buscarPortid');
btnBuscarPortid.addEventListener('click', async ()=>{
    let idPort = document.querySelector('#idPort').value;
    let showLoading = function(){
        Swal.fire({
            title: 'Espere un momento por favor',
            html: 'Consultando PortID...',// add html attribute if you want or remove
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
   
    if (idPort !== "") {

        let dataP = new FormData();
        dataP.append("idPort",idPort);
        //dataP.append("messageIdX",messageIdX);

        ProcessConsulta(dataP,"../php/consultaPortid.php").then(res =>{
            swal.close();
            if (res === false || res.response === false) {
                return Swal.fire("Warning","Error al buscar el PortID, vuelva a intentarlo","warning");
            }else{
                if (res.msg !== 'Execution Error') {
                    
                    //Aquí va la tabla que se crea de la consulta
                    let elem = document.querySelector('#portIdConsulta');

                    //registra si tiene valores en la tabla SPN_MSJ_1006
                    if (res.tableConsultaPort !== "" && res.countX == 0){
                    //if (res.countX === 0) {
                        document.querySelector('#txtdate').style.display = "inline";
                        document.querySelector('#programar').style.display = "inline";
                    } else {
                        document.querySelector('#txtdate').style.display = "none";
                        document.querySelector('#programar').style.display = "none";
                    }

                    // Set HTML content
                    if (res.tableConsultaPort !== "") {
                        elem.innerHTML = res.tableConsultaPort;
                    }else{
                        return Swal.fire("Warning","El PortID ingresado aún no cuenta con el estatus 1005","warning");
                    }
                    

                }else{
                    return Swal.fire("Warning","Error en la ejecución del programa","warning");
                }
            }
        });
    }
});

document.querySelector('#buscaPortId').addEventListener('click', async ()=>{
    let buscaPortId = document.querySelector('#PortIdBuscar').value;
    let showLoading = function(){
        Swal.fire({
            title: 'Espere un momento por favor',
            html: 'Consultando PortID...',// add html attribute if you want or remove
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
   
    if (buscaPortId !== "") {

        let dataP = new FormData();
        dataP.append("buscaPortId",buscaPortId);
        //dataP.append("messageIdX",messageIdX);

        ProcessConsulta(dataP,"../php/consultaBuscador.php").then(res =>{
            swal.close();
            if (res === false || res.response === false) {
                return Swal.fire("Warning","Error al buscar el PortID, vuelva a intentarlo","warning");
            }else{
                if (res.msg !== 'Execution Error') {
                    
                    //Aquí va la tabla que se crea de la consulta
                    let elem = document.querySelector('#portIdBusqueda');

                    // Set HTML content
                    if (res.tabla !== "") {
                        elem.innerHTML = res.tabla;
                        document.querySelector('#PortIdBuscar').value = "";
                    }else{
                        return Swal.fire("Warning","El PortID ingresado aún no cuenta con el estatus 1005","warning");
                    }
                    

                }else{
                    return Swal.fire("Warning","Error en la ejecución del programa","warning");
                }
            }
        });
    }
});

/* document.querySelector('#ido').addEventListener('click', async ()=>{

    let dataP = new FormData();
    

    ProcessConsulta(dataP,"../php/selectReceptora.php").then(res =>{


        if (res.msg !== 'Execution Error') {
            
            //Aquí va la tabla que se crea de la consulta
            
            let elem = document.querySelector('#ido');

            // Set HTML content
            if (res.tabla !== "") {
                elem.innerHTML = res.tabla;
            }
            
        }else{
            return Swal.fire("Warning","Error en la ejecución del programa","warning");
        }
    });
    return false;
}); */

const btncloseBusqueda = document.querySelector('#closeBusqueda');
const contentConsulta = document.querySelector('#portIdConsulta');
btncloseBusqueda.addEventListener('click', async ()=>{
    document.querySelector('#idPort').value = "";
    document.querySelector('#txtdate').style.display = "none";
    document.querySelector('#programar').style.display = "none";
    contentConsulta.innerHTML = "";
});

async function ProcessConsulta(formdata,url = '../php/consultaSPN.php'){
    //let url = '../php/consultaSPN.php';

    //console.log(...formdata);

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
        return text;
    }else{
        //console.log(res.status);
        Swal.fire("Warning","Ocurrió un error en la red, vuelva a intentar la importación","warning");
        return false;
    }
    //return text;

} 

