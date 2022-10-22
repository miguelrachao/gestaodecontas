<?php
//script de segurança
	$url=substr($_SERVER['REQUEST_URI'],0,6);
	if($url!="/index"){
		Header('Location: index.php');
		exit();
	}
?>
<div style="margin-top: 100px; margin-bottom: 100px;">
	<h1 class="text-2xl font-semibold">Pedimos desculpa.<br> A página que procura não foi encontrada.</h1>
</div>