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
    $conn = oci_connect('PORTABILIDAD','PorT4bLDD.8304FD,s21','172.21.20.153:1521/CVDBPTBD');
} catch (Exeption $e) {
    print "Error" . $e->getMessage() . "</br>";
    die();
}

/* class ConectarORC{
    function conectardb(){
        try { */
            $conn = oci_connect('PORTABILIDAD','PorT4bLDD.8304FD,s21','172.21.20.153:1521/CVDBPTBD');
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
