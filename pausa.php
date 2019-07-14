<?php

$DataZona = new DateTimeZone('Brazil/East');
$data = new DateTime('NOW');
$data->setTimezone($DataZona);
$data_Ano = date_format($data, 'Y-m-d');

$link_pausas = 'https://comunicabrasil.evolux.net.br/api/v1/report/complete_pause?start_date=' . $data_Ano . '&end_date=' . $data_Ano . '&token=7beadf74-6ad7-42f9-8d0b-e90f98e3bfda';
$json_file = file_get_contents($link_pausas);
$json_str = json_decode($json_file, true);
$itens = $json_str['data'];

$data_Ano = date_format($data, 'd/m/Y');

?>

<!-- Datatables -->
<link href="assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
<!-- / Datatables -->

<h2>Visão das Pausas</h2>
<table class="datatable-buttons table table-striped table-bordered table-hover" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Motivo</th>
            <th>Hoje - <?php echo $data_Ano; ?></th>
            <th>Limite</th>
            <th>Duração</th>
            <!-- <th>Overflow</th> -->
        </tr>
    </thead>

    <tbody>
        <?php

        foreach ($itens as $e) {
            $i = 0;
            if (isset($e['time_start']) and $i == 0 and isset($e['time_start'])) {

                $data_start = $e['time_start'];
                $Data_start = substr($data_start, 0, 10); // Retira somente a Data da string

                $Time_start = substr($data_start, 11, 8); // Retira somente a Hora da string


                $data_end = $e['time_end'];
                $Time_end = substr($data_end, 11, 8); // Retira somente a Hora da string

                $i++;
            }

            if ($e['agent'] != null) {

                $agent_id =  $e['agent']['id'];
                $agent_login = $e['agent']['login'];
                $agent_name = $e['agent']['name'];
                $agent_description = $e['description'];
                $agent_time_start = $e['time_start'];
                $agent_time_end = $e['time_end'];
                $agent_limit = $e['limit'];
                $agent_duration = $e['duration'];
                $agent_overflow = $e['overflow'];

                $agent_limit = round($agent_limit / 60);  // Apresenta os minutos
                $agent_duration = round($agent_duration / 60);  // Apresenta os minutos
                $agent_overflow = round($agent_overflow / 60);  // Apresenta os minutos
            }
            echo '<tr>
                    <td>' . $agent_id . '</td>
                    <td>' . $agent_name . '</td>
                    <td>' . $agent_description . '</td>
                    <td>' . $Time_start . ' - ' . $Time_end . '</td>
                    <td>' . $agent_limit . 'min.' . '</td>
                    <td>' . $agent_duration . 'min.' .  '</td>
                </tr>';
            // < td > ' . $agent_overflow . ' min . ' .  ' < / td >
        }
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
                    "order": [2, 'desc']
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