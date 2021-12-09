<?php

use Domains\User;
?>
<?php require_once 'layout/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="row">
                <div class="col">
                    <h3><i class="bi bi-file-text"></i> My Exams</h3>

                </div>
                <div class="col text-end">
                    <a class="btn btn-success bi bi-plus-lg fs-6 text"
                       href="<?= BASE_URL . 'exams/new' ?>"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
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
                    <table class="table text-center table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            /**
                             * @var \Domains\Exam $exam
                             */
                            foreach ($this->data['exams'] as $exam):
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($exam->getId()) ?></td>
                                    <td><?= htmlspecialchars($exam->getDatetime()) ?></td>
                                    <td><?= htmlspecialchars($exam->getStatus()) ?></td>
                                    <td>
                                        <?php
                                        if (
                                            $this->data['current_user']->getUserRole() === User::USER_ROLE_DOCTOR &&
                                            $exam->getStatus() === 'created'):
                                            ?>
                                            <a href="<?= BASE_URL . 'exams/results?exam_id=' . $exam->getId() ?>" class="btn btn-danger">REGISTER RESULTS</a>
                                        <?php endif; ?>
                                        <?php
                                        if (
                                            $exam->getStatus() === 'done'):
                                            ?>
                                            <a href="<?= BASE_URL . 'exams/results/send?exam=' . $exam->getId() ?>" class="btn btn-primary">SEND AGAIN</a>
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'layout/footer.php'; ?>