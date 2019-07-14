<?php

// Dia de Ontem Até amanhã de Hoje
$DataZona = new DateTimeZone('America/Sao_Paulo');
$data = new DateTime('NOW');
$data->setTimezone($DataZona);
$data_Hoje = date_format($data, 'Y-m-d');

?>

<!-- Datatables -->
<link href="assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
<!-- / Datatables -->

<h2>Visão Por Campanha</h2>

<table class="datatable-buttons table table-striped table-bordered table-hover" style="width:100%">
    <thead>
        <tr>
            <th>Campanha</th>
            <th>Escalados</th>
            <th>Absenteísmo</th>
            <th>%</th>
        </tr>
    </thead>

    <tbody>
        <?php

        //Conectando ao Banco
        require_once 'assets/includes/conexao.php';

        $sql = sprintf("SELECT * FROM tbl_campanha WHERE CodiCampanha<>'2' AND 
            CodiCampanha<>'3' AND CodiCampanha<>'4' AND CodiCampanha<>'7'");
        $query_campanha = $ligacao->query($sql);

        $i=0;
        while ($row_campanha = mysqli_fetch_assoc($query_campanha)) {

            $CodiCampanha = $row_campanha['CodiCampanha'];

            $sql_escalado = "SELECT U.CodiUsuario, U.Usuario, US.Usuario, USU.Usuario, 
            Campanha, NivelAcesso, DataEscalaUsuario
            FROM dimensiona_novo.tbl_escala E, dimensiona_novo.tbl_usuarios U,
            dimensiona_novo.tbl_campanha C, dimensiona_novo.tbl_nivelacesso N,
            dimensiona_novo.tbl_supervisor S, dimensiona_novo.tbl_coordenador CO,
            dimensiona_novo.tbl_usuarios US, dimensiona_novo.tbl_usuarios USU
            WHERE (U.CodiUsuario=E.CodiUsuario) AND (U.CodiCampanha='$CodiCampanha') AND 
            (E.DataEscalaUsuario='$data_Hoje') AND (E.HorarioEscalaUsuario<>'F') AND
            (U.CodiCampanha=C.CodiCampanha) AND (U.CodiNivelAcesso=N.CodiNivelAcesso) AND
            (U.CodiSupervisor=S.CodiSupervisor) AND (U.CodiCoordenador=CO.CodiCoordenador) AND
            (S.CodiUsuario=US.CodiUsuario) AND (CO.CodiUsuario=USU.CodiUsuario);";
            $query_escalado = $ligacao->query($sql_escalado);

            $sql = "SELECT U.CodiUsuario, U.Usuario, US.Usuario, USU.Usuario, Campanha, NivelAcesso, DataEscalaUsuario 
            FROM dimensiona_novo.tbl_ausencia A, dimensiona_novo.tbl_escala E, dimensiona_novo.tbl_usuarios U, 
            dimensiona_novo.tbl_campanha C, dimensiona_novo.tbl_nivelacesso N,
            dimensiona_novo.tbl_supervisor S, dimensiona_novo.tbl_coordenador CO,
            dimensiona_novo.tbl_usuarios US, dimensiona_novo.tbl_usuarios USU
            WHERE A.CodiUsuario=E.CodiUsuario AND A.DataAusencia=E.DataEscalaUsuario AND
            (E.DataEscalaUsuario='$data_Hoje') AND (E.HorarioEscalaUsuario<>'F') AND
            U.CodiUsuario=A.CodiUsuario AND U.CodiUsuario=E.CodiUsuario AND
            (U.CodiCampanha='$CodiCampanha') AND U.CodiCampanha=C.CodiCampanha AND
            (U.CodiNivelAcesso=N.CodiNivelAcesso) AND
            (U.CodiSupervisor=S.CodiSupervisor) AND (U.CodiCoordenador=CO.CodiCoordenador) AND
            (S.CodiUsuario=US.CodiUsuario) AND (CO.CodiUsuario=USU.CodiUsuario);";
            $query_abs = $ligacao->query($sql);

            $Absenteismo_Porcentagem = 0;

            if ($query_abs->num_rows != 0) {
                $Absenteismo_Porcentagem = ($query_abs->num_rows * 100) / $query_escalado->num_rows;
                $Absenteismo_Porcentagem = number_format($Absenteismo_Porcentagem, 2, ',', '.');
            }

            printf('<tr>');
            printf('<td>' . $row_campanha['Campanha'] . '</td>');
            printf('<td id="td_botao" data-toggle="modal" data-target=".escala_modal_' . $i . '"><a href="#">' . $query_escalado->num_rows . '</a></td>');
            printf('<td id="td_botao" data-toggle="modal" data-target=".abs_modal_' . $i . '"><a href="#">' . $query_abs->num_rows . '</a></td>');
            print('<td>' . $Absenteismo_Porcentagem . '% </td>');
            printf('</tr>');

        ?>

        <div class="modal fade escala_modal_<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title" id="gridSystemModalLabel">Escalados</h3>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <?php 
                            $Codi_Faltante = '';

                            if ($query_escalado->num_rows == 0){
                                printf('<h2></h2>');
                                printf('<h2>Não foram encontrados resultados...</h2>' . '<br />');
                                printf('<h2>Desculpe...</h2>');
                            }
                            else{
                                while ($row_faltantes = mysqli_fetch_array($query_escalado)){
                                    $data = new DateTime($row_faltantes[6]);
                                    $row_faltantes[6] = date_format($data, 'd-m-Y');

                                    echo '<div class="col-sm-7">';
                                    echo '<h4 style="display:inline-block;">Nome: ' . $row_faltantes[1] . '</h4>';
                                    echo '<hr /></div>';
                                    echo '<div class="col-sm-5">';
                                    echo '<h4 style="display:inline-block;">Data: ' . $row_faltantes[6] . '</h4>';
                                    echo '<hr /></div>';
                                    $Codi_Faltante = $row_faltantes[0];
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Fechar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade abs_modal_<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title" id="gridSystemModalLabel">Absenteísmo</h3>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <?php 
                            $Codi_Faltante = '';

                            if ($query_abs->num_rows == 0){
                                printf('<h2></h2>');
                                printf('<h2>Não foram encontrados resultados...</h2>' . '<br />');
                                printf('<h2>Desculpe...</h2>');
                            }
                            else{
                                while ($row_abs = mysqli_fetch_array($query_abs)){
                                    $data = new DateTime($row_abs[6]);
                                    $row_abs[6] = date_format($data, 'd-m-Y');

                                    echo '<div class="col-sm-7">';
                                    echo '<h4 style="display:inline-block;">Nome: ' . $row_abs[1] . '</h4>';
                                    echo '<hr /></div>';
                                    echo '<div class="col-sm-5">';
                                    echo '<h4 style="display:inline-block;">Data: ' . $row_abs[6] . '</h4>';
                                    echo '<hr /></div>';
                                    $Codi_Faltante = $row_abs[0];
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Fechar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        
        <?php

            $i++;
        }

        mysqli_close($ligacao);

        ?>
    </tbody>
</table>

<!-- Datatables -->
<script src="assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="assets/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="assets/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="assets/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="assets/vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
<script src="assets/vendors/jszip/dist/jszip.min.js"></script>
<script src="assets/vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="assets/vendors/pdfmake/build/vfs_fonts.js"></script>
<!-- Datatables -->

<script>
    $(document).ready(function() {
        var handleDataTableButtons = function() {
            if ($(".datatable-buttons").length) {
                $(".datatable-buttons").DataTable({
                    buttons: [{
                            extend: 'copy',
                            text: 'Copiar',
                            exportOptions: {
                                modifier: {
                                    page: 'current'
                                }
                            }
                        },
                        'csv', 'excel', 'pdf'
                    ],
                    dom: "Bfrtip",
                    deferRender: true,
                    responsive: true,
                });
            }
        };

        TableManageButtons = function() {
            "use strict";
            return {
                init: function() {
                    handleDataTableButtons();
                }
            };
        }();

        TableManageButtons.init();
    });
</script>
<!-- /Datatables -->