<?php require_once 'layout/header.php' ?>

<div class="container justify-content-center">
    <div class="row mt-3">
        <div class="col col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Sign Up</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($this->data['message'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <strong><?= htmlspecialchars($this->data['message']) ?></strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="<?= BASE_URL . 'signup' ?>">
                        <label class="form-label" for="username">Username:</label>
                        <input type="text" id="username" name="username" class="form-control"
                               value="<?= htmlspecialchars($this->data['user_array'] ? $this->data['user_array']['username'] : '') ?>" />
                        <label class="form-label" for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control"
                               value="<?= htmlspecialchars($this->data['user_array'] ? $this->data['user_array']['email'] : '') ?>"/>
                        <label class="form-label" for="password">Password:</label>
                        <input type="password" name="password" id="password" class="form-control"
                               value="<?= htmlspecialchars($this->data['user_array'] ? $this->data['user_array']['password'] : '') ?>"/>
                        <button type="submit" class="mt-3 btn btn-primary">Sign up</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php' ?>
