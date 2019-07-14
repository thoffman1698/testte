<?php

require_once 'assets/includes/conexao.php';

$CodiUsuario = '32';

for ($i=1; $i<32; $i++){
    if ( ($i > 15) and ($i != 6) and ($i != 7) and ($i != 13) and ($i != 14) and ($i != 20) and ($i != 21) and ($i != 27) and ($i != 28) ){
    // if ( ($i != 6) and ($i != 7) and ($i != 14) and ($i != 20) and ($i != 21) and ($i != 28) ){
        
        // if ( $i != 13 and $i != 27 ){
        if ( $i != 6 and $i != 20 ){
            $sql = "INSERT INTO tbl_escala (CodiUsuario, DataEscalaUsuario, HorarioEscalaUsuario) VALUES ('$CodiUsuario', '2019-07-$i', '08:00 - 16:12')";
            // $ligacao->query($sql);
        }
        else {
            $sql = "INSERT INTO tbl_escala (CodiUsuario, DataEscalaUsuario, HorarioEscalaUsuario) VALUES ('$CodiUsuario', '2019-07-$i', '08:00 - 16:42')";
            // $ligacao->query($sql);
        }
    }
    else{
        $sql = "INSERT INTO tbl_escala (CodiUsuario, DataEscalaUsuario, HorarioEscalaUsuario) VALUES ('$CodiUsuario', '2019-07-$i', 'F')";
        // $ligacao->query($sql);
    }
}

?>