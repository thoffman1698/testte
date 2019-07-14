<?php

// Conectando ao Banco
// require_once 'assets/includes/conexao.php';

// $sql = "SELECT * FROM tbl_usuarios WHERE CodiNivelAcesso='6'";
// $query = $ligacao->query($sql);

// mysqli_close($ligacao);

// Dia de Ontem Até amanhã de Hoje
$DataZona = new DateTimeZone('America/Sao_Paulo');
$data_end = new DateTime('today');
$data_end->setTimezone($DataZona);
$data_end = date_format($data_end, 'd/m/Y');
$data_start = new DateTime('today');
$data_start->setTimezone($DataZona);
$data_start = date_format($data_start, 'd/m/Y');

$data = $data_start . ' - ' . $data_end;

?>

<!-- Datatables -->
<link href="assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
<!-- / Datatables -->

<!-- Select2 -->
<link href="assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<!-- / Select2 -->

<!-- bootstrap-daterangepicker -->
<link href="assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- / bootstrap-daterangepicker -->

<h2>Relatório Por Supervisão</h2>

<form action="index_sistema.php" method="GET">
    <!-- <div class="col-md-4 col-md-offset-1">
        <?php

        echo "<select class='select2_Supervisor form-control' name='CodiSupervisor'>";
        echo "<option value=''></option>";

        while($row = mysqli_fetch_array($query)){
            $CodiSupervisor = $row["CodiUsuario"]; 
            $Supervisor = $row["Usuario"];
                            
            echo "<option value='$CodiSupervisor'>$Supervisor</option>";
        }

        echo "</select>";

        ?>
    </div> -->
    <div class="col-md-4 col-md-offset-3 form-group" style="display: inline-table;">
        <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
        <input type="text" name="Data_Supervisor" id="reservation" class="form-control" data-inputmask="'mask': '99/99/9999 - 99/99/9999'" value="<?php echo $data; ?>" />
    </div>
    <div class="col-md-3 form-group">
        <button type="submit" class="btn btn-danger">Pesquisar</button>
    </div>
</form>


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
                    "order": [2, 'asc'],
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

<!-- bootstrap-daterangepicker -->
<script src="assets/vendors/moment/min/moment.min.js"></script>
<script src="assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- bootstrap-daterangepicker -->
<script>
  $(document).ready(function() {
    var cb = function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    };

    var optionSet1 = {
      opens: 'left',
      buttonClasses: ['btn btn-default'],
      applyClass: 'btn-small btn-success',
      cancelClass: 'btn-small btn-danger',
      locale: {
        applyLabel: 'Selecionar',
        cancelLabel: 'Cancelar',
        format: 'DD/MM/YYYY',
        daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        firstDay: 1
      }
    };
    $('#reservation').daterangepicker(optionSet1, cb);
    $('#reservation').on('show.daterangepicker', function() {
      console.log("show event fired");
    });
    $('#reservation').on('hide.daterangepicker', function() {
      console.log("hide event fired");
    });
    $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
      console.log("cancel event fired");
    });
  });
</script>
<!-- /bootstrap-daterangepicker -->

<!-- Select2 -->
<script src="assets/vendors/select2/dist/js/select2.full.min.js"></script>
<!-- / Select2 -->

<script>
    $(document).ready(function() {
        $(".select2_Supervisor").select2({
            placeholder: "Selecione o Supervisor",
            allowClear: true
        });

        $(".select2_group").select2({});
        $(".select2_multiple").select2({
            maximumSelectionLength: 4,
            placeholder: "With Max Selection limit 4",
            allowClear: true
        });
    });
</script>
<!-- /Select2 -->