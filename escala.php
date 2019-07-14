<?php

    // Conectando ao Banco
    require_once 'assets/includes/conexao.php';

    $sql_ = "SELECT CodiUsuario, Usuario FROM tbl_usuarios WHERE CodiNivelAcesso>'6' ORDER BY Usuario";
    $query_ = $ligacao->query($sql_);
    $query_2 = $ligacao->query($sql_);

    mysqli_close($ligacao);
    
?>

<h2>Escala</h2>

<div class="col-md-12">
    <h3>Consulte:</h3>
    <div class="row" id="consulta_Escala">
        <form action="./index_sistema.php" method="GET">
            <div class="col-md-6 form-group">
                <?php

                echo "<select class='select2_Usuarios form-control' name='CodiUsuarioC'>";
                echo "<option value=''></option>";

                while($row = mysqli_fetch_array($query_)){
                    $CodiUsuario = $row["CodiUsuario"]; 
                    $Usuario = $row["Usuario"];
                                    
                    echo "<option value='$CodiUsuario'";
                    if(isset($CodiUsuarioC) and isset($Data) and $CodiUsuarioC==$CodiUsuario){
                        echo "selected";
                    }                              
                    echo ">$Usuario</option>";
                }

                echo "</select>";

                ?>
            </div>
                
            <div class="col-md-3 form-group">
                <select class='select2_Mes form-control' name='MesC'>";
                    <option value='0'>Mês Atual</option>";
                    <option value='1'>Mês Seguinte</option>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <button type="submit" class="btn btn-login no-margin">Pesquisar</button>
            </div>
        </form>
    </div>
    <!-- <div class="row">
        <div class="col-md-6" align="right">
            <button type="button" onclick="displayConsultarEscala();" class="btn btn-login">Consultar</button>
        </div>
        <div class="col-md-6">
            <button type="button" onclick="displayAlterarEscala();" class="btn btn-login">Alterar</button>
        </div>
    </div> -->
    
    <h3>Insira:</h3>
    <div class="row">
        <form action="./insere_escala.php" method="POST">
            <div class="col-md-4 form-group" style="padding-right:0">
                <?php

                echo "<select class='select2_Usuarios form-control' name='CodiUsuarioA'>";
                echo "<option value=''></option>";

                while($row = mysqli_fetch_array($query_2)){
                    $CodiUsuario = $row["CodiUsuario"]; 
                    $Usuario = $row["Usuario"];
                                    
                    echo "<option value='$CodiUsuario'>$Usuario</option>";
                }

                echo "</select>";

                ?>
            </div>
                
            <div class="col-md-2 form-group">
                <select class='select2_Mes form-control' name='MesA'>";
                    <option value='0'>Mês Atual</option>";
                    <option value='1'>Mês Seguinte</option>
                </select>
            </div>
                
            <div class="col-md-1 form-group no-padding">
                <select class='select2_Dia form-control' name='DiaA'>";
                    <option value=''></option>";
                    <?php 
                    for ($i=1; $i<32; $i++){
                        if ($i<10){
                            $i = 0 . $i;
                        }
                        echo "<option value='" . $i . "'>" . $i . "</option>";
                    } ?>
                </select>
            </div>
                
            <div class="col-md-2 form-group">
                <select class='select2_Horarios form-control' name='HorarioA'>";
                    <option value=''></option>";
                    <option value='05:00 - 11:00'>05:00 - 11:00</option>
                    <option value='07:00 - 13:00'>07:00 - 13:00</option>
                    <option value='08:00 - 13:00'>08:00 - 13:00</option>
                    <option value='08:00 - 14:00'>08:00 - 14:00</option>
                    <option value='08:20 - 16:32'>08:20 - 16:32</option>
                    <option value='09:00 - 15:00'>09:00 - 15:00</option>
                    <option value='11:00 - 17:00'>11:00 - 17:00</option>
                    <option value='14:00 - 20:00'>14:00 - 20:00</option>
                    <option value='17:00 - 23:00'>17:00 - 23:00</option>
                    <option value='23:00 - 05:00'>23:00 - 05:00</option>
                    <option value='F'>FOLGA</option>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <button type="submit" class="btn btn-login no-margin"> Adicionar&nbsp;</button>
            </div>
        </form>
    </div>
</div>

<!-- Select2 -->
<script src="assets/vendors/select2/dist/js/select2.full.min.js"></script>

<script>
    $(document).ready(function() {
        $(".select2_Usuarios").select2({
            placeholder: "Selecione o Usuario",
            allowClear: true
        });
        $(".select2_Mes").select2({
            placeholder: "Mês",
            allowClear: true
        });
        $(".select2_Dia").select2({
            placeholder: "Dia",
            allowClear: true
        });
        $(".select2_Horarios").select2({
            placeholder: "Horário",
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