<!-- Nav tabs -->
<?php $logged = isset($this->data['current_user']); ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE_URL . 'panel' ?>">Super Lab</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0" id="navId" role="tablist">
                <?php if ($logged) : ?>
                    <div class="nav-item">
                        <a href="<?= BASE_URL . 'exams' ?>"
                           class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/exams') !== false ? 'active' : '' ?>">
                            <i class="bi bi-file-text-fill"></i> My Exams
                        </a>
                    </div>
                <?php else : ?>
                    <li class="nav-item">
                        <a href="<?= BASE_URL . 'login' ?>"
                           class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/login') !== false ? 'active' : '' ?>">
                            <i class="bi bi-box-arrow-in-right"></i> Log in
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL . 'signup' ?>"
                           class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/signup') !== false ? 'active' : '' ?>">
                            <i class="bi bi-pencil-square"></i> Sign up
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <?php if ($logged) : ?>
            <div class="dropdown me-2">
                <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-fill"></i> <?= $this->data['current_user']->getUsername() ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="<?=
                        $this->data['current_user']->getUserRole() === \Domains\User::USER_ROLE_PATIENT ?
                            BASE_URL . 'patients/register' : BASE_URL . 'doctors/register'
                        ?>">Profile info</a></li>
                    <li><a class="dropdown-item"  href="<?= BASE_URL . 'patients/register' ?>">Another action</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="text-center">
                        <form action="<?= BASE_URL . 'logout' ?>" method="post">
                            <button type="logout" class="btn btn-danger"><i class="bi bi-door-open-fill"></i> Log out</button>
                        </form>
                    </li>
                </ul>
            </div>

        <?php endif; ?>
    </div>
</nav>