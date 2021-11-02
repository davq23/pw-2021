<?php
require_once __DIR__ . '/api/auth.php';

$auth = auth();
$method = $_SERVER['REQUEST_METHOD'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Notepad</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/main.css" />
    <link rel="shortcut icon" href="notes.png" type="image/png">
    <!-- <div>Icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <?php

    if ($auth && $method === 'GET') :
    ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand bi bi-book" href="#">Notepad</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                File
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createFileModal">
                                        <i class="bi bi-file-plus"></i> New file
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createDirModal">
                                        <i class="bi bi-folder-plus"></i> New directory
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteFileModal">
                                        <i class="bi bi-file-minus"></i> Delete file
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteDirModal">
                                        <i class="bi bi-folder-minus"></i> Delete directory
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <form action="" method="post">
                    <button type="submit" class="btn btn-danger" name="logout">Logout</button>
                </form>
                <a class="btn btn-success bi bi-arrow-clockwise" href="">
                    Reload
                </a>
                <a class="btn btn-primary bi bi-list-task" data-bs-toggle="offcanvas" href="#directoryTreeOffcanvas" role="button" aria-controls="directoryTreeOffcanvas">
                    Workspace
                </a>
            </div>
        </nav>
        <nav>
            <ul class="nav nav-tabs" id="tab-list">
            </ul>
        </nav>
        <form action="api/edit-file.php" method="POST" class="mt-2 container-fluid" id="tab-sections">
        </form>

        <!-- Modal -->
        <div class="modal fade" id="createFileModal" tabindex="-1" aria-labelledby="createFileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createFileModalLabel">Create file</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="api/create-file.php" id="create-file-form" method="POST">
                        <div class="modal-body">
                            <label for="filename">Filename:</label>
                            <input type="text" name="filename" class="form-control" id="filename" />
                            <strong class="text-red"></strong>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="deleteFileModal" tabindex="-1" aria-labelledby="deleteFileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteFileModalLabel">Delete file</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="api/delete-file.php" id="delete-file-form" method="POST">
                        <div class="modal-body">
                            <label for="filename">Filename:</label>
                            <input type="text" name="filename" class="form-control" id="filename" />
                            <strong class="text-red"></strong>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="createDirModal" tabindex="-1" aria-labelledby="createDirModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createDirModalLabel">Create directory</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="api/create-dir.php" id="create-dir-form" method="POST">
                        <div class="modal-body">
                            <label for="dirname">Directory name:</label>
                            <input type="text" name="dirname" class="form-control" id="dirname" />
                            <strong class="text-red"></strong>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="deleteDirModal" tabindex="-1" aria-labelledby="deleteDirModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteDirModalLabel">Delete directory</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="api/delete-dir.php" id="delete-dir-form" method="POST">
                        <div class="modal-body">
                            <label for="dirname">Directory name:</label>
                            <input type="text" name="dirname" class="form-control" id="dirname" />
                            <strong class="text-red"></strong>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="directoryTreeOffcanvas" aria-labelledby="directoryTreeOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="directoryTreeOffcanvasLabel">
                    <i class="bi bi-list-task"></i>
                    Workspace
                </h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="dropdown mt-3">
                    <h6>Files:</h6>
                    <ul id="directory-list" class="list-group">

                    </ul>
                </div>
            </div>
        </div>

        <script src="js/main.js"></script>

    <?php elseif (!$auth && $method === 'GET') : ?>
        <div class="container min-vh-100">
            <div class="col-md-4 m-auto py-5">
                <h5 class="bi bi-book" >Notepad</h5>
                <?php if (isset($_SESSION['message'])) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong><?= htmlspecialchars($_SESSION['message']) ?></strong>
                    </div>
                <?php endif; ?>
                <form action="" method="post">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" class="form-control" name="username" id="username" required autocomplete="off">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required autocomplete="off">
                    <button type="submit" name="login" class="btn btn-primary mt-3">Login</button>
                </form>
            </div>
        </div>
    <?php 
        session_destroy();


    elseif ($auth && $method === 'POST'):
    
        if (isset($_POST['logout']) && session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        header('Location: ./', true);
        exit();

    elseif (!$auth && $method === 'POST'):
        if (!isset($_POST['login'])) {
            $_SESSION['message'] = 'Invalid login';
            header('Location: ./', true);
            exit();
        }

        require_once 'config/database.php';

        if (!$mysqli) {
            http_response_code(500);
            error_log(mysqli_connect_error());
            $_SESSION['message'] = 'Unknown error';
            header('Location: ./', true);
            exit();
        }

        $usernameInput = filter_input(INPUT_POST, 'username');
        $passwordInput = filter_input(INPUT_POST, 'password');
        
        if (!$usernameInput || !$passwordInput) {
            $_SESSION['message'] = 'Invalid login';
            header('Location: ./', true);
            exit();
        }

        $statement = mysqli_prepare($mysqli, 'SELECT id, password FROM users WHERE username = ? LIMIT 1');
        
        $id = null;
        $hash = null;

        if (
            !mysqli_stmt_bind_param($statement, 's', $usernameInput)  ||
            !mysqli_stmt_bind_result($statement, $id, $hash) ||
            !mysqli_stmt_execute($statement)
        ) {
            http_response_code(500);
            error_log(mysqli_connect_error());
            $_SESSION['message'] = 'Unknown error';
            header('Location: ./', true);
            exit();
        }

        mysqli_stmt_fetch($statement);

        if (!$id || !$hash || !password_verify($passwordInput, $hash)) {
            $_SESSION['message'] = 'Invalid username or password';
            header('Location: ./', true);
            exit();
        }

        mkdir(__DIR__."/workspace/user_$id");

        session_regenerate_id(true);

        $_SESSION['user_id'] = $id;

        header('Location: ./', true);
        exit();
    else :
        http_response_code(405);
        exit();
    ?>
    <?php endif; ?>

    <script>
        var alertList = document.querySelectorAll('.alert');
        alertList.forEach(function(alert) {
            new bootstrap.Alert(alert)
        })
    </script>
</body>

</html>