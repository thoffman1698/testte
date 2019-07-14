<?php

    if (isset($_POST['CodiUsuarioA']) and isset($_POST['MesA']) and isset($_POST['DiaA']) and isset($_POST['HorarioA'])){
        $CodiUsuario = $_POST['CodiUsuarioA'];
        $Dia = $_POST['DiaA'];
        $Mes = $_POST['MesA'];
        $Horario = $_POST['HorarioA'];

        $data = new DateTime('NOW');
        $Data = date_format($data, 'Y-m-d');
        $Ano = date_format($data, 'Y');
        
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

        $Data = $Ano . '-' . $Mes . '-' . $Dia;

        // Conectando ao Banco
        require_once 'assets/includes/conexao.php';

        $sql = "SELECT * FROM tbl_escala WHERE CodiUsuario='$CodiUsuario' AND DataEscalaUsuario='$Data' AND HorarioEscalaUsuario='$Horario'";
        $query = $ligacao->query($sql);

        if($query->num_rows==0 ){
            $sql = "INSERT INTO tbl_escala (CodiUsuario, DataEscalaUsuario, HorarioEscalaUsuario) VALUES ('$CodiUsuario', '$Data', '$Horario')";
            $ligacao->query($sql);
        }
    
        mysqli_close($ligacao);

        header('location: ./index_sistema.php');
        exit;
    }

?>