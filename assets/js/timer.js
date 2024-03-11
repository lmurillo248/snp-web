function dateTimeZone(){
    let mostrarFecha = document.querySelector('#fechaEntregado');
    let mostrarReloj = document.querySelector('#reloj');
    let fechaTimeStamp = document.querySelector('#fechaTimeStamp');
    let fecha = new Date();
    let diaSemana = ['Domingo','Lunes', 'Martes','Miércoles','Jueves','Viernes','Sábado'];

    let mesAnyo = ['01','02', '03','04','05','06','07','08','09','10','11','12'];
    function getDateDay() {
        let days = fecha.getDate();
        return days < 10 ? '0' + days : days;
    }
 
    mostrarFecha.innerHTML = `${fecha.getFullYear()}` + `${mesAnyo[fecha.getMonth()]}` + `${getDateDay()}`;
    setInterval(()=>{
        let hora = new Date();
        //let hora = `${fecha.getHours()}` + `${fecha.getMinutes()}` + `${fecha.getSeconds()}`
        mostrarReloj.innerHTML = hora.toLocaleTimeString().replace(":","").replace(":","");
        fechaTimeStamp.innerHTML = mostrarFecha.innerHTML + mostrarReloj.innerHTML;
    },1000);

    /* let datex=new Date(new Date().toString().split('GMT')[0]+' UTC').toISOString().replaceAll('-', '')
    .replaceAll('T', '')
    .replaceAll(':', '')
    .replaceAll('.000Z', '');
    alert(datex); */

    

}

