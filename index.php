<?php
	include('build/php/conn.php');
	session_start();
	if((!isset($_COOKIE['session_logged_gdc'])) && (!isset($_SESSION['on_gdc']))) {
		if(isset($_GET['p'])){
			if($_GET['p']=="1"){ //pagina para criar conta
			  include('registo_conta.php');
			}elseif($_GET['p']=="2"){ //pagina para criar conta
				include('recuperar.php');
			}else{
				include('404_index.php');
			}
		}else{
			include('login.php');
		}
	}else{
?>
<html lang="pt">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Gestão de contas</title>
    <link rel="icon" type="image/ico" href="build/images/favicon.ico">
    <link
      href="build/css/css2.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="build/css/tailwind.css" />
	<link rel="stylesheet" href="build/css/alert.css" />
	<link rel="stylesheet" href="build/css/custom.css" />
	<script src="build/js/jquery.js" defer></script>
    <script src="build/js/component.min.js"></script>
    <script src="build/js/alpine.min.js" defer></script>
  </head>
  <body>
    <div x-data="setup()" x-init="$refs.loading.classList.add('hidden'); setColors(color);" :class="{ 'dark': isDark}">
      <div class="flex h-screen antialiased text-gray-900 bg-gray-100 dark:bg-dark dark:text-light">
        <!-- Loading screen -->
        <div
          x-ref="loading"
          class="fixed inset-0 z-50 flex items-center justify-center text-2xl font-semibold text-white bg-primary-darker"
        >
          <img src="build/images/loading2.gif">
        </div>
        <!-- MENU COMUM DESKTOP-->
		<?php
			include('menus/menu_comum_desktop.php');
		?>
        <div class="flex-1 h-full overflow-x-hidden overflow-y-auto">
          <!-- Navbar -->
          <header class="relative bg-white dark:bg-darker">
            <!-- HEADER -->
			<?php
				include('header/header.php');
			?>
            <!-- MENU COMUM MOBILE -->
			<?php
				include('menus/menu_comum_mobile.php');
			?>
          </header>
		  <!-- PAINEL CENTRAL | PÁGINAS DINAMICAS-->
		  <main style="margin-top:10px;">
			<center>
			  <?php
				if(isset($_GET['p'])){
				  if($_GET['p']=="1"){ //pagina para adicionar registo
					include('novoregisto.php');
				  }elseif($_GET['p']=="2"){ //pagina para adicionar perfil
					include('novoperfil.php');
				  }elseif($_GET['p']=="3"){ //pagina para adicionar categoria
					include('novacategoria.php');
				  }elseif($_GET['p']=="4"){ //pagina para ver perfis
					include('verperfis.php');
				  }elseif($_GET['p']=="5"){ //pagina para partilhar perfil
					include('partilharperfil.php');
				  }elseif($_GET['p']=="6"){ //pagina para ver categorias
					include('vercategorias.php');
				  }elseif($_GET['p']=="7"){ //pagina para ver registos
					include('verregistos.php');
				  }elseif($_GET['p']=="8"){ //pagina para ver registos
					include('minhaconta.php');
				  }elseif($_GET['p']=="9"){ //pagina para ver registos
					include('editarregisto.php');
				  }else{
					  include('404.php');
				  }
				}
				else{
				  include('inicio.php');
				}
			  ?>
			</center>
		  </main>
        </div>
		<?php
			include('configuracoes_extra/configuracoes_extra.php');
		?>
  </body>
</html>
<?php
}
?>