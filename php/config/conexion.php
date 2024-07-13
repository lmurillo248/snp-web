<?php

class Conectar{
    protected $dbh;
    protected function conexion(){
        try {
            $conn = $this->dbh = new PDO('mysql:host=localhost;dbname=andercode_soap','root','');
            return $conn;
        } catch (Exeption $e) {
            print "Error" . $e->getMessage() . "</br>";
            die();
        }
    }
    public function set_names(){
        return $this->dbh->query("SET NAMES 'utf8'");
    }
}

try {
    $conn = oci_connect('portabilidad','wn8g6yW_#Wt4BFYsky','etalides.izzitelecom.net:1595/spndesarrolloservice.izzitelecom.net');
    // 'SPNDESARROLLO=(DESCRIPTION=(
    //     ADDRESS=(PROTOCOL=TCP)
    //     (HOST=etalides.izzitelecom.net)
    //     (PORT=1595))
    //     (CONNECT_DATA=(SERVER=DEDICATED)
    //     (SERVICE_NAME=spndesarrolloservice.izzitelecom.net)
    // ))'
} catch (Exeption $e) {
    print "Error" . $e->getMessage() . "</br>";
    die();
}

/* class ConectarORC{
    function conectardb(){
        try { */
            $conn = oci_connect('portabilidad','wn8g6yW_#Wt4BFYsky','etalides.izzitelecom.net:1595/spndesarrolloservice.izzitelecom.net');
         /*    return $conn;
        } catch (Exeption $e) {
            print "Error" . $e->getMessage() . "</br>";
            die();
        }
    }
} */


/* $rf = new ConectarORC();
$rf->conectardb(); */
/* $conection = new ConectarORC();
$conection->conectardb();

var_dump($conection);

$sql = "SELECT
                ID,
                FECHA,
                SENDER,
                PORTID,
                PORTID_AUX,
                MESSAGEID,
                ACUSE,
                XMLMSG
            FROM
                SPN_MSJ
            WHERE
                ROWNUM <= 5";

    $objPersona = null;

    $stmt = oci_parse($this->conection, $sql);        // Preparar la sentencia
    $ok   = oci_execute( $stmt );            // Ejecutar la sentencia

    if( $ok == true ){
        // Si se encontró el registro, se obtiene un objeto en PHP con los datos de los campos:
         if( oci_num_rows($stmt) > 0 )

             $objPersona = oci_fetch_object( $stmt );

    }
     
    oci_free_statement($stmt);    // Liberar los recursos asociados a una sentencia o cursor

    #return $objPersona;

    print_r($objPersona);  */




?>
