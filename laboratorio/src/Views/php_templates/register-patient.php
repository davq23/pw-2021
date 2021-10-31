<?php require_once 'layout/header.php' ?>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-6 justify-content-center">
            <div class="card text-center">
                <img class="card-img-top" src="holder.js/100px180/" alt="">
                <div class="card-body">
                    <h4 class="card-title">Register patient info</h4>
                    <form action="<?= BASE_URL . 'patients' ?>" class="text-start" method="post">
                        <div class="mb-3">
                            <label for="surnames" class="form-label">Surnames (Ex. 'David Armando'): </label>
                            <input type="text" class="form-control" name="surnames" id="surnames" aria-describedby="surnamesHelperId" placeholder="Juan Pérez">
                            <small id="surnamesHelperId" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="mb-3">
                            <label for="family_names" class="form-label">Family Names (Ex. 'Quintero Granadillo'): </label>
                            <input type="text" class="form-control" name="family_names" id="family_names" aria-describedby="family_namesHelperId" placeholder="Juan Pérez">
                            <small id="family_namesHelperId" class="form-text text-muted">Help text</small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php' ?>