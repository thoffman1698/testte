<?php

	// Dia de Ontem Até amanhã de Hoje
	$DataZona = new DateTimeZone('America/Sao_Paulo');
	$data = new DateTime('NOW');
	$data->setTimezone($DataZona);
	$data_Hoje = date_format($data, 'Y-m-d');
	
	//Iniciando a sessão
	session_start();	
	
	//Incluindo a conexão com banco de dados
	require_once 'assets/includes/conexao.php';	
	
	//O campo usuário e Senha preenchido entra no if para validar
	if(isset($_POST[ 'Login']) && isset($_POST['Senha'])){
		$Login = mysqli_real_escape_string($ligacao, $_POST[ 'Login']); //Escapar de caracteres especiais, como aspas, prevenindo SQL injection
		$Senha = mysqli_real_escape_string($ligacao, $_POST['Senha']);
		
		//Buscar na tabela usuario o usuário que corresponde com os dados digitado no formulário
		$result_usuario = "SELECT * FROM tbl_usuarios WHERE (Login = '$Login') AND (Senha = '$Senha') LIMIT 1";
		$resultado_usuario = mysqli_query($ligacao, $result_usuario) or die(mysqli_error($ligacao));
		$resultado = mysqli_fetch_assoc($resultado_usuario);
		
		//Encontrado um usuario na tabela usuário com os mesmos dados digitado no formulário
		if($resultado == true){
			
			$_SESSION['auth'] = true;
    		$_SESSION['usuarioSenha'] = $resultado['Senha'];
    		$_SESSION['usuarioId'] = $resultado['CodiUsuario'];
    		$_SESSION['usuarioNome'] = $resultado['Usuario'];
    		$_SESSION['ultimoAcesso'] = $resultado['DataUltimoAcesso'];
    		$_SESSION['usuarioNivelAcessoId'] = $resultado['CodiNivelAcesso'];
			// if (isset($resultado['foto'])){
			// 	$_SESSION['foto'] = $resultado['foto'];
			// }
			
			// Auditoria do Sistema, cadastrar acessos no sistema  
			$ip = getenv("REMOTE_ADDR"); // obtém o número ip do usuário
		    $id = $_SESSION['usuarioId'];
		    $insere = "INSERT INTO tbl_auditoria(CodiUsuario, Ip, DataAcesso, HoraUltimoAcesso, Descricao) VALUES ('$id', '$ip', NOW( ), NOW( ), 'Login Efetuado')";
			mysqli_query($ligacao,$insere) or die(mysqli_error($ligacao));
			$insere = "UPDATE tbl_usuarios SET DataUltimoAcesso=NOW() WHERE CodiUsuario='$id'";
			mysqli_query($ligacao,$insere) or die(mysqli_error($ligacao));
			$deleta = "DELETE FROM tbl_ausencia WHERE CodiUsuario='$id' AND DataAusencia='$data_Hoje'";
			mysqli_query($ligacao,$deleta) or die(mysqli_error($ligacao));

			require_once 'insere_ausentes.php';

			header('location: ./index_sistema.php');
			exit;
			
		}else{
			//Váriavel global recebendo a mensagem de erro
			$_SESSION['loginErro'] = "Usuário ou senha Inválido";
			header('location: ./');
			exit;

			// auditoria
    		$ip = getenv("REMOTE_ADDR");
    		$insere = "INSERT INTO tbl_auditoria(Ip, DataUltimoAcesso, Descricao) VALUES ('$ip', NOW( ), 'Falha de Login')";
   			mysqli_query($ligacao,$insere) or die(mysqli_error($ligacao));
		}
	//O campo usuário e Senha não preenchido entra no else e redireciona o usuário para a página de Login
	}else{
		//Váriavel global recebendo a mensagem de erro
		$_SESSION['logindeslogado'] = "Usuário Deslogado";
		header('location: ./');
		exit;
	}
?>