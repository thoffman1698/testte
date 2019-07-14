<?php

require_once 'assets/includes/header.php';

// Conectando ao Banco
require_once 'assets/includes/conexao.php';

$sql = "SELECT * FROM tbl_teste";
$query_eventos = $ligacao->query($sql);

mysqli_close($ligacao); 

?>

<!-- FullCalendar -->
<!-- <link href="assets/vendors/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
<link href="assets/vendors/fullcalendar/dist/fullcalendar.print.css" rel="stylesheet" media="print"> -->
<link href='assets/vendors/fullcalendar-4.2/packages/core/main.css' rel='stylesheet' />
<link href='assets/vendors/fullcalendar-4.2/packages/daygrid/main.css' rel='stylesheet' />
<link href='assets/vendors/fullcalendar-4.2/packages/timegrid/main.css' rel='stylesheet' />

<section>
    <div class="container-fluid">
        <div class="row">
            <!-- Menu -->
            <nav class="col-md-3" id="menu-dimensiona">
                <?php require_once 'assets/includes/menu_esquerdo.php'; ?>
            </nav>

            <!-- Conteúdo em JS no Footer-->
            <div class="col-md-9 col-md-offset-3" id="tabelas">

                <div id='calendar'></div>

                <div id="fc_create" data-toggle="modal" data-target="#CalenderModalNew"></div>
                <div id="fc_edit" data-toggle="modal" data-target="#CalenderModalEdit"></div>
                <!-- /calendar modal -->

            </div>
        </div>
    </div>
</section>

<?php

require_once 'assets/includes/footer.php';

?>

<!-- FullCalendar -->
<script src="assets/vendors/moment/min/moment.min.js"></script>
<script src="assets/vendors/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="assets/vendors/fullcalendar/dist/lang/pt-br.js"></script>
<script src='assets/vendors/fullcalendar-4.2/packages/core/main.js'></script>
<script src='assets/vendors/fullcalendar-4.2/packages/interaction/main.js'></script>
<script src='assets/vendors/fullcalendar-4.2/packages/daygrid/main.js'></script>
<script src='assets/vendors/fullcalendar-4.2/packages/timegrid/main.js'></script>
<script src='assets/vendors/fullcalendar-4.2/packages/core/locales/pt-br.js'></script>

<!-- FullCalendar -->
<!-- <script>
    $(window).load(function() {
        var date = new Date(),
            d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear(),
            started,
            categoryClass;

        var calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                $('#fc_create').click();

                started = start;
                ended = end;

                $(".antosubmit").on("click", function() {
                    var title = $("#title").val();
                    if (end) {
                        ended = end;
                    }

                    categoryClass = $("#event_type").val();

                    if (title) {
                        calendar.fullCalendar('renderEvent', {
                                title: title,
                                start: started,
                                end: end,
                                allDay: allDay
                            },
                            true // make the event "stick"
                        );
                    }

                    $('#title').val('');

                    calendar.fullCalendar('unselect');

                    $('.antoclose').click();

                    return false;
                });
            },
            eventClick: function(calEvent, jsEvent, view) {
                $('#fc_edit').click();
                $('#title2').val(calEvent.title);

                categoryClass = $("#event_type").val();

                $(".antosubmit2").on("click", function() {
                    calEvent.title = $("#title2").val();

                    calendar.fullCalendar('updateEvent', calEvent);
                    $('.antoclose2').click();
                });

                calendar.fullCalendar('unselect');
            },
            editable: true,
            
            events: [
                <?php while ($row_eventos = mysqli_fetch_assoc($query_eventos)){ ?>
                    {
                        id: '<?php echo $row_eventos['CodiTeste']; ?>',
                        Titulo: '<?php echo $row_eventos['Titulo']; ?>',
                        Comeco: '<?php echo $row_eventos['Comeco']; ?>',
                        Final: '<?php echo $row_eventos['Final']; ?>',
                        Cor: '<?php echo $row_eventos['Cor']; ?>',
                    },
                <?php } ?>    
            ],
            eventColor: '#378006',
        });
    });
</script> -->

<script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'timeGridList' ],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        navLinks: true, // can click day/week names to navigate views
        selectable: true,
        selectMirror: true,

        select: function(arg) {
            var title = prompt('Event Title:');
            if (title) {
                calendar.addEvent({
                    title: title,
                    start: arg.start,
                    end: arg.end,
                    allDay: arg.allDay
                })
            }
            calendar.unselect()
        },
        eventLimit: true, // allow "more" link when too many events
        events: [
            <?php while ($row_eventos = mysqli_fetch_assoc($query_eventos)){ ?>
                {
                    id: '<?php echo $row_eventos['CodiTeste']; ?>',
                    Titulo: '<?php echo $row_eventos['Titulo']; ?>',
                    Comeco: '<?php echo $row_eventos['Comeco']; ?>',
                    Final: '<?php echo $row_eventos['Final']; ?>',
                    Cor: '<?php echo $row_eventos['Cor']; ?>',
                },
            <?php } ?> 
        ],
        editable: true,
    });

    calendar.render();
  });

</script>

<!-- /FullCalendar -->