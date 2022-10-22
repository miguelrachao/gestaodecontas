<?php
if(isset($_POST['email'])){
	include('conn.php');
	session_start();
	//recebe dados
	$email=$_POST['email'];
	$email_envia=$email;
	$email=base64_encode($email);
	$query_verifica=mysqli_query($conn, "SELECT * FROM utilizadores WHERE email='$email'");
	$rows_verifica=mysqli_num_rows($query_verifica);
	if($rows_verifica==0){
		echo '2';
	}else{
		$password=rand(1111,9999);
		$query_update=mysqli_query($conn, "UPDATE utilizadores SET password=md5('$password') WHERE email='$email'");
		//Enviar Email
		$recetor=$email_envia;
		$emissor = 'email emissor';
		$nome_emissor = 'Gestão de contas';
		$titulo_email = 'Recuperação de password';
		$mensagem_email = 'A sua nova password para login: '.$password.'<br>Assim que possivel, troque a password!<br><b>Gestão de Contas</b>';
		require_once('phpmailer/class.phpmailer.php');
		function smtpmailer($para, $de, $de_nome, $assunto, $corpo) { 
			global $error;
			$mail = new PHPMailer();
			$mail->CharSet = 'UTF-8';
			$mail->IsSMTP();		// Ativar SMTP
			$mail->IsHTML(true);    //Ativar HTML
			$mail->SMTPDebug = '1';		// Debugar: 1 = erros e mensagens, 2 = mensagens apenas
			$mail->SMTPAuth = true;		// Autenticação ativada
			$mail->SMTPSecure = 'ssl';	// SSL REQUERIDO pelo GMail
			$mail->Host = 'smtp server';	// SMTP utilizado
			$mail->Port = 'smtp port';  		// A porta 587 deverá estar aberta em seu servidor
			$mail->Username = $de;
			$mail->Password = 'mail password';
			$mail->SetFrom($de, $de_nome);
			$mail->Subject = $assunto;
			$mail->Body = $corpo;
			$mail->AddAddress($para);
			if(!$mail->Send()) {
				return false;
			} else {
				return true;
			}
		}
		smtpmailer($recetor, $emissor, $nome_emissor, $titulo_email, $mensagem_email);
		echo '1';
	}
}else{
	Header('Location: ..\..\index.php');
	exit();
}
	
?>