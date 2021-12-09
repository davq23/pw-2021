<?php require_once 'layout/header.php' ?>
<?php
/**
 * @var ?\Domains\Patient  $patient
 */
$patient = $this->data['current_patient'];
?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-6 justify-content-center">
            <div class="card text-center">
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
                    <h4 class="card-title"><?= isset($patient) ? 'Update' : 'Register' ?> patient info</h4>
                    <form action="<?= BASE_URL . (isset($patient) ? 'patients/update' : 'patients/new') ?>" class="text-start" method="post">
                        <div class="mb-3">
                            <label for="surnames" class="form-label">Surname (Ex. 'David'): </label>
                            <input type="text" class="form-control" name="surnames" id="surnames"
                                   aria-describedby="surnamesHelperId"
                                   <?php if (isset($patient)): ?>
                                       value="<?= htmlspecialchars($patient->getSurname()) ?>"
                                   <?php endif; ?>
                                   placeholder="Juan Pérez" />
                            <small id="surnamesHelperId" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="mb-3">
                            <label for="family_names" class="form-label">Family Name (Ex. 'Quintero'): </label>
                            <input type="text" class="form-control" name="family_names" id="family_names"
                                   aria-describedby="family_namesHelperId"
                                   <?php if (isset($patient)): ?>
                                       value="<?= htmlspecialchars($patient->getFamilyName()) ?>"
                                   <?php endif; ?>
                                   placeholder="Juan Pérez" />
                            <small id="family_namesHelperId" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="mb-3">
                            <label for="dni" class="form-label">DNI (Ex. 25709214): </label>
                            <input type="number" min='0' class="form-control" name="dni" id="dni"
                                   aria-describedby="dniHelperId"
                                   <?php if (isset($patient)): ?>
                                       value="<?= htmlspecialchars($patient->getDni()) ?>"
                                   <?php endif; ?>
                                   placeholder="25709214" />
                            <small id="dniHelperId" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="mb-3">
                            <label for="birthday" class="form-label">Birthday: </label>
                            <input type="text" class="form-control datepicker-element" name="birthday" id="birthday"
                                   aria-describedby="birthdayHelperId"
                                   <?php if (isset($patient)): ?>
                                       value="<?= htmlspecialchars($patient->getBirthday()) ?>"
                                   <?php endif; ?>
                                   placeholder="1997-08-02" />
                            <small id="birthdayHelperId" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email: </label>
                            <input type="email" class="form-control" name="email" id="email"
                                   aria-describedby="emailHelperId"
                                   <?php if (isset($patient)): ?>
                                       value="<?= htmlspecialchars($patient->getBirthday()) ?>"
                                   <?php endif; ?>
                                   placeholder="1997-08-02" />
                            <small id="emailHelperId" class="form-text text-muted">Help text</small>
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