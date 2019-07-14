<?php

if (isset($_GET['Data_Campanha'])){

    $Data_Campanha = trim(addslashes($_GET['Data_Campanha']));

    $Data_Campanha_1 = substr($Data_Campanha, 0, 10);
    
    $Data_Campanha_1 = new DateTime($Data_Campanha_1);
    $Data_Campanha_1 = date_format($Data_Campanha_1, 'Y-d-m');

    $Data_Campanha_2 = substr($Data_Campanha, -10);

    $Data_Campanha_2 = new DateTime($Data_Campanha_2);
    $Data_Campanha_2 = date_format($Data_Campanha_2, 'Y-d-m');

?>

    <div class="row">
        <div class="col-md-12">
            <h2>Relatório Por Campanha</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="padding-3">

            <form action="index_sistema.php" method="GET">
                <!-- <div class="col-md-4 col-md-offset-1">
                    <?php

                    echo "<select class='select2_Campanha form-control' name='CodiCampanha'>";
                    echo "<option value=''></option>";

                    while($row = mysqli_fetch_array($query)){
                        $CodiCampanhaC = $row["CodiCampanha"]; 
                        $Campanha = $row["Campanha"];
                                      
                        if (isset($CodiCampanhaC) and $CodiCampanhaC==$CodiCampanha){
                            $CampanhaC = $Campanha;
                            echo "<option value='$CodiCampanha' selected>$Campanha</option>";    
                        }
                        else{
                            echo "<option value='$CodiCampanha'>$Campanha</option>";
                        }
                    }

                    echo "</select>";

                    ?>
                </div> -->
                <div class="col-md-4 col-md-offset-3 form-group" style="display: inline-table;">
                    <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                    <input type="text" name="Data_Campanha" id="reservation" class="form-control" data-inputmask="'mask': '99/99/9999 - 99/99/9999'" value="<?php echo $Data_Campanha; ?>" />
                </div>
                <div class="col-md-3 form-group">
                    <button type="submit" class="btn btn-danger">Pesquisar</button>
                </div>
            </form>
        </div>  
    </div>
    <div class="row">
        <div class="col-md-12">
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
                    include 'assets/includes/conexao.php';

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
                        (E.DataEscalaUsuario BETWEEN '$Data_Campanha_1' AND '$Data_Campanha_2') AND
                        (E.HorarioEscalaUsuario<>'F') AND
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
                        (A.DataAusencia BETWEEN '$Data_Campanha_1' AND '$Data_Campanha_2') AND
                        (E.HorarioEscalaUsuario<>'F') AND
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
            
                                                    if ($Codi_Faltante != $row_faltantes[0]){
                                                        echo '<div class="col-sm-12">';
                                                        echo '<h4 style="display:inline-block;">Nome: ' . $row_faltantes[1] . ' // ' . 'Campanha: ' . $row_faltantes[4] . '</h4>';
                                                        echo '</div>';
                                                        echo '<div class="col-sm-3">';
                                                        echo '<h4 style="display:inline-block;">Data: ' . $row_faltantes[6] . ' // </h4>';
                                                        echo '</div>';
            
                                                    }
                                                    else {
                                                        echo '<div class="col-sm-3">';
                                                        echo '<h4 style="display:inline-block;">Data: ' . $row_faltantes[6] . '</h4>';
                                                        echo '</div>';
                                                    }
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
                                                        
                                                        if ($Codi_Faltante != $row_abs[0]){
                                                            echo '<div class="col-sm-12">';
                                                            echo '<h4 style="display:inline-block;">Nome: ' . $row_abs[1] . ' // ' . 'Campanha: ' . $row_abs[4] . '</h4>';
                                                            echo '</div>';
                                                            echo '<div class="col-sm-3">';
                                                            echo '<h4 style="display:inline-block;">Data: ' . $row_abs[6] . '</h4>';
                                                            echo '</div>';
            
                                                        }
                                                        else {
                                                            echo '<div class="col-sm-3">';
                                                            echo '<h4 style="display:inline-block;">Data: ' . $row_abs[6] . '</h4>';
                                                            echo '</div>';
                                                        }
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
        </div>  
    </div>

<?php 
}
?>