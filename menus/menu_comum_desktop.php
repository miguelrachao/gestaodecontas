<!-- Sidebar -->
        <aside class="flex-shrink-0 hidden w-64 bg-white border-r dark:border-primary-darker dark:bg-darker md:block">
          <div class="flex flex-col h-full">
            <!-- Sidebar links -->
            <nav aria-label="Main" class="flex-1 px-2 py-4 space-y-2 overflow-y-hidden hover:overflow-y-auto">
              
              <!-- Estatisticas menu -->
              <div x-data="{ isActive: true, open: true}">
                <a
                  href="index.php"
                  class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                  :class="{<?php if(!isset($_GET['p'])){ echo "'bg-primary-100 dark:bg-primary': isActive ";}?> }"
                  style="background-image:url('build/images/logo.png'); background-repeat:no-repeat; background-size: 25px 25px; background-position: left 5px top 5px; padding-left:30px;"
                >
                  <span class="ml-2 text-sm"> Estatísticas </span>
                </a>
              </div>

				<!-- Registos Menu -->
              <div x-data="{ isActive: true, open: <?php if(isset($_GET['p'])){ if(($_GET['p']=="1") || ($_GET['p']=="7")){ echo "true";}else{ echo "false";} }else{echo"false";}?> }">
                <a
                  href="#"
                  @click="$event.preventDefault(); open = !open"
                  class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light dark:hover:bg-primary hover:bg-primary-100"
                  :class="{<?php if(isset($_GET['p'])){ if(($_GET['p']=="1") || ($_GET['p']=="7")){ echo "'bg-primary-100 dark:bg-primary': isActive";} }?>  }"
                  role="button"
                  aria-haspopup="true"
                  :aria-expanded="(open || isActive) ? 'true' : 'false'"
                  style="background-image:url('build/images/registo.png'); background-repeat:no-repeat; background-size: 25px 25px; background-position: left 5px top 5px; padding-left:30px;"
                >
                  <span class="ml-2 text-sm"> Registos </span>
                  <span aria-hidden="true" class="ml-auto">
                    <svg
                      class="w-4 h-4 transition-transform transform"
          
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </span>
                </a>
                <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="Components">
                  <a
                    href="index.php?p=1"
                    role="menuitem"
                    class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md  dark:hover:text-light hover:text-gray-700 <?php if($_GET['p']=="1"){ echo "dark:bg-primary dark:text-light bg-primary-100 text-gray-500";} ?>"
                  >
                    Novo Registo
                  </a>
                  <a
                    href="index.php?p=7"
                    role="menuitem"
                    class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700 <?php if($_GET['p']=="7"){ echo "dark:bg-primary dark:text-light bg-primary-100 text-gray-500";} ?>"
                  >
                    Ver Registos
                  </a>
                </div>
              </div>


              <!-- Perfis Menu -->
              <div x-data="{ isActive: true, open: <?php if(isset($_GET['p'])){ if(($_GET['p']=="2") || ($_GET['p']=="4")|| ($_GET['p']=="5")){ echo "true";}else{echo"false";} }else{echo"false";}?> }">
                <a
                  href="#"
                  @click="$event.preventDefault(); open = !open"
                  class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light dark:hover:bg-primary hover:bg-primary-100"
                  :class="{<?php if(isset($_GET['p'])){ if(($_GET['p']=="2") || ($_GET['p']=="4")|| ($_GET['p']=="5")){ echo "'bg-primary-100 dark:bg-primary': isActive";} }?>  }"
                  role="button"
                  aria-haspopup="true"
                  :aria-expanded="(open || isActive) ? 'true' : 'false'"
                  style="background-image:url('build/images/perfil.png'); background-repeat:no-repeat; background-size: 25px 25px; background-position: left 5px top 5px; padding-left:30px;"
                >
                  <span class="ml-2 text-sm"> Perfis de contas </span>
                  <span aria-hidden="true" class="ml-auto">
                    <svg
                      class="w-4 h-4 transition-transform transform"
          
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </span>
                </a>
                <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="Components">
                  <a
                    href="index.php?p=2"
                    role="menuitem"
                    class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700 <?php if($_GET['p']=="2"){ echo "dark:bg-primary dark:text-light bg-primary-100 text-gray-500";} ?>"
                  >
                    Novo Perfil
                  </a>
                  <a
                    href="index.php?p=4"
                    role="menuitem"
                    class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700 <?php if($_GET['p']=="4"){ echo "dark:bg-primary dark:text-light bg-primary-100 text-gray-500";} ?>"
                  >
                    Ver Perfis
                  </a>
				   <a
                    href="index.php?p=5"
                    role="menuitem"
                    class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700 <?php if($_GET['p']=="5"){ echo "dark:bg-primary dark:text-light bg-primary-100 text-gray-500";} ?>"
                  >
                    Partilhar Perfil
                  </a>
                </div>
              </div>

              <!-- Categorias Menu -->
              <div x-data="{ isActive: true, open: <?php if(isset($_GET['p'])){ if(($_GET['p']=="3") || ($_GET['p']=="6")){ echo "true";}else{echo"false";} }else{echo"false";}?> }">
                <a
                  href="#"
                  @click="$event.preventDefault(); open = !open"
                  class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light dark:hover:bg-primary hover:bg-primary-100"
                  :class="{<?php if(isset($_GET['p'])){ if(($_GET['p']=="3") || ($_GET['p']=="6")){ echo "'bg-primary-100 dark:bg-primary': isActive";} }?>  }"
                  role="button"
                  aria-haspopup="true"
                  :aria-expanded="(open || isActive) ? 'true' : 'false'"
                  style="background-image:url('build/images/categoria.png'); background-repeat:no-repeat; background-size: 25px 25px; background-position: left 5px top 5px; padding-left:30px;"
                >
                  <span class="ml-2 text-sm"> Categorias </span>
                  <span aria-hidden="true" class="ml-auto">
                    <svg
                      class="w-4 h-4 transition-transform transform"
          
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </span>
                </a>
                <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="Components">
                  <a
                    href="index.php?p=3"
                    role="menuitem"
                    class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700 <?php if($_GET['p']=="3"){ echo "dark:bg-primary dark:text-light bg-primary-100 text-gray-500";} ?>"
                  >
                    Nova Categoria
                  </a>
                  <a
                    href="index.php?p=6"
                    role="menuitem"
                    class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700 <?php if($_GET['p']=="6"){ echo "dark:bg-primary dark:text-light bg-primary-100 text-gray-500";} ?>"
                  >
                    Ver Categorias
                  </a>
                </div>
              </div>


          </div>
        </aside>