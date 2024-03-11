document.querySelector('#activarPerfil').addEventListener('click', async ()=>{

    let txUsuario = document.querySelector('#userPerfil').value;
    let txEmail = document.querySelector('#userEmail').value;
    let txNombre = document.querySelector('#firstName').value;
    let txApellidoP = document.querySelector('#FirstlastName').value;
    let txApellidoM = document.querySelector('#SecondlastName').value;
    let txPerfil = document.querySelector('#selectPerfil').value;
    let activo = 1;
    let showLoading = function(){
        Swal.fire({
            title: 'Espere un momento por favor',
            html: 'Creando usuario...',// add html attribute if you want or remove
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

    if (txUsuario !== "" && txEmail !== "" && txNombre !== "" && txApellidoP !== "" && txApellidoM !== "" && txPerfil !== "") {
        let data = new FormData();
        data.append("userPerfil",txUsuario);
        data.append("userEmail",txEmail);
        data.append("firstName",txNombre);
        data.append("FirstlastName",txApellidoP);
        data.append("SecondlastName",txApellidoM);
        data.append("selectPerfil",txPerfil);
        data.append("activo",activo);
        
        
        ProcessPerfil(data,"../php/perfilUsuarioSPN.php").then(res =>{
            swal.close();
            if (res === false) {
                return Swal.fire("Warning","Error al crear usuario, vuelva a intentarlo","warning");
            }else{
                if (res.countX > 0) {
                    return Swal.fire("Warning","El usuario ingresado ya existe","warning");
                }
                if (res.msg !== 'Execution Error') {
                    
                    
                    
                }else{
                    return Swal.fire("Warning","Error en la ejecución del programa","warning");
                }
            }
        });
    }else{
        return Swal.fire("Warning","Los campos del formulario no pueden estar vacíos, verifique la información","warning");
    }

});

document.querySelector('#desactivatePerfil').addEventListener('click', async ()=>{

    let txUsuario = document.querySelector('#desactivar').value;
    let activo = 0;
    let showLoading = function(){
        Swal.fire({
            title: 'Espere un momento por favor',
            html: 'Eliminando usuario...',// add html attribute if you want or remove
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

    if (txUsuario !== "") {
        let data = new FormData();
        data.append("desactivar",txUsuario);
        data.append("activo",activo);
        
        
        ProcessPerfil(data,"../php/desactivarUsuario.php").then(res =>{
            swal.close();
            if (res === false) {
                return Swal.fire("Warning","Error al crear usuario, vuelva a intentarlo","warning");
            }else{
                if (res.countX > 0) {
                    return Swal.fire("Warning","El usuario ingresado ya existe","warning");
                }
                if (res.msg !== 'Execution Error') {
                    
                    
                    
                }else{
                    return Swal.fire("Warning","Error en la ejecución del programa","warning");
                }
            }
        });
    }else{
        return Swal.fire("Warning","Los campos del formulario no pueden estar vacíos, verifique la información","warning");
    }

});

document.querySelector('#addIdoEmp').addEventListener('click', async ()=>{

    let txIdo = document.querySelector('#addIdo').value;
    let txEmpresa = document.querySelector('#addEmp').value;
    let btnInsertar = document.querySelector('#addIdoEmp');
    btnInsertar.disabled = true;
    let showLoading = function(){
        Swal.fire({
            title: 'Espere un momento por favor',
            html: 'Creando registro...',// add html attribute if you want or remove
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

    if (txIdo !== "" && txEmpresa !== "") {
        let data = new FormData();
        data.append("addIdo",txIdo);
        data.append("addEmp",txEmpresa);
        
        ProcessPerfil(data,"../php/insertarEmpresa.php").then(res =>{
            swal.close();
            if (res === false) {
                return Swal.fire("Warning","Error al insertar los datos, vuelva a intentarlo","warning");
            }else{
                if (res.countX > 0) {
                    return Swal.fire("Warning","Los datos ingresados ya existen","warning");
                }
                if (res.msg !== 'Execution Error') {
                    Swal.fire({
                        icon: 'success',
                        html: '<h3>Insertado</h3>' + 
                        '<p>IDO: ' + txIdo + '</p>' +
                        '<p>Empresa: ' + txEmpresa + '</p>',
                        footer: '<h4 style="color:#28a745">Operación exitosa</h4>'
                    });
                    function cleanScreenEmpresaIdo() {
                        document.querySelector('#addIdo').value = "";
                        document.querySelector('#addEmp').value = "";
                        btnInsertar.disabled = false;
                    }
                    return cleanScreenEmpresaIdo();
                    
                }else{
                    return Swal.fire("Warning","Error en la ejecución del programa","warning");
                }
            }
        });
    }else{
        return Swal.fire("Warning","Los campos del formulario no pueden estar vacíos, verifique la información","warning");
    }

});

document.querySelector('#modificarIdoEmp').addEventListener('click', async ()=>{

    let txIdoAnt = document.querySelector('#addIdoAnterior').value;
    let txEmpresaAnt = document.querySelector('#addEmpAnterior').value;
    let txIdoAct = document.querySelector('#addIdoActual').value;
    let txEmpresaAct = document.querySelector('#addEmpActual').value;
    let btnInsertar = document.querySelector('#modificarIdoEmp');
    btnInsertar.disabled = true;
    let showLoading = function(){
        Swal.fire({
            title: 'Espere un momento por favor',
            html: 'Creando registro...',// add html attribute if you want or remove
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

    if (txIdoAnt !== "" && txEmpresaAnt !== "" && txIdoAct !== "" && txEmpresaAct !== "") {
        let data = new FormData();
        data.append("addIdoAnterior",txIdoAnt);
        data.append("addEmpAnterior",txEmpresaAnt);
        data.append("addIdoActual",txIdoAct);
        data.append("addEmpActual",txEmpresaAct);
        
        ProcessPerfil(data,"../php/editarEmpresa.php").then(res =>{
            swal.close();
            if (res === false) {
                return Swal.fire("Warning","Error al insertar los datos, vuelva a intentarlo","warning");
            }else{
                if (res.countX > 0) {
                    return Swal.fire("Warning","Los datos ingresados ya existen","warning");
                }
                if (res.msg !== 'Execution Error') {
                    Swal.fire({
                        icon: 'success',
                        html: '<h3>Insertado</h3>' + 
                        '<p>IDO Actualizado: ' + txIdoAct + '</p>' +
                        '<p>Empresa Actualizada: ' + txEmpresaAct + '</p>',
                        footer: '<h4 style="color:#28a745">Operación exitosa</h4>'
                    });
                    function cleanScreenEmpresaIdo() {
                        document.querySelector('#addIdoAnterior').value = "";
                        document.querySelector('#addEmpAnterior').value = "";
                        document.querySelector('#addIdoActual').value = "";
                        document.querySelector('#addEmpActual').value = "";
                        btnInsertar.disabled = false;
                    }
                    return cleanScreenEmpresaIdo();
                    
                }else{
                    return Swal.fire("Warning","Error en la ejecución del programa","warning");
                }
            }
        });
    }else{
        return Swal.fire("Warning","Los campos del formulario no pueden estar vacíos, verifique la información","warning");
    }

});

async function ProcessPerfil(formdata,url = '../php/consultaSPN.php'){
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