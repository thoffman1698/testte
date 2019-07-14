<h2>Escala</h2>

<?php

    if (isset($_GET['MesC'])){
        $CodiUsuario = trim(addslashes($_GET['CodiUsuarioC']));
        $Mes = trim(addslashes($_GET['MesC']));

        $data = new DateTime('NOW');
        $Data = date_format($data, 'Y-m-d');
        
        //Coletando somente o Mês
        $mes_Atual = date_format($data, 'm');
        $mes_Seguinte = $mes_Atual + 1;

        if ($mes_Seguinte < 10){
            $mes_Seguinte = 0 . $mes_Seguinte;
        }

        if ($mes_Seguinte == 13){
            $mes_Seguinte = 0 . 1;
        }

        if ($Mes == 0){
            $Mes = $mes_Atual;
        }

        if ($Mes == 1){
            $Mes = $mes_Seguinte;
        }

        // Conectando ao Banco
        include 'assets/includes/conexao.php';

        $sql_ = "SELECT CodiUsuario, Usuario FROM tbl_usuarios WHERE CodiNivelAcesso>'6' ORDER BY Usuario";
        $query_ = $ligacao->query($sql_);

        if ( $CodiUsuario == '' ){
            $sql = "SELECT U.CodiUsuario, U.Usuario, C.Campanha, N.NivelAcesso, E.DataEscalaUsuario, E.HorarioEscalaUsuario
            FROM tbl_escala E, tbl_usuarios U, tbl_campanha C, tbl_nivelacesso N 
            WHERE 
            (E.DataEscalaUsuario LIKE '%-$Mes-%') AND
            (U.CodiUsuario=E.CodiUsuario) AND 
            (U.CodiCampanha=C.CodiCampanha) AND
            (U.CodiNivelAcesso=N.CodiNivelAcesso)";
            $query = $ligacao->query($sql);

        }
        else{
            $sql = "SELECT U.CodiUsuario, U.Usuario, C.Campanha, N.NivelAcesso, E.DataEscalaUsuario, E.HorarioEscalaUsuario
            FROM tbl_escala E, tbl_usuarios U, tbl_campanha C, tbl_nivelacesso N 
            WHERE (U.CodiUsuario='$CodiUsuario') AND 
            (E.DataEscalaUsuario LIKE '%-$Mes-%') AND
            (U.CodiUsuario=E.CodiUsuario) AND 
            (U.CodiCampanha=C.CodiCampanha) AND
            (U.CodiNivelAcesso=N.CodiNivelAcesso)";
            $query = $ligacao->query($sql);
        }

        mysqli_close($ligacao);

        if($sql_ = mysqli_fetch_array($query) and $CodiUsuario == $sql_["CodiUsuario"] and $Data == $sql_["DataEscalaUsuario"]){
            $DataEscalaUsuario = $sql_["DataEscalaUsuario"]; 
            $DataEscalaUsuario = new DateTime($DataEscalaUsuario);
            $DataEscalaUsuario = date_format($DataEscalaUsuario, 'd-m-Y');   
        }

?>

        <table class="datatable-buttons table table-striped table-bordered table-hover">
            <thead>

                <?php

                printf('<tr>');

                printf('<th> Nome </th>');
                printf('<th> Campanha </th>');
                printf('<th> Data </th>');
                printf('<th> Horário </th>');
                printf('<th> Alterar </th>');

                printf('</tr>');

                ?>
            </thead>
            <tbody>
                <?php

                while($row = mysqli_fetch_array($query)){

                    printf('<tr>');
                    printf('<td>' . $row[1] . '</td>');
                    printf('<td>' . $row[2] . '</td>');
                    printf('<td>' . $row[4] . '</td>');
                    printf('<td>' . $row[5] . '</td>');
                    printf('<td id="td_botao"><a href="#"> Alterar </a></td>');
                    printf('</tr>');
                }

                ?>
            </tbody>
        </table>
<?php
    }
?>