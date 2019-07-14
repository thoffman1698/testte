<?php

if (isset($_GET['Data_Operador'])){

    $Data_Operador = trim(addslashes($_GET['Data_Operador']));

    $Data_Operador_1 = substr($Data_Operador, 0, 10);

    $Data_Operador_1 = new DateTime($Data_Operador_1);
    $Data_Operador_1 = date_format($Data_Operador_1, 'Y-d-m');

    $Data_Operador_2 = substr($Data_Operador, -10);

    $Data_Operador_2 = new DateTime($Data_Operador_2);
    $Data_Operador_2 = date_format($Data_Operador_2, 'Y-d-m');
?>

    <div class="row">
        <div class="col-md-12">
            <h2>Relatório Por Operador</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="padding-3">
            <form action="index_sistema.php" method="GET">
                <div class="col-md-4 col-md-offset-3 form-group" style="display: inline-table;">
                    <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                    <input type="text" name="Data_Operador" id="reservation" class="form-control" data-inputmask="'mask': '99/99/9999 - 99/99/9999'" placeholder="01/01/2019 - 01/01/2019" value="<?php echo $Data_Operador; ?>" />
                </div>
                <div class="col-md-4 form-group">
                    <button type="submit" class="btn btn-danger">Pesquisar</button>
                </div>
            </form>
        </div>  
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="datatable-buttons table table-striped table-bordered table-hover">
                <thead>

                    <?php

                    printf('<tr>');

                    printf('<th> Nome </th>');
                    printf('<th> Escalado </th>');
                    printf('<th> Absenteísmo </th>');
                    print('<th> % </th>');

                    printf('</tr>');

                    ?>
                </thead>
                <tbody>
                    <?php

                    // Conecta ao Banco
                    include 'assets/includes/conexao.php'; 

                    $sql = "SELECT U.CodiUsuario, U.Usuario, US.Usuario, USU.Usuario, Campanha, NivelAcesso, DataAusencia 
                    FROM dimensiona_novo.tbl_ausencia A, dimensiona_novo.tbl_usuarios U,
                    dimensiona_novo.tbl_campanha C, dimensiona_novo.tbl_nivelacesso N,
                    dimensiona_novo.tbl_supervisor S, dimensiona_novo.tbl_usuarios US,
                    dimensiona_novo.tbl_coordenador CO, dimensiona_novo.tbl_usuarios USU
                    WHERE U.CodiUsuario=A.CodiUsuario AND 
                    A.DataAusencia BETWEEN '$Data_Operador_1' AND '$Data_Operador_2' AND
                    U.CodiCampanha=C.CodiCampanha AND U.CodiNivelAcesso=N.CodiNivelAcesso AND
                    (U.CodiSupervisor=S.CodiSupervisor) AND (S.CodiUsuario=US.CodiUsuario) AND 
                    (U.CodiCoordenador=CO.CodiCoordenador) AND (USU.CodiUsuario=CO.CodiUsuario)";
                    $query_faltantes = $ligacao->query($sql);

                    $CodiEscalado = '';
                    $CodiOperador = '';
                    $i = 0;
                    while ($row_faltantes = mysqli_fetch_array($query_faltantes)){

                        $CodiEscalado = $row_faltantes[0];

                        $sql = "SELECT U.CodiUsuario, U.Usuario, US.Usuario, USU.Usuario, 
                        Campanha, NivelAcesso, DataEscalaUsuario
                        FROM dimensiona_novo.tbl_escala E, dimensiona_novo.tbl_usuarios U,
                        dimensiona_novo.tbl_campanha C, dimensiona_novo.tbl_nivelacesso N,
                        dimensiona_novo.tbl_supervisor S, dimensiona_novo.tbl_coordenador CO,
                        dimensiona_novo.tbl_usuarios US, dimensiona_novo.tbl_usuarios USU
                        WHERE (U.CodiUsuario=E.CodiUsuario) AND (U.CodiUsuario='$CodiEscalado') AND 
                        (E.DataEscalaUsuario BETWEEN '$Data_Operador_1' AND '$Data_Operador_2') AND
                        (U.CodiCampanha=C.CodiCampanha) AND (U.CodiNivelAcesso=N.CodiNivelAcesso) AND
                        (U.CodiSupervisor=S.CodiSupervisor) AND (U.CodiCoordenador=CO.CodiCoordenador) AND
                        (S.CodiUsuario=US.CodiUsuario) AND (CO.CodiUsuario=USU.CodiUsuario);";
                        $query_escalado = $ligacao->query($sql);
                        
                        $sql = "SELECT U.CodiUsuario, U.Usuario, US.Usuario, USU.Usuario, 
                        Campanha, NivelAcesso, DataEscalaUsuario 
                        FROM dimensiona_novo.tbl_ausencia A, dimensiona_novo.tbl_escala E, 
                        dimensiona_novo.tbl_usuarios U, 
                        dimensiona_novo.tbl_campanha C, dimensiona_novo.tbl_nivelacesso N,
                        dimensiona_novo.tbl_supervisor S, dimensiona_novo.tbl_coordenador CO,
                        dimensiona_novo.tbl_usuarios US, dimensiona_novo.tbl_usuarios USU
                        WHERE A.CodiUsuario=E.CodiUsuario AND A.DataAusencia=E.DataEscalaUsuario AND
                        (A.DataAusencia BETWEEN '$Data_Operador_1' AND '$Data_Operador_2') AND
                        U.CodiUsuario=A.CodiUsuario AND U.CodiUsuario=E.CodiUsuario AND
                        (U.CodiUsuario='$CodiEscalado') AND U.CodiCampanha=C.CodiCampanha AND
                        (U.CodiNivelAcesso=N.CodiNivelAcesso) AND
                        (U.CodiSupervisor=S.CodiSupervisor) AND (U.CodiCoordenador=CO.CodiCoordenador) AND
                        (S.CodiUsuario=US.CodiUsuario) AND (CO.CodiUsuario=USU.CodiUsuario);";
                        $query_abs = $ligacao->query($sql);
                
                        $Absenteismo_Porcentagem = 0;
                        
                        if ($query_abs->num_rows!=0){
                            $Absenteismo_Porcentagem = ($query_abs->num_rows * 100) / $query_escalado->num_rows;
                            $Absenteismo_Porcentagem = number_format($Absenteismo_Porcentagem, 2, ',', '.');
                        }

                        if (isset($CodiOperador) and $CodiOperador != $row_faltantes[0]){
                            printf('<tr>');
                            printf('<td id="td_botao" data-toggle="modal" data-target=".modal_' . $i . '"><a href="#">' . $row_faltantes[1] . '</a></td>');
                            printf('<td id="td_botao" data-toggle="modal" data-target=".escala_modal_' . $i . '"><a href="#">' . $query_escalado->num_rows . '</a></td>');
                            printf('<td id="td_botao" data-toggle="modal" data-target=".abs_modal_' . $i . '"><a href="#">' . $query_abs->num_rows . '</a></td>');
                            print('<td>' . $Absenteismo_Porcentagem . '% </td>');
                            printf('</tr>');
                            ?>
    
                            <div class="modal fade modal_<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h3 class="modal-title" id="gridSystemModalLabel">Detalhes do Operador</h3>
                                        </div>
                    
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <?php 
                                                        echo '<h4>Nome: ' . $row_faltantes[1] . '</h4><br />';
                                                        echo '<h4>Supervisor: ' . $row_faltantes[2] . '</h4><br />';
                                                        echo '<h4>Coordenador: ' . $row_faltantes[3] . '</h4><br />';
                                                        echo '<h4>Campanha: ' . $row_faltantes[4] . '</h4>';
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-success" data-dismiss="modal">Fechar</button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
    
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
                            
                        }

                        $CodiOperador = $row_faltantes[0];
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