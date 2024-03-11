<div class="modal fade" id="exampleModalSolicitudReversion" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width:150%">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Solicitud de Reversión Msg 4001</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ni ni-fat-remove" style="color:#000; font-size:17px"></i>
                </button>
            </div>
            <div class="modal-body">
                <span>Ingrese el número para la reversión</span>
                <div class="row">
                    <div class="col-md-6">
                        <label for="example-text-input" class="form-control-label">From:</label>
                        <input class="form-control" type="number" id="numRevFrom">
                    </div>
                    <div class="col-md-6">
                        <label for="example-text-input" class="form-control-label">To:</label>
                        <input class="form-control nipInterfaz" type="number" id="numRevTo">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="listadoRevert">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="formFileSm" class="form-label">Seleccione Motivo de Reversión.</label>
                                <select class="form-select" aria-label="Default select example" name=""
                                    id="selectRevertType">
                                    <option value="">-Seleccionar-</option>
                                    <option value="1001">Mutuo Acuerdo</option>
                                    <option value="1002">Decesión administrativa o judicial</option>
                                    <option value="1003">A petición del Donante</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="formFileSm" class="form-label">Seleccione Fecha.</label>
                                    <input type="date-timelocal" class="form-control" id="txtdateRevert"
                                        placeholder="Programar Fecha">
                                </div>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                    </div>
                    <div class="mb-3" id="attachDocNoNip" style="">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="formFileSm" class="form-label">Seleccione Tipo de Documento</label>
                                <select class="form-select" aria-label="Default select example" name=""
                                    id="selectDocumentRevert">
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
                            <div class="col-md-6">
                                <label for="formFileSm" class="form-label">Seleccione Archivo</label>
                                <input class="form-control " id="formFileSmRevert" type="file" accept=".pdf,.jpeg,.jpg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="sendRevert">Enviar</button>
            </div>
        </div>
    </div>
</div>