<?php require_once 'layout/header.php' ?>
<?php

use Domains\OilWell;
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="row">
                <div class="col">
                    <h3><i class="bi bi-server"></i> Oil wells</h3>

                </div>
                <div class="col text-end">
                    <a href="<?= BASE_URL . 'well/new' ?>" class="btn btn-success bi bi-plus-lg text"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <?php require_once 'layout/message-danger-success.php'; ?>
            </div>
            <div class="row">
                <div class="col">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Name</th>
                                <th>Depth</th>
                                <th>Estimated reserves</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            /** @var OilWell $oilWell */
                            foreach ($this->data['oil_wells'] as $oilWell):
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($oilWell->getId()) ?></td>
                                    <td><?= htmlspecialchars($oilWell->getName()) ?></td>
                                    <td class="text-center">
                                        <?= htmlspecialchars(number_format($oilWell->getDepth(), 2, ',', '.')) ?> m
                                    </td>
                                    <td class="text-center">
                                        <?= htmlspecialchars(number_format($oilWell->getEstimatedReserves(), 2, ',', '.')) ?> barrels
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-src="<?= htmlspecialchars(json_encode($oilWell)) ?>"
                                                    data-bs-target="#modalChart">
                                                <i class="bi bi-bar-chart"></i>
                                            </button>
                                            <button class="btn btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-src="<?= htmlspecialchars(json_encode($oilWell)) ?>"
                                                    data-bs-target="#modalAddMeasurement">
                                                <i class="bi bi-speedometer"></i>
                                            </button>
                                            <a class="btn btn-warning" href="<?= BASE_URL . 'well/update?oil_well=' . $oilWell->getId() ?>" >
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button class="btn btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
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
<?php require_once 'layout/add-measurement-modal.php'; ?>
<?php require_once 'layout/chart-modal.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js" integrity="sha256-7lWo7cjrrponRJcS6bc8isfsPDwSKoaYfGIHgSheQkk=" crossorigin="anonymous"></script>
<?php require_once 'layout/footer.php' ?>