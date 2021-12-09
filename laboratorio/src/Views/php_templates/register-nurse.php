<?php require_once 'layout/header.php' ?>
<?php
/**
 * @var ?\Domains\nurse  $nurse
 */
$nurse = $this->data['current_nurse'];
?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-6 justify-content-center">
            <div class="card text-center">
                <img class="card-img-top" src="holder.js/100px180/" alt="">
                <div class="card-body">
                    <?php if (isset($this->data['message-danger'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?= htmlspecialchars($this->data['message-danger']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($this->data['message-success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            <?= htmlspecialchars($this->data['message-success']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <h4 class="card-title"><?= isset($nurse) ? 'Update' : 'Register' ?> nurse info</h4>
                    <form action="<?= BASE_URL . (isset($nurse) ? 'nurses/update' : 'nurses') ?>" class="text-start" method="post">
                        <div class="mb-3">
                            <label for="surnames" class="form-label">Surnames (Ex. 'David Armando'): </label>
                            <input type="text" class="form-control" name="surnames" id="surnames"
                                   aria-describedby="surnamesHelperId"
                                   <?php if (isset($nurse)): ?>
                                       value="<?= htmlspecialchars($nurse->getSurnames()) ?>"
                                   <?php endif; ?>
                                   placeholder="Juan Pérez" />
                            <small id="surnamesHelperId" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="mb-3">
                            <label for="family_names" class="form-label">Family Names (Ex. 'Quintero Granadillo'): </label>
                            <input type="text" class="form-control" name="family_names" id="family_names"
                                   aria-describedby="family_namesHelperId"
                                   <?php if (isset($nurse)): ?>
                                       value="<?= htmlspecialchars($nurse->getFamilyNames()) ?>"
                                   <?php endif; ?>
                                   placeholder="Juan Pérez" />
                            <small id="family_namesHelperId" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="mb-3">
                            <label for="birthday" class="form-label">Family Names (Ex. 'Quintero Granadillo'): </label>
                            <input type="text" class="form-control datepicker-element" name="birthday" id="birthday"
                                   aria-describedby="birthdayHelperId"
                                   <?php if (isset($nurse)): ?>
                                       value="<?= htmlspecialchars($nurse->getBirthday()) ?>"
                                   <?php endif; ?>
                                   placeholder="1997-08-02" />
                            <small id="birthdayHelperId" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-danger">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php' ?>