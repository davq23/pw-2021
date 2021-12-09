<?php
/**
 * @var \Views\PHPTemplateView $this
 */
?>
<?php require_once 'layout/header.php' ?>
<div class="container mt-3">
    <div class="row">
        <div class="col">
            <div class="card">
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
                <div class="card-body">
                    <form action="<?= BASE_URL . 'exams/new' ?>" method="post">
                        <div class="text-center">
                            <h4 class="card-title">Exam form</h4>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="patient" class="form-label">Patient:</label>
                                <select name="patient_id" id="patient" class="form-select">
                                    <?php foreach ($this->data['patients'] as $patient): ?>
                                        <option value="<?= htmlspecialchars($patient['id']) ?>">
                                            <?= htmlspecialchars("{$patient['surname']} {$patient['family_name']} ({$patient['dni']})") ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="datetime" class="form-label">Datetime</label>
                                <input type="datetime-local" id="datetime" class="form-control" name="datetime">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" class="form-control" name="description" rows="10" cols="10"></textarea>
                            </div>
                        </div>
                        <input type="submit" name="register" class="btn btn-success" value="Register!" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php'
?>