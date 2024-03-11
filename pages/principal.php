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
    if ($usuario == "" || $isadmin == true ) {
      session_start();
      session_destroy();
      header("Location:../login.php");
    }
    $stid = oci_parse($conn, "SELECT IDO, EMPRESA FROM SPN_MSJ_RECEPTORA ORDER BY IDO ASC");
    oci_execute($stid);
    $stidFix = oci_parse($conn, "SELECT  TIPO_DE_PORTACION FROM SPN_MSJ_TIPO_PORT ORDER BY TIPO_DE_PORTACION ASC");
    oci_execute($stidFix);
  } catch (\Throwable $th) {
    //throw $th;
  }
  
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href=".../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    SPN
  </title>
  <!--     Fonts and icons     -->
  <link href="../assets/css/cssplugs/fonts.googleapis.css" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />

  <link rel="stylesheet" href="../assets/css/cssplugs/flatpickr.min.css">
  <link rel="stylesheet" href="../assets/css/cssplugs/flatpickr_dark.css">
</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <aside
    class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
        aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="" target="">
        <img src="../assets/img/izzi.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Sistema SPN</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item" id="btnSearch">
          <a class="nav-link" style="cursor: pointer;">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-mobile-button text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Portabilidad Red</span>
          </a>
        </li>
        <!-- <li class="nav-item" id="btnSearchInternet">
              <a class="nav-link"  style="cursor: pointer;">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Portabilidad Red</span>
              </a>
            </li> -->
        <!-- <li class="nav-item" id="btnSearch2">
              <a class="nav-link"  style="cursor: pointer;">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="ni ni-watch-time text-primary text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Seguimiento</span>
              </a>
            </li> -->
        <!-- <li class="nav-item" id="btnSearch3">
          <a class="nav-link" style="cursor: pointer;">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-archive-2 text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Reversiones</span>
          </a>
        </li> -->
        <li class="nav-item" id="btnSearchEliminar">
          <a class="nav-link" style="cursor: pointer;">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-basket text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Eliminación</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Consultas</h6>
        </li>
        <li class="nav-item" id="btnSearchConsulta">
          <a class="nav-link" style="cursor: pointer;">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-watch-time text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Seguimiento</span>
          </a>
        </li>
        <li class="nav-item" id="btnSearchProgramables">
          <a class="nav-link" style="cursor: pointer;">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-time-alarm text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Programables</span>
          </a>
        </li>
        <li class="nav-item" id="btnSearchConsultaEliminados">
          <a class="nav-link " style="cursor: pointer;">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-fat-remove text-dark text-lg opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Cancelación</span>
          </a>
        </li>
      </ul>
    </div>

  </aside>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
      data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">IZZI</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Sistema de Portabilidad Nacional</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0"></h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group">

            </div>
          </div>
          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a href="" class="nav-link text-white p-0" style="cursor:default"
                aria-expanded="false">
                <i class="ni ni-circle-08 me-sm-1"></i>
                <span class="d-sm-inline d-none"><?php echo $usuario ?></span>
              </a>
            </li>
            <li class="nav-item dropdown pe-2 d-flex align-items-center" style="margin-left: 10px;">
              <div class="col-sm-auto ms-sm-auto mt-sm-0 mt-3 d-flex">
                  <label class="form-check-label mb-0">
                    <small id="profileVisibility2" style="color: #FFF; font-size: 15px;">
                        Logout
                    </small>
                  </label>
                  <div class="form-check form-switch ms-2">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault23"
                          checked onchange="visible()">
                  </div>
              </div>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0">
                <i class="ni ni-settings fixed-plugin-button-nav cursor-pointer"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">date</p>
                    <h5 class="font-weight-bolder" id="fechaEntregado">
                      
                    </h5>
                    <p class="mb-0">
                      <span class="text-success text-sm font-weight-bolder">Schedule</span>
                      Today
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Time</p>
                    <h5 class="font-weight-bolder" id="reloj">
                      
                    </h5>
                    <p class="mb-0">
                      <span class="text-success text-sm font-weight-bolder">Current</span>
                      Time
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold" style="color:#0b253d00;">buscador</p>
                      <button class="form-control" style="padding: 6px" data-bs-toggle="modal" data-bs-target="#exampleModalBuscador">Búsqueda Avanzada</button>
                    <p class="mb-0" style="color:#0b253d00;">
                      <span class=" text-sm font-weight-bolder" style="color:#0b253d00;">Search</span>
                      Engine
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                    <i class="ni ni-folder-17 text-lg opacity-10"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
      include 'modales/solicitudReversion.php';
      include 'modales/reversionesRechazadas.php';
      include 'modales/reversionesProgramadas.php';
      include 'modales/reversionesRealizadas.php';
      include 'modales/buscador.php';
      
      ?>
      <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4 item1">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <div class="row">
                <div class="col-md-6">
                  <h6 class="text-capitalize">Formato Solicitud Portabilidad</h6>
                </div>
                <div class="col-md-3">
                  <button class="btn btn-primary btn-sm ms-auto item8" id="btnSearchDoc">Documentos</button>
                </div>
                <div class="col-md-3">
                  <button class="btn btn-primary btn-sm ms-auto" data-bs-toggle="modal"
                    data-bs-target="#staticBackdrop">Programar 1006</button>
                </div>
              </div>
            </div>
            <div class="card-body p-3">
              <div class="card-header pb-0">
                <div class="d-flex align-items-center">
                  <p class="mb-3">Datos del Subscriptor</p>
                </div>
                <div class="form-group">
                  <label for="example-text-input" class="form-control-label">Empresa Receptora</label>
                  <select class="form-select" aria-label="Default select example" id="ido">
                    <option value="" selected>-Seleccione un IDO-</option>
                    <?php 
                          while ($row = oci_fetch_array($stid, OCI_ASSOC)) {?>
                            <option value="<?php echo"". $row['IDO']?>"><?php echo"".$row['EMPRESA']?></option>
                            <?php }?>
                  </select>
                </div>
                <p class="text-uppercase text-sm">Tipo de Subscriptor</p>
                <div class="row">
                  <div class="form-check col-md-4">
                    <input class="form-check-input" type="radio" name="exampleRadios1" id="exampleRadios1" value="0"
                      checked>
                    <label class="form-check-label" for="exampleRadios1">Persona Física</label>
                  </div>
                  <div class="form-check col-md-4">
                    <input class="form-check-input" type="radio" name="exampleRadios2" id="exampleRadios2" value="1">
                    <label class="form-check-label" for="exampleRadios2">Persona Moral</label>
                  </div>
                  <div class="form-check col-md-4">
                    <input class="form-check-input" type="radio" name="exampleRadios3" id="exampleRadios3" value="1">
                    <label class="form-check-label" for="exampleRadios3">Entidad Gobierno</label>
                  </div>
                </div>
                <hr class="horizontal dark">
                <div class="d-flex align-items-center">
                  <p class="mb-3">Tipo de Portación</p>
                </div>
                <div class="row mb-3">
                  <div class="d-flex align-items-center">
                    <select class="form-select" aria-label="Default select example" name="" id="PortType">
                      <option value="">-Seleccione Tipo de Portación-</option>
                      <?php 
                          while ($rowfix = oci_fetch_array($stidFix, OCI_ASSOC)) {?>
                            <option value="<?php echo"". $rowfix['TIPO_DE_PORTACION']?>"><?php echo"".$rowfix['TIPO_DE_PORTACION']?></option>
                            <?php }?>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="form-check col-md-6">
                    <input class="form-check-input" type="radio" name="exampleRadios4" id="exampleRadios4" value="N"
                      checked>
                    <label class="form-check-label" for="exampleRadios4">Número Activo</label>
                  </div>
                  <div class="form-check col-md-6">
                    <input class="form-check-input" type="radio" name="exampleRadios5" id="exampleRadios5" value="Y">
                    <label class="form-check-label" for="exampleRadios5">Número Desconectado</label>
                  </div>
                </div>
                <hr class="horizontal dark">
                <div class="d-flex align-items-center">
                  <p class="text-uppercase text-sm">Tiempo Establecido por el cliente</p>
                </div>
                <div class="d-flex align-items-center">
                  <div class="row">
                    <label for="example-text-input" class="form-control-label">Solicitud entregada el día:</label>
                      <!-- <h10 id="fechaEntregado" style="display:none"></h10>
                      <h10 id="reloj" style="display:none;"></h10> -->
                      <h10 id="fechaTimeStamp" style="color: #596CFF; font-size: bold"></h10>
                  </div>
                </div>
                <hr class="horizontal dark">
                <div class="d-flex align-items-center">
                  <p class="text-uppercase text-sm">Datos del proveedor</p>
                </div>
                <div class="form-group">
                  <label for="example-text-input" class="form-control-label">Donador:</label>
                  <select class="form-select" aria-label="Default select example" id="donador">
                    <option selected>A definir por el ABD</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                </div>
                <div class="form-group">
                  <button class="btn btn-secondary" type="button" id="noNip">Nip</button>
                </div>
                <div class="form-group">
                  <div class="d-flex align-items-center">
                    <p class="mb-0">Número (s) a Portar</p>
                  </div><span style="font-size: 10px;">Para números individuales el From y el To se deben llenar con el
                    mismo número.</span>
                  <div class="row">
                    <div class="col-md-4">
                      <label for="example-text-input" class="form-control-label">From:</label>
                    </div>
                    <div class="col-md-4">
                      <label for="example-text-input" class="form-control-label">To:</label>
                    </div>
                    <div class="col-md-4">
                      <label for="example-text-input" class="form-control-label" id="nipLabel">NIP:</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <input class="form-control" type="number" id="numeroFrom">
                    </div>
                    <div class="col-md-4">
                      <input class="form-control nipInterfaz" type="number" id="numeroTo">
                    </div>
                    <div class="col-md-4">
                      <input class="form-control nipInterfaz" type="number" id="nip" name="nipInp">
                    </div>
                  </div>
                </div>
                <div class="mb-3 mostrar-attach" id="attachDocNoNip">
                  <div class="row">
                    <div class="col-md-3">
                      <label for="formFileSm" class="form-label">Seleccione tipo de documento.</label>
                      <select class="form-select" aria-label="Default select example" name="" id="selectDocumentOne">
                        <option value="">-Seleccionar-</option>
                        <option value="S">Formulario de Solicitud de Portación</option>
                        <option value="F">Factura</option>
                        <option value="C">Contrato</option>
                        <option value="I">ID</option>
                        <option value="O">Otro</option>
                        <option value="P">Poder</option>
                        <option value="M">Orden de la Autoridad Competente</option>
                        <option value="E">Aviso escrito firmado por el Suscriptor</option>
                        <option value="N">Comprobante de Numeración</option>
                        <option value="R">Documento de Recuperación (Comprobante de Cancelación)</option>
                      </select>
                    </div>
                    <div class="col-md-9">
                      <label for="formFileSm" class="form-label">Adjuntar Documento Seleccionado.</label>
                      <input class="form-control " id="formFileSmIdentifica" type="file" accept=".pdf,.png,.jpg">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label for="formFileSm" class="form-label">Seleccione tipo de documento.</label>
                      <select class="form-select" aria-label="Default select example" name="" id="selectDocumentTwo">
                        <option value="">-Seleccionar-</option>
                        <option value="S">Formulario de Solicitud de Portación</option>
                        <option value="F">Factura</option>
                        <option value="C">Contrato</option>
                        <option value="I">ID</option>
                        <option value="O">Otro</option>
                        <option value="P">Poder</option>
                        <option value="M">Orden de la Autoridad Competente</option>
                        <option value="E">Aviso escrito firmado por el Suscriptor</option>
                        <option value="N">Comprobante de Numeración</option>
                        <option value="R">Documento de Recuperación (Comprobante de Cancelación)</option>
                      </select>
                    </div>
                    <div class="col-md-9">
                      <label for="formFileSm" class="form-label">Adjuntar Documento Seleccionado.</label>
                      <input class="form-control " id="formFileSmSolOriginal" type="file" accept=".pdf,.png,.jpg">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label for="formFileSm" class="form-label">Seleccione tipo de documento.</label>
                      <select class="form-select" aria-label="Default select example" name="" id="selectDocumentThree">
                        <option value="">-Seleccionar-</option>
                        <option value="S">Formulario de Solicitud de Portación</option>
                        <option value="F">Factura</option>
                        <option value="C">Contrato</option>
                        <option value="I">ID</option>
                        <option value="O">Otro</option>
                        <option value="P">Poder</option>
                        <option value="M">Orden de la Autoridad Competente</option>
                        <option value="E">Aviso escrito firmado por el Suscriptor</option>
                        <option value="N">Comprobante de Numeración</option>
                        <option value="R">Documento de Recuperación (Comprobante de Cancelación)</option>
                      </select>
                    </div>
                    <div class="col-md-9">
                      <label for="formFileSm" class="form-label">Adjuntar Documento Seleccionado.</label>
                      <input class="form-control " id="formFileSmJurada" type="file" accept=".pdf,.png,.jpg">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12" id="listadoMoralGob" style="display:none;">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="excelFile" class="form-label">Importar listado de números.</label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-8">
                        <input type="file" class="form-control" id="excelFile">
                      </div>
                      <div class="col-md-4">
                        <button class="btn btn-primary" id="cleanTable">Limpiar Tabla</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 conTableY" id="conTable">
                    <div class="table-responsive">
                      <div id="paginador"></div>
                      <table class="table table-bordered" id="tablePhoneId">
                        <thead>
                          <tr></tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="example-text-input" class="form-control-label">Comentarios:</label>
                  <textarea class="form-control" id="comentarios" rows="3"></textarea>
                </div>
                <div class="form-group">
                  <button class="btn btn-primary btn-sm ms-auto" id="enviarApi" value="Enviar">Enviar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-12 item3">
          <div class="card">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Reversiones</h6>
            </div>
            <div class="card-body p-3">
              <ul class="list-group">
                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                  <div class="d-flex align-items-center">
                    <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                      <i class="ni ni-mobile-button text-white opacity-10"></i>
                    </div>
                    <div class="d-flex flex-column">
                      <h6 class="mb-1 text-dark text-sm">Solicitud de Reversión de Portación</h6>
                      <span class="text-xs">Mensaje 4001 Reversión </span>
                    </div>
                  </div>
                  <div class="d-flex">
                    <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                        class="ni ni-bold-right" aria-hidden="true" 
                        data-bs-toggle="modal" data-bs-target="#exampleModalSolicitudReversion"></i></button>
                  </div>
                </li>
                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                  <div class="d-flex align-items-center">
                    <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                      <i class="ni ni-tag text-white opacity-10"></i>
                    </div>
                    <div class="d-flex flex-column">
                      <h6 class="mb-1 text-dark text-sm">Reversiones Rechazadas</h6>
                      <span class="text-xs">Hay 4 reversiones pendientes</span></span>
                    </div>
                  </div>
                  <div class="d-flex">
                    <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"
                      data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="ni ni-bold-right"
                        aria-hidden="true"></i></button>
                  </div>
                </li>
                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                  <div class="d-flex align-items-center">
                    <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                      <i class="ni ni-box-2 text-white opacity-10"></i>
                    </div>
                    <div class="d-flex flex-column">
                      <h6 class="mb-1 text-dark text-sm">Reversiones Programadas</h6>
                      <span class="text-xs">Hay 4 reversiones programadas</span></span>
                    </div>
                  </div>
                  <div class="d-flex">
                    <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"
                      data-bs-toggle="modal" data-bs-target="#exampleModalProg"><i class="ni ni-bold-right"
                        aria-hidden="true"></i></button>
                  </div>
                </li>
                <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                  <div class="d-flex align-items-center">
                    <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                      <i class="ni ni-satisfied text-white opacity-10"></i>
                    </div>
                    <div class="d-flex flex-column">
                      <h6 class="mb-1 text-dark text-sm">Reversiones Realizadas</h6>
                      <span class="text-xs font-weight-bold">No hay solicitudes de Reversión</span>
                    </div>
                  </div>
                  <div class="d-flex">
                    <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"
                      data-bs-toggle="modal" data-bs-target="#exampleModalRealizadas"><i class="ni ni-bold-right"
                        aria-hidden="true"></i></button>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4 item5">
        <div class="col-lg-12 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <div class="row">
                <div class="col-md-6">
                  <h6 class="text-capitalize">Consulta de Seguimiento</h6>
                </div>
              </div>
            </div>
            <div class="card-body p-3 tableConsY">
              <div class="card-header pb-0 ">
                <div class="d-flex align-items-center">
                  <p class="mb-0">Solicitudes de Portación Recibidas</p>
                </div>
                <div id="tableCons">
                  <!-- Aquí va la consulta general-------------------- -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<!-- Seguimiento al 1005 ------------------------------------------ -->
      <div class="row mt-4 item9">
        <div class="col-lg-12 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <div class="row">
                <div class="col-md-6">
                  <h6 class="text-capitalize">Consulta de Programables</h6>
                </div>
              </div>
            </div>
            <div class="card-body p-3 tableConsY">
              <div class="card-header pb-0 ">
                <div class="d-flex align-items-center">
                  <p class="mb-0">PortID programables</p>
                </div>
                <div id="tableConsMilCinco">
                  <!-- Aquí va la consulta del 1005-------------------- -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4 item6">
        <div class="col-lg-12 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <div class="row">
                <div class="col-md-6">
                  <h6 class="text-capitalize">Cancelación de Portación</h6>
                </div>
              </div>
            </div>
            <div class="card-body p-3">
              <div class="card-header pb-0">
                <div class="d-flex align-items-center">
                  <p class="mb-0">Cancelar PortID</p>
                </div>
                <!-- ---------------------------------------------------------------------------------- -->
                <div class="d-flex align-items-center">
                  <p class="text-uppercase text-sm"></p>
                </div>
                <div class="col-md-12">
                  <div class="d-flex align-items-center">
                    <p class="mb-0"></p>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <input class="form-control" placeholder="Ingrese el PortID" type="text" value="" name="idPortDelete" id="idPortDelete">
                    </div>
                    <div class="col-md-6">
                      <button class="btn btn-warning" style="float:right"  id="btnIdPortDelete">Cancelar Portación</button>
                    </div>
                  </div>
                </div>
                <div class="d-flex align-items-center">
                    <p class="mb-0">Mensaje 3001 de cancelación</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-7 mb-lg-0 mb-4 item7">
        <div class="card z-index-2 h-100">
          <div class="card-header pb-0 pt-3 bg-transparent">
            <div class="row">
              <div class="col-md-6">
                <h6 class="text-capitalize">Solicitud de Eliminación</h6>
              </div>
            </div>
          </div>
          <div class="card-body p-3">
            <div class="card-header pb-0">
              <p class="text-uppercase text-sm">Tipo de Servicio</p>
              <div class="row">
                <div class="form-check col-md-4">
                  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios7" value="option1"
                    checked>
                  <label class="form-check-label" for="exampleRadios7">Fijo</label>
                </div>
                <div class="form-check col-md-4">
                  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios6" value="option2">
                  <label class="form-check-label" for="exampleRadios6">No Geográfico</label>
                </div>
              </div>
              <div class="form-group">
                <label for="example-text-input" class="form-control-label">Cliente</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
              </div>
              <hr class="horizontal dark">
              <div class="d-flex align-items-center">
                <p class="mb-0">Números Telefónicos</p>
              </div>
              <hr class="horizontal dark">

              <!-- Aquí van los número que se van a eliminar -->
              <div class="row">
                <div class="col-md-12" id="listadoMoralGobEliminacion">
                  <div class="row">
                    <div class="col-md-12">
                      <label for="excelFileEliminacion" class="form-label">Importar listado de números.</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-8">
                      <input type="file" class="form-control" id="excelFileEliminacion">
                    </div>
                    <div class="col-md-4">
                      <button class="btn btn-primary" id="cleanTableEliminacion">Limpiar Tabla</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 conTableYE" id="conTableDos">
                  <div class="table-responsive">
                    <div id="paginador"></div>
                    <table class="table table-bordered" id="tablePhoneIdDos">
                      <thead>
                        <tr></tr>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <hr class="horizontal dark">
              <div class="d-flex align-items-center">
                <p class="text-uppercase text-sm">Rango</p>
              </div>
              <div class="col-md-12">
                <div class="d-flex align-items-center">
                  <p class="mb-0">Número (s) a Portar</p>
                </div><span style="font-size: 10px;">Para números individuales el From y el To se deben llenar con el
                  mismo número.</span>
                <div class="row">
                  <div class="col-md-6">
                    <label for="example-text-input" class="form-control-label">From:</label>
                    <input class="form-control" type="text" value="" name="" id="fromDelete">
                  </div>
                  <div class="col-md-6">
                    <label for="example-text-input" class="form-control-label">To:</label>
                    <input class="form-control" type="text" value="" name="" id="toDelete">
                  </div>
                </div>
              </div>
              <hr class="horizontal dark">
              <div class="form-group">
                <button type="button" class="btn btn-primary" id="deleteNumber">Eliminar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                ©
                <script>
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
  <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="ni ni-settings py-2"> </i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3 ">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Opciones de Configuración</h5>
          <p>Customice la vista.</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="fa fa-close"></i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0 overflow-auto">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary active" data-color="primary"
              onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between 2 different sidenav types.</p>
        </div>
        <div class="d-flex">
          <button class="btn bg-gradient-primary w-100 px-3 mb-2 active me-2" data-class="bg-white"
            onclick="sidebarType(this)">White</button>
          <button class="btn bg-gradient-primary w-100 px-3 mb-2" data-class="bg-default"
            onclick="sidebarType(this)">Dark</button>
        </div>
        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
        <!-- Navbar Fixed -->
        <div class="d-flex my-3">
          <h6 class="mb-0">Navbar Fixed</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
          </div>
        </div>
        <hr class="horizontal dark my-sm-4">
        <div class="mt-2 mb-5 d-flex">
          <h6 class="mb-0">Light / Dark</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>

        <div class="w-100 text-center">

        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Criterios de Búsqueda</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeBusqueda"><i
              class="ni ni-fat-remove" style="color:#000; font-size:17px"></i></button>
        </div>
        <div class="modal-body">
          <div class="card-body">
            <p class="text-uppercase text-sm"></p>
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <input class="form-control" type="text" value="" id="idPort" placeholder="ID de Portación:">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <button type="button" class="btn btn-primary" id="buscarPortid" style="width: 110px">Buscar</button>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <input type="date-timelocal" class="form-control" style="display: none;" id="txtdate"
                    placeholder="Programar Fecha">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <button type="button" class="btn btn-success" id="programar"
                    style="display: none; width: 110px">Programar</button>
                </div>
              </div>
            </div>
          </div>

          <div class="resultadosBusqueda">
            <hr class="horizontal dark mt-0">
            <h5 class="modal-title" id="">Resultados de la Búsqueda</h5>
            <div class="card-body">
              <p class="text-uppercase text-sm"></p>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group" id="portIdConsulta">

                  </div>
                  <div class="row mt-4 item2">
                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-header pb-0 p-3">
                          <h6 class="mb-0">Programar Portación</h6>
                        </div>
                        <!--  -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">

        </div>
      </div>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/core/sweetalert2.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <!-- Calendar -->
  <script src="../assets/js/plugins/flatpickr.js"></script>
  <!-- js general -->
  <script src="../assets/js/senderSPN.js"></script>
  <script src="../assets/js/readExcelFile.js"></script>
  <script src="../assets/js/paginator.js"></script>
  <script src="../assets/js/appExcel.js"></script>
  <script src="../assets/js/consultaSPN.js"></script>
  <script src="../assets/js/consulta1005.js"></script>
  <script src="../assets/js/eliminarNumero.js"></script>
  <script src="../assets/js/sender4001.js"></script>
  <script src="../assets/js/timer.js"></script>

  <script>

    /* dateMin = new Date();
    function getDateDay() {
        let days = dateMin.getDate();
        return days < 10 ? '0' + days : days;
    }
    let mesAnyoDate = ['01','02', '03','04','05','06','07','08','09','10','11','12'];
    let mostrarDate = `${dateMin.getFullYear()}` + `${mesAnyoDate[dateMin.getMonth()]}` + `${getDateDay() +1}`;
    console.log(mostrarDate); */
    dateTimeZone();

    flatpickr("#txtdate", {
      minDate: "today",
      maxDate: new Date().fp_incr(5), // 14 days from now
      "disable": [
        function (date) {
          // return true to disable
          return (date.getDay() === 0);

        }
      ],
      "locale": {
        "firstDayOfWeek": 1 // start week on Monday
      }
    });

    flatpickr("#txtdateRevert", {
      minDate: "today",
      maxDate: new Date().fp_incr(5), // 14 days from now
      "disable": [
        function (date) {
          // return true to disable
          return (date.getDay() === 0);

        }
      ],
      "locale": {
        "firstDayOfWeek": 1 // start week on Monday
      }
    });

    const fileExcelClick = document.querySelector("#excelFile");
    const conTableX = document.querySelector(".conTableY");
    fileExcelClick.addEventListener("click", () => {
      conTableX.style.display = "inline";
    });

    const fileExcelClickE = document.querySelector("#excelFileEliminacion");
    const conTableDos = document.querySelector(".conTableYE");
    fileExcelClickE.addEventListener("click", () => {
      conTableDos.style.display = "inline";
    });

    const mostrar = document.querySelector("#btnSearch");
    const mensaje = document.querySelector(".item1");

    mostrar.addEventListener("click", () => {
      if ("#btnSearch") {
        mensaje.style.display = "inline";
        mensajeEliminar.style.display = "none";
        //mensaje3.style.display = "none";
        //mensajeDoc.style.display = "inline";
        mensajeConsultaEliminados.style.display = "none";
        mensajeConsulta.style.display = "none";
        mensajeConsultaProgramables.style.display = "none";
        //mensaje.classList.toggle("item1");
      }
    });

    /* const mostrar3 = document.querySelector("#btnSearch3");
    const mensaje3 = document.querySelector(".item3");

    mostrar3.addEventListener("click", () => {  //Reactivar esta sección cuando se implemente el mensaje 4001
      mensaje3.style.display = "inline";
      mensajeEliminar.style.display = "none";
      mensajeConsulta.style.display = "none";
      mensajeConsultaProgramables.style.display = "none";
      mensaje.style.display = "none";
      //mensajeDoc.style.display = "none";
      mensajeConsultaEliminados.style.display = "none";
    }); */

    const mostrarDoc = document.querySelector("#btnSearchDoc");
    const mensajeDoc = document.querySelector(".item4");

    mostrarDoc.addEventListener("click", () => {
      //mensajeDoc.style.display = "inline";
      mensajeConsulta.style.display = "none";
      mensajeConsultaProgramables.style.display = "none";
      mensajeConsultaEliminados.style.display = "none";
      mensajeEliminar.style.display = "none";
    });

    const mostrarConsulta = document.querySelector("#btnSearchConsulta");
    const mensajeConsulta = document.querySelector(".item5");

    mostrarConsulta.addEventListener("click", () => {
      mensajeConsulta.style.display = "inline";
      mensajeConsultaEliminados.style.display = "none";
      mensajeEliminar.style.display = "none";
      mensaje.style.display = "none";
      //mensaje3.style.display = "none";
      //mensajeDoc.style.display = "none";
    });

    const mostrarConsultaProgramables = document.querySelector("#btnSearchProgramables");
    const mensajeConsultaProgramables = document.querySelector(".item9");

    mostrarConsultaProgramables.addEventListener("click", () => {
      mensajeConsultaProgramables.style.display = "inline";
      mensajeConsulta.style.display = "none";
      mensajeConsultaEliminados.style.display = "none";
      mensajeEliminar.style.display = "none";
      mensaje.style.display = "none";
      //mensaje3.style.display = "none";
      //mensajeDoc.style.display = "none";
    });

    const mostrarConsultaEliminados = document.querySelector("#btnSearchConsultaEliminados");
    const mensajeConsultaEliminados = document.querySelector(".item6");

    mostrarConsultaEliminados.addEventListener("click", () => {
      mensajeConsultaEliminados.style.display = "inline";
      mensajeConsulta.style.display = "none";
      mensajeConsultaProgramables.style.display = "none";
      mensajeEliminar.style.display = "none";
      mensaje.style.display = "none";
      //mensaje3.style.display = "none";
      //mensajeDoc.style.display = "none";
    });

    const mostrarEliminar = document.querySelector("#btnSearchEliminar");
    const mensajeEliminar = document.querySelector(".item7");

    mostrarEliminar.addEventListener("click", () => {
      mensajeEliminar.style.display = "inline";
      mensajeConsulta.style.display = "none";
      mensajeConsultaProgramables.style.display = "none";
      mensajeConsultaEliminados.style.display = "none";
      mensaje.style.display = "none";
      //mensaje3.style.display = "none";
      //mensajeDoc.style.display = "none";
    });

    const btnNoNip = document.querySelector('#noNip');
    btnNoNip.addEventListener('click', () => {
      fBotonNip();
    });

    const btnAttachment = document.querySelector('#nip');
    btnAttachment.addEventListener('click', () => {
      let valor = document.querySelector('#nip').value;
      const attachDiv = document.querySelector('.mostrar-attach');
      if (valor == "Attachments") {
        attachDiv.style.display = "inline";
      }else{
        
      }
    });

    function fBotonNip() {
      const nips = document.getElementById('noNip');
      const labelNip = document.getElementById('nipLabel');
      const nipInput = document.getElementById('nip');
      const attachDiv = document.querySelector('.mostrar-attach');
      const nipBotton = "button";
      const nipButton = "btn btn-primary";
      const nipButtom = "Attachments";
      const nipName = "nombreNip";
      const nipBoton = "botonNipAttach";
      const inputType = "text";
      const inputTypeIn = "form-control";
      const inputTypeVal = "";
      const inputTypeId = "nip";
      if (nips.innerHTML == 'Nip') {
        nips.innerHTML = 'Sin Nip';
        labelNip.innerHTML = "";
        nipInput.setAttribute('type', nipBotton);
        nipInput.setAttribute('class', nipButton);
        nipInput.setAttribute('Value', nipButtom);
        nipInput.setAttribute('name', nipName);
        //nipInput.setAttribute('id', nipBoton);
      } else {
        nips.innerHTML = 'Nip';
        labelNip.innerHTML = "NIP:";
        nipInput.setAttribute('type', inputType);
        nipInput.setAttribute('class', inputTypeIn);
        nipInput.setAttribute('Value', inputTypeVal);
        attachDiv.style.display = "none";
        //nipInput.setAttribute('id', inputTypeId);
      }
    }

    const checkexampleRadios1 = document.querySelector('#exampleRadios1');
    const checkexampleRadios2 = document.querySelector('#exampleRadios2');
    const checkexampleRadios3 = document.querySelector('#exampleRadios3');
    const checkexampleRadios4 = document.querySelector('#exampleRadios4');
    const checkexampleRadios5 = document.querySelector('#exampleRadios5');

    checkexampleRadios1.addEventListener('click', () => {
      if (checkexampleRadios1.checked) {
        document.getElementById("exampleRadios2").checked = false;
        document.getElementById("exampleRadios3").checked = false;
        conTableX.style.display = "none";
        excelFile.value = "";
      }
      numeroFisica();
    });

    checkexampleRadios2.addEventListener('click', () => {
      if (checkexampleRadios2.checked) {
        document.getElementById("exampleRadios1").checked = false;
        document.getElementById("exampleRadios3").checked = false;
      }
      numeroGobiernoMoral();
    });

    checkexampleRadios3.addEventListener('click', () => {
      if (checkexampleRadios3.checked) {
        document.getElementById("exampleRadios1").checked = false;
        document.getElementById("exampleRadios2").checked = false;
      }
      numeroGobiernoMoral();
    });

    checkexampleRadios4.addEventListener('click', () => {
      if (checkexampleRadios4.checked) {
        document.getElementById("exampleRadios5").checked = false;
      }
      if (checkexampleRadios2.checked || checkexampleRadios3.checked) {

      } else {
        numeroFisica();
      }

    });

    checkexampleRadios5.addEventListener('click', () => {
      if (checkexampleRadios5.checked) {
        document.getElementById("exampleRadios4").checked = false;
      }
      numeroGobiernoMoral();
      var moralGobierno = document.getElementById("listadoMoralGob");
      if (checkexampleRadios1.checked && checkexampleRadios5.checked) {
        moralGobierno.style.display = "none";
      }
    });


    function numeroGobiernoMoral() {
      var moralGob = document.getElementById("listadoMoralGob");
      const nipNonip = document.getElementById('noNip');
      if (moralGob.style.display === "none") {
        moralGob.style.display = "block";
        nipNonip.style.display = "none";
      }

      const nips = document.getElementById('noNip');
      const labelNip = document.getElementById('nipLabel');
      const nipInput = document.getElementById('nip');
      const attachDiv = document.querySelector('.mostrar-attach');
      const nipBotton = "button";
      const nipButton = "btn btn-primary";
      const nipButtom = "Attachments";
      const nipName = "nombreNip";
      const nipBoton = "botonNipAttach";
      const inputType = "text";
      const inputTypeIn = "form-control";
      const inputTypeVal = "";
      const inputTypeId = "nip";
      if (nips.innerHTML == 'Nip') {
        nips.innerHTML = 'Sin Nip';
        labelNip.innerHTML = "";
        nipInput.setAttribute('type', nipBotton);
        nipInput.setAttribute('class', nipButton);
        nipInput.setAttribute('Value', nipButtom);
        nipInput.setAttribute('name', nipName);
        //nipInput.setAttribute('id', nipBoton);
      }

    }

    function numeroFisica() {
      var moralGob = document.getElementById("listadoMoralGob");
      const nipNonip = document.getElementById('noNip');
      if (moralGob.style.display === "block") {
        moralGob.style.display = "none";
        nipNonip.style.display = "block";
      }
      const nips = document.getElementById('noNip');
      const labelNip = document.getElementById('nipLabel');
      const nipInput = document.getElementById('nip');
      const attachDiv = document.querySelector('.mostrar-attach');
      const nipBotton = "button";
      const nipButton = "btn btn-primary";
      const nipButtom = "Attachments";
      const nipName = "nombreNip";
      const nipBoton = "botonNipAttach";
      const inputType = "text";
      const inputTypeIn = "form-control";
      const inputTypeVal = "";
      const inputTypeId = "nip";
      if (nips.innerHTML == 'Nip') {

      } else {
        nips.innerHTML = 'Nip';
        labelNip.innerHTML = "NIP:";
        nipInput.setAttribute('type', inputType);
        nipInput.setAttribute('class', inputTypeIn);
        nipInput.setAttribute('Value', inputTypeVal);
        attachDiv.style.display = "none";
        //nipInput.setAttribute('id', inputTypeId);
      }

    }

  </script>
  <script>
    function visible() {
      var elem = document.getElementById('profileVisibility2');
      if (elem) {
        if (elem.innerHTML == "Switch to visible") {
            elem.innerHTML = "Switch to invisible";
        } else {
            elem.innerHTML = "Switch to visible";
            window.location = "../php/model/logout.php";
        }
      }
    }

    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="../assets/js/plugins/buttons.github.io_buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.0.4"></script>
</body>

</html>
<?php 
} else {
  session_start();
  session_destroy();
  header("Location:../login.php");
}
?>