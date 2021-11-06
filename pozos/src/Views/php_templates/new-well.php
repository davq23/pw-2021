<?php require_once 'layout/header.php' ?>
<?php

use Domains\OilWell;
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="row">
                <div class="col">
                    <h3><i class="bi bi-server"></i> Register oil well</h3>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php require_once 'layout/message-danger-success.php'; ?>
            <div class="row">
                <div class="col">
                    <form action="<?= BASE_URL . 'well/new' ?>" method="post">
                        <label for="name" class="form-label">Name:</label>
                        <input id="name" name="name" class="form-control" type="text" required />
                        <label for="depth-visible" class="form-label">Depth:</label>
                        <input id="depth" name="depth" class="form-control autonumeric" type="text" suffix=" m" required />
                        <label for="estimated_reserves-visible" class="form-label">Estimated reserves:</label>
                        <input id="estimated_reserves" name="estimated_reserves" class="form-control autonumeric" type="text" suffix=" barrels" required />
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php' ?>