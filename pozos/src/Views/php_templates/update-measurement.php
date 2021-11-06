<?php require_once 'layout/header.php' ?>
<?php

use Domains\Measurement;

/**
 * @var Measurement $measurement
 */
$measurement = $this->data['measurement'];
/**
 * @var OilWell $oilWell
 */
$oilWell = $this->data['oil_well'];
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="row">
                <div class="col">
                    <h3>
                        <i class="bi bi-bar-chart"></i> Update measurement for
                        <?= htmlspecialchars($oilWell->getName()) ?>
                    </h3>
                </div>
                <div class="col text-end">
                    <form action="<?= BASE_URL . 'well/measurements/delete' ?>" method="post">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($measurement->getId()) ?>"/>
                        <button class="btn btn-danger bi bi-x-lg" type="submit">Delete measurement</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <?php require_once 'layout/message-danger-success.php'; ?>
                    <h6>Taken at <?= htmlspecialchars($measurement->getTime()) ?></h6>
                    <form action="<?= BASE_URL . '/well/measurements/edit' ?>" method="post">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($measurement->getId()) ?>"/>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-6">
                                    <label for="value" class="form-label">Value read:</label>
                                    <input id="value" name="value" class="form-control autonumeric" suffix=" psi" required
                                           value="<?= htmlspecialchars($measurement->getValue()) ?>" />
                                </div>
                            </div>
                            <?php
                            $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $measurement->getTime());
                            ?>
                            <div class="row">
                                <div class="col-6">
                                    <label for="date" class="form-label">Date:</label>
                                    <input id="date" name="date" class="form-control datepicker-element" required
                                           value="<?= htmlspecialchars($dateTime->format('Y-m-d')) ?>"/>
                                </div>
                                <div class="col-6">
                                    <label for="time" class="form-label">Time:</label>
                                    <input id="time" name="time" class="form-control" data-clocklet="format: HH:mm:ss"
                                           required
                                           value="<?= htmlspecialchars($dateTime->format('H:i:s')) ?>"/>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="submit">Edit measurement</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'layout/footer.php' ?>
