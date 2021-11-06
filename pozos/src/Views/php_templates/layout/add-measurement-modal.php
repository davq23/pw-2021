<!-- Modal -->
<div class="modal fade" id="modalAddMeasurement" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAddMeasurementLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddMeasurementLabel">
                    <i class="bi bi-speedometer"></i> Add new measurement to <span name="name"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL . '/well/measurements/add' ?>" method="post" class="ajax-form">
                <input type="hidden" name="oil_well_id" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-danger invisible" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <strong></strong>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="value" class="form-label">Value read:</label>
                            <input id="value" name="value" class="form-control autonumeric" suffix=" psi" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="date" class="form-label">Date:</label>
                            <input id="date" name="date" class="form-control datepicker-element" required />
                        </div>
                        <div class="col-6">
                            <label for="time" class="form-label">Time:</label>
                            <input id="time" name="time" class="form-control" data-clocklet="format: HH:mm:ss" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-danger" type="submit">Add measurement</button>
                </div>
            </form>

        </div>
    </div>
</div>