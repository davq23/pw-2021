<?php require_once 'layout/header.php' ?>

<div class="container justify-content-center">
    <div class="row mt-3">
        <div class="col col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Log In</h3>
                </div>
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col">
                            <?php if (isset($this->data['message'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill"></i> <strong><?= htmlspecialchars($this->data['message']) ?></strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            <form method="POST" action="<?= BASE_URL . 'login' ?>">
                                <label class="form-label" for="username-email">Username or email:</label>
                                <input type="text" id="username-email" name="username-email" class="form-control" />
                                <label class="form-label" for="password">Password:</label>
                                <input type="password" name="password" class="form-control" />
                                <button type="submit" class="mt-3 btn btn-primary">Log in</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php' ?>