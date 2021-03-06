<?php
/**
 * @var \Views\PHPTemplateView $this
 */
/**
 * @var ?\Domains\Doctor  $doctor
 */
$doctor = $this->data['current_doctor'];
?>
<?php require_once 'layout/header.php' ?>
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
                    <h4 class="card-title"><?= isset($doctor) ? 'Update' : 'Register' ?> doctor info</h4>
                    <form action="<?= BASE_URL . (isset($doctor) ? 'doctors/update' : 'doctors') ?>" class="text-start" method="post">
                        <input type="hidden" name="id" value="<?= $doctor ? $doctor->getId() : ''; ?>"
                               <div class="mb-3">
                        <label for="surnames" class="form-label">Surnames (Ex. 'David Armando'): </label>
                        <input type="text" class="form-control" name="surnames" id="surnames"
                               aria-describedby="surnamesHelperId"
                               <?php if (isset($doctor)): ?>
                                   value="<?= htmlspecialchars($doctor->getSurnames()) ?>"
                               <?php endif; ?>
                               placeholder="Juan P??rez" />
                        <small id="surnamesHelperId" class="form-text text-muted">Help text</small>
                        <div class="mb-3">
                            <label for="family_names" class="form-label">Family Names (Ex. 'Quintero Granadillo'): </label>
                            <input type="text" class="form-control" name="family_names" id="family_names"
                                   aria-describedby="family_namesHelperId"
                                   <?php if (isset($doctor)): ?>
                                       value="<?= htmlspecialchars($doctor->getFamilyNames()) ?>"
                                   <?php endif; ?>
                                   placeholder="Juan P??rez" />
                            <small id="family_namesHelperId" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="mb-3">
                            <label for="birthday" class="form-label">Birthday: </label>
                            <input type="text" class="form-control datepicker-element" name="birthday" id="birthday"
                                   aria-describedby="birthdayHelperId"
                                   <?php if (isset($doctor)): ?>
                                       value="<?= htmlspecialchars($doctor->getBirthday()) ?>"
                                   <?php endif; ?>
                                   placeholder="1997-08-02" />
                            <small id="birthdayHelperId" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="mb-3 credentials-container" input-name="credentials[]">
                            <label for="credentials"
                                   class="d-flex justify-content-between align-items-center">
                                Credentials
                                <button class="btn btn-success bi bi-plus-lg"
                                        type="button"
                                        action="add-credential">
                                </button>
                            </label>

                            <ul class="list-group">
                                <?php
                                if (isset($doctor)):
                                    $credentials = $doctor->getCredentials();
                                    foreach ($credentials as $credential):
                                        ?>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <input type="text" class="form-control" name="credentials[]"
                                                   value="<?= htmlspecialchars($credential) ?>"
                                                   />
                                            <button class="btn btn-danger bi bi-x-lg"
                                                    action="delete-credential"
                                                    ></button>
                                        </li>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </ul>

                        </div>
                        <div class = "text-end">
                            <button type = "submit" class = "btn btn-danger">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php'
?>

