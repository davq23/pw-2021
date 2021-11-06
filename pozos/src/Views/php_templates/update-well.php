<?php require_once 'layout/header.php' ?>
<?php

use Domains\OilWell;

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
                    <h3><i class="bi bi-server"></i> Update oil well</h3>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <?php require_once 'layout/message-danger-success.php'; ?>
                    <form action="<?= BASE_URL . '/well/update' ?>" method="post">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($oilWell->getId()) ?>" />
                        <label for="name" class="form-label">Name:</label>
                        <input id="name" name="name" class="form-control" type="text" required
                               value="<?= htmlspecialchars($oilWell->getName()) ?>"/>
                        <label for="depth-visible" class="form-label">Depth:</label>
                        <input type="text" class="form-control autonumeric" name="depth" id="depth"
                               value="<?= htmlspecialchars($oilWell->getDepth()) ?>" suffix=" m"/>
                        <label for="estimated_reserves" class="form-label">Estimated reserves:</label>
                        <input type="text" class="form-control autonumeric" name="estimated_reserves" id="estimated_reserves"
                               value="<?= htmlspecialchars($oilWell->getEstimatedReserves()) ?>" suffix=" barrels"/>
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php' ?>