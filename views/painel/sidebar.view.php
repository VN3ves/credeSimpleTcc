<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
            <a href="./" class="nav-link <?php if (!isset($activePage[0])) {
                                                echo 'active';
                                            } ?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>

        <?php if (check_permissions('SUPERADMIN', $permissao)) { ?>
            <li class="nav-item"><a href="<?php echo HOME_URI; ?>/eventos" class="nav-link <?php if (isset($activePage[0]) && $activePage[0] == 'eventos' && !isset($activePage[2])) {
                                                                                                echo 'active';
                                                                                            } ?>"><i class="far fa-calendar nav-icon"></i>
                    <p>Eventos</p>
                </a></li>
        <?php } ?>

        <?php
        $modeloEvento = $this->load_model('eventos/eventos');
        $idEventoHash = isset($_SESSION['idEventoHash']) ? $_SESSION['idEventoHash'] : null;
        if ($idEventoHash !== null && $idEventoHash != 0) {
            $idEvento = decryptHash($idEventoHash);
            $eventoTemDB = $modeloEvento->checkEventoDB($idEvento);
        }

        if ($idEvento !== null && $idEvento != 0 && $eventoTemDB === true) {
            $evento = $modeloEvento->getEvento($idEvento);
        ?>

            <li class="nav-item">
                <a href="#" class="nav-link bg-danger text-white font-weight-bold rounded" style="cursor: default;">
                    <i class="fas fa-calendar-alt nav-icon"></i>
                    <p><?php echo htmlentities(chk_array($evento, 'nomeEvento')); ?></p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo HOME_URI; ?>/credenciamento" class="nav-link <?php
                                                                                    if (isset($activePage[0]) && $activePage[0] == 'credenciamento') {
                                                                                        echo 'active bg-success text-white font-weight-bold rounded';
                                                                                    }
                                                                                    ?>">
                    <i class="fas fa-calendar-check nav-icon"></i>
                    <p>Credenciamento</p>
                </a>
            </li>
            <?php if (check_permissions('ADMIN', $permissao)) { ?>

                <li class="nav-item">
                    <a href="<?php echo HOME_URI; ?>/entradas" class="nav-link <?php
                                                                                if (isset($activePage[0]) && $activePage[0] == 'entradas') {
                                                                                    echo 'active';
                                                                                }
                                                                                ?>">
                        <i class="fas fa-door-open nav-icon"></i>
                        <p>Registros de Entrada</p>
                    </a>
                </li>

                <!-- Paginas de Recursos -->
                <li class="nav-item <?php if (isset($activePage[0]) && $activePage[0] == 'recursos') {
                                        echo 'menu-open';
                                    } ?>">
                    <a href="#" class="nav-link <?php if (isset($activePage[0]) && $activePage[0] == 'recursos') {
                                                    echo 'active';
                                                } ?>"><i class="nav-icon fa-solid fa-eye"></i>

                        <p>Recursos<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview ml-3">

                        <li class="nav-item pl-2"><a href="<?php echo HOME_URI; ?>/recursos/setores" class="nav-link <?php if (isset($activePage[0]) && $activePage[0] == 'recursos' && $activePage[1] == 'setores') {
                                                                                                                            echo 'active';
                                                                                                                        } ?>"><i class="fas fa-map-marker nav-icon"></i>
                                <p>Setores</p>
                            </a></li>


                        <li class="nav-item pl-2"><a href="<?php echo HOME_URI; ?>/recursos/leitores" class="nav-link <?php if (isset($activePage[0]) && $activePage[0] == 'recursos' && $activePage[1] == 'leitores') {
                                                                                                                            echo 'active';
                                                                                                                        } ?>"><i class="fas fa-qrcode nav-icon"></i>
                                <p>Leitores Faciais</p>
                            </a></li>

                    </ul>
                </li>

                <li class="nav-item">
                    <a href="<?php echo HOME_URI; ?>/lotes" class="nav-link <?php
                                                                            if (isset($activePage[0]) && $activePage[0] == 'lotes' && (!isset($activePage[1]) || $activePage[1] == 'index')) {
                                                                                echo 'active';
                                                                            }
                                                                            ?>">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>Lotes de Credenciais</p>
                    </a>
                </li>


            <?php } ?>
        <?php } ?>



        <li class="nav-item <?php if (isset($activePage[0]) && $activePage[0] == 'pessoas' && (!isset($activePage[2]) || $activePage[2] !== 'perfil')) {
                                echo 'menu-open';
                            } ?>">
            <a href="#" class="nav-link <?php if (isset($activePage[0]) && $activePage[0] == 'pessoas' && (!isset($activePage[2]) || $activePage[2] !== 'perfil')) {
                                            echo 'active';
                                        } ?>"><i class="nav-icon fas fa-walking"></i>
                <p>Pessoas <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview ml-3">
                <li class="nav-item pl-2"><a href="<?php echo HOME_URI; ?>/pessoas" class="nav-link <?php if (isset($activePage[0]) && $activePage[0] == 'pessoas' && !isset($activePage[2])) {
                                                                                                        echo 'active';
                                                                                                    } ?>"><i class="far fa-calendar nav-icon"></i>
                        <p>Pessoas</p>
                    </a></li>

            </ul>
        </li>

        <li class="nav-item"><a href="<?php echo HOME_URI; ?>/pessoas/index/perfil" class="nav-link <?php if (isset($activePage[0]) && $activePage[0] == 'pessoas' && (isset($activePage[2]) && $activePage[2] == 'perfil') && (!isset($activePage[3]))) {
                                                                                                        echo 'active';
                                                                                                    } ?>"><i class="fas fa-user nav-icon"></i>
                <p>Seu Perfil</p>
            </a></li>

    </ul>
</nav>