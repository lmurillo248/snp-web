<?php
require_once '../php/config/conexion.php';
#variable de sesión global
session_start();

#variable global de usuario
if (isset($_SESSION['usuario'])) {
  try {
    #variable global de usuario
    $usuario = $_SESSION['usuario'];
    $isadmin = $_SESSION['isadmin'];

    #validación de usuario
    if ($usuario == "" || $isadmin == false) {
        session_start();
        session_destroy();
        header("Location:../login.php");
    }
    $stid = oci_parse($conn, "SELECT IDO, EMPRESA FROM SPN_MSJ_RECEPTORA ORDER BY IDO ASC");
    oci_execute($stid);
    $stidPerfil = oci_parse($conn, "SELECT PERFIL, PRIVILEGIO, DESCRIPCION FROM SPN_MSJ_PERFIL ORDER BY PERFIL ASC");
    oci_execute($stidPerfil);
  } catch (\Throwable $th) {
    //throw $th;
  }

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/izziAppp.png">
    <title>
        Admin SPN
    </title>

    <link href="../assets/css/cssplugs/fonts.googleapis.css" rel="stylesheet" />

    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />

    <script src="../assets/js/plugins/kit.fontawesome.js" crossorigin="anonymous"></script>
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />

    <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />

    <style>
        .async-hide {
            opacity: 0 !important
        }
    </style>
    



    
</head>

<body class="g-sidenav-show bg-gray-100">


    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0"
                href="admin.php">
                <img src="../assets/img/izzi.png" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">SPN</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <ul class="nav flex-column bg-white border-radius-lg p-3">
            <li class="nav-item">
                <a class="nav-link text-body" data-scroll href="#profile">
                    <div class="icon me-2">
                        <i class="ni ni-circle-08 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="text-sm">Admin</span>
                </a>
            </li>
            <li class="nav-item pt-2">
                <a class="nav-link text-body" data-scroll href="#basic-info">
                    <div class="icon me-2">
                        <i class="ni ni-settings text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="text-sm">Alta de Usuario</span>
                </a>
            </li>
            <li class="nav-item pt-2">
                <a class="nav-link text-body" data-scroll href="#addEmpresa">
                    <div class="icon me-2">
                        <i class="ni ni-fat-add text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="text-sm">Agregar Empresa</span>
                </a>
            </li>
            <li class="nav-item pt-2">
                <a class="nav-link text-body" data-scroll href="#modificarEmpresa">
                    <div class="icon me-2">
                        <i class="ni ni-ruler-pencil text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="text-sm">Modificar Empresa</span>
                </a>
            </li>
            <li class="nav-item pt-2">
                <a class="nav-link text-body" data-scroll href="#delete">
                    <div class="icon me-2">
                        <i class="ni ni-fat-remove text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="text-sm">Eliminar cuenta</span>
                </a>
            </li>
        </ul>
        </div>
    </aside>
    <main class="main-content max-height-vh-100 h-100">
        
       
        <div class="container-fluid my-3 py-3">
            <div class="row mb-5">
                <div class="col-lg-3">
                    <div class="card position-sticky top-1">
                        
                    </div>
                </div>
                <div class="col-lg-12 mt-lg-0 mt-4" >

                    <div class="card card-body" id="profile" style="margin-top: -30px;">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-sm-auto col-4" style="margin-left: 21px; margin-top: 6px;">
                                <div class="avatar avatar-xl position-relative">
                                    <h5 style="">Bienvenido: </h5>
                                </div>
                            </div>
                            <div class="col-sm-auto col-8 my-auto">
                                <div class="h-100">
                                    <h5 class="mb-1 font-weight-bolder">
                                        <?php echo $usuario;?>
                                    </h5>
                                    <p class="mb-0 font-weight-bold text-sm">
                                        
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-auto ms-sm-auto mt-sm-0 mt-3 d-flex">
                                <label class="form-check-label mb-0">
                                    <small id="profileVisibility">
                                        Logout
                                    </small>
                                </label>
                                <div class="form-check form-switch ms-2">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault23"
                                        checked onchange="visible()">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4" id="basic-info">
                        <div class="card-header">
                            <h5>Alta de Usuario</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label mt-4">Usuario</label>
                                    <div class="input-group">
                                        <input id="userPerfil" name="userPerfil" class="form-control" type="text"placeholder="p-example">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label mt-4">Email</label>
                                    <div class="input-group">
                                        <input id="userEmail" name="userEmail" class="form-control" type="email"placeholder="example@email.com">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label class="form-label">Nombres</label>
                                    <div class="input-group">
                                        <input id="firstName" name="firstName" class="form-control" type="text" placeholder="Alec" required="required">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Apellido Paterno</label>
                                    <div class="input-group">
                                        <input id="FirstlastName" name="FirstlastName" class="form-control" type="text" placeholder="Thompson" required="required">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Apellido Materno</label>
                                    <div class="input-group">
                                        <input id="SecondlastName" name="SecondlastName" class="form-control" type="text" placeholder="Thompson" required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 align-self-center">
                                    <label class="form-label">Perfil del Usuario</label>
                                    <select class="form-select" aria-label="Default select example" id="selectPerfil">
                                        <option selected>Seleccione Perfil de Usuario</option>
                                        <?php 
                                            while ($rowPer = oci_fetch_array($stidPerfil, OCI_ASSOC)) {?>
                                                <option value="<?php echo"". $rowPer['PERFIL']?>"><?php echo"".$rowPer['PRIVILEGIO']?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="col-md-6 align-self-center">
                                    <label class="form-label mt-4"></label>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary mb-0 ms-auto" type="button" id="activarPerfil" style="    width: 111px;">Activar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4" id="addEmpresa">
                        <div class="card-header">
                            <h5>Agregar Empresa</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label mt-4">IDO Nuevo</label>
                                    <div class="input-group">
                                        <input id="addIdo" name="addIdo" class="form-control" type="text"placeholder="Example: 102">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label mt-4">Nombre Empresa</label>
                                    <div class="input-group">
                                        <input id="addEmp" name="addEmp" class="form-control" type="text"placeholder="Example: OPERBES SA DE CV">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 align-self-center">
                                    
                                </div>
                                <div class="col-md-6 align-self-center">
                                    <label class="form-label mt-4"></label>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary mb-0 ms-auto" type="button" id="addIdoEmp" style="    width: 111px;">Enviar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4" id="modificarEmpresa">
                        <div class="card-header">
                            <h5>Modificar Empresa</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label mt-4">IDO Anterior</label>
                                    <div class="input-group">
                                        <input id="addIdoAnterior" name="addIdoAnterior" class="form-control" type="text"placeholder="Example: 102 --Anterior--">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label mt-4">Nombre Empresa Anterior</label>
                                    <div class="input-group">
                                        <select class="form-select" aria-label="Default select example" id="addEmpAnterior" name="addEmpAnterior">
                                            <option value="" selected>-Seleccione un IDO-</option>
                                            <?php 
                                                while ($row = oci_fetch_array($stid, OCI_ASSOC)) {?>
                                                    <option value="<?php echo"". $row['IDO']?>"><?php echo"".$row['EMPRESA']?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label mt-4">IDO Nuevo</label>
                                    <div class="input-group">
                                        <input id="addIdoActual" name="addIdoActual" class="form-control" type="text"placeholder="Example: 103">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label mt-4">Nombre Empresa Nuevo</label>
                                    <div class="input-group">
                                        <input id="addEmpActual" name="addEmpActual" class="form-control" type="text"placeholder="Example: ROYAL TELEPHONE GUIDE">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 align-self-center">
                                    
                                </div>
                                <div class="col-md-6 align-self-center">
                                    <label class="form-label mt-4"></label>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary mb-0 ms-auto" type="button" id="modificarIdoEmp" style="    width: 111px;">Enviar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4" id="delete">
                        <div class="card-header">
                            <h5>Eliminar Acceso de Usuario</h5>
                            <p class="text-sm mb-0">Ingrese el usuario que desea desactivar del sistema SPN</p>
                        </div>
                        <div class="card-body d-sm-flex pt-0">
                            <div class="col-6">
                                <div class="input-group">
                                    <input id="desactivar" name="desactivar" class="form-control" type="text" placeholder="p-example" required="required">
                                </div>
                            </div>
                            <button class="btn btn-outline-secondary mb-0 ms-auto" type="button"
                                name="desactivatePerfil" id="desactivatePerfil">Desactivar</button>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer pt-3  ">
            <div class="container-fluid">
              <div class="row align-items-center justify-content-lg-between">
                <div class="col-lg-6 mb-lg-0 mb-4">
                  <div class="copyright text-center text-sm text-muted text-lg-start">
                    © <script>
                      document.write(new Date().getFullYear())
                    </script>,
                    IZZI.
                    <a href="" class="font-weight-bold" target="_blank">Derechos reservados</a>
                  </div>
                </div>
              </div>
            </div>
          </footer>
        </div>
    </main>

    <!--   Core JS Files   -->
    <script src="../assets/js/core/popper.min.js"></script>
      <script src="../assets/js/core/bootstrap.min.js"></script>
      <script src="../assets/js/core/sweetalert2.js"></script>
      <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
      <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
      <script src="../assets/js/plugins/chartjs.min.js"></script>

      <!-- js general -->
      <script src="../assets/js/perfilUsuarioSPN.js"></script>

    <script>

        function visible() {
            var elem = document.getElementById('profileVisibility');
            if (elem) {
                if (elem.innerHTML == "Switch to visible") {
                    elem.innerHTML = "Switch to invisible";
                } else {
                    elem.innerHTML = "Switch to visible";
                    window.location = "../php/model/logout.php";
                }
            }
        }

        var openFile = function (event) {
            var input = event.target;

            // Instantiate FileReader
            var reader = new FileReader();
            reader.onload = function () {
                imageFile = reader.result;

                document.getElementById("imageChange").innerHTML = '<img width="200" src="' + imageFile + '" class="rounded-circle w-100 shadow" />';
            };
            reader.readAsDataURL(input.files[0]);
        };
    </script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>

</body>

</html>
<?php 
} else {
    session_start();
    session_destroy();
    header("Location:../login.php");
}
?>