<?php
	session_start();
	
	unset(
		$_SESSION['auth'],
		$_SESSION['usuarioId'],
		$_SESSION['usuarioNome'],
		$_SESSION['usuarioSenha'],
		$_SESSION['usuarioNivelAcessoId']
	);
	
	$_SESSION['logindeslogado'] = "Você saiu do sistema com sucesso!";
	//redirecionar o usuario para a página de login
	header('location: ./');
?>