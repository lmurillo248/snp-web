document.querySelector('#sendRevert').addEventListener('click', async ()=>{

    let txnumRevFrom = document.querySelector('#numRevFrom').value;
    let txnumRevTo = document.querySelector('#numRevTo').value;
    let txselectRevertType = document.querySelector('#selectRevertType').value;
    let txdateRevert = document.querySelector('#txtdateRevert').value;
    let txselectDocumentRevert = document.querySelector('#selectDocumentRevert').value;
    let txformFileSmRevert = document.querySelector('#formFileSmRevert').files[0];

    if (txtportIdRev !== "" && txnumRevFrom !== "" && txnumRevTo !== "" && txselectRevertType !== "" && txdateRevert !== "") {

        if (txselectDocumentRevert !== "" && txformFileSmRevert == "") {
            return Swal.fire("Warning","Se requiere adjuntar documento","warning");
        }
        //modal de espera mientras se procesa la data
        let showLoading = function(){
            Swal.fire({
                title: 'Espere un momento por favor',
                html: 'Enviando Reversión...',// add html attribute if you want or remove
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
        data.append("numRevFrom",txnumRevFrom);
        data.append("numRevTo",txnumRevTo);
        data.append("selectRevertType",txselectRevertType);
        data.append("txtdateRevert",txdateRevert);
        data.append("selectDocumentRevert",txselectDocumentRevert);
        data.append("formFileSmRevert", txformFileSmRevert);
        
        ProcessRevert(data,"../php/sender4001.php").then(res =>{
            swal.close();
            if (res === false || res.response === false) {
                return Swal.fire("Warning","Error al enviar el formulario, vuelva a intentarlo","warning");
            }else{
                
                if (res.msg !== 'Execution Error') {
                    Swal.fire({
                        icon: 'success',
                        html: '<h6>DN: ' + txnumRevFrom + '</h6>' + 
                        '<p>PortID: ' + res.PortID + '</p>' +
                        '<p>Fecha Programada: ' + txdateRevert + '</p>' +
                        '<p style="color:#28a745">El DN ha sido enviado al ABD con éxito</p>',
                        footer: '<h4 style="color:#28a745">Operación Exitosa</h4>'
                    });
                    
                }else{
                    return Swal.fire("Warning","Error en la ejecución del programa","warning");
                }
            }
        });
    }else{
        return Swal.fire("Warning","Los campos del formulario no pueden estar vacíos, verifique la información","warning");
    }
});

async function ProcessRevert(formdata,url = '../php/consultaSPN.php'){
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