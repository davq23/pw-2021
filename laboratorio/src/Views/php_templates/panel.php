<?php require_once 'layout/header.php' ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-4 btn-primary py-2 rounded">
            <div class="row">
                <div class="col">
                    <h2>Exams</h2>
                </div>
            </div>
            <div class="row">
                <div class="col fs-extra-large text-center">
                    <i class="bi bi-file-text-fill w-100"></i>
                </div>
            </div>
            <div class="row">
                <div class="col fs-3 text">
                    <h3>Exam count: <?= htmlspecialchars($this->data['exam_count']) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-4 btn-warning text-white py-2 rounded">
            <div class="row">
                <div class="col">
                    <h2>Doctors</h2>
                </div>
            </div>
            <div class="row">
                <div class="col fs-extra-large text-center">
                    <i class="bi bi-file-earmark-medical-fill"></i>
                </div>
            </div>
            <div class="row">
                <div class="col fs-3 text">
                    <h3>Doctor count: <?= htmlspecialchars($this->data['exam_count']) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-4 btn-danger text-white py-2 rounded">
            <div class="row">
                <div class="col">
                    <h2>Diagnosis</h2>
                </div>
            </div>
            <div class="row">
                <div class="col fs-extra-large text-center">
                    <i class="bi bi-file-earmark-medical-fill"></i>
                </div>
            </div>
            <div class="row">
                <div class="col fs-3 text">
                    <h3>Doctor count: <?= htmlspecialchars($this->data['exam_count']) ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php' ?>