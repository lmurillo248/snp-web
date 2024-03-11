document.querySelector('#btnSearchProgramables').addEventListener('click', async ()=>{
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
    data.append("tableConsMilCinco",tableConsMilCinco);
    
    ProcessConsultaMilCinco(data).then(res =>{
        swal.close();
        if (res === false) {
            return Swal.fire("Warning","Error al consultar, vuelva a intentarlo","warning");
        }else{
            if (res.msg !== 'Execution Error') {
                
                //Aquí va la tabla que se crea de la consulta
                let elem = document.querySelector('#tableConsMilCinco');

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

async function ProcessConsultaMilCinco(formdata,url = '../php/consulta1005.php'){
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