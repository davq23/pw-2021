<!-- Modal -->
<div class="modal fade" id="modalChart" data-bs-backdrop="static" data-bs-keyboard="false"
     url="<?= BASE_URL . 'well/measurements' ?>" callback="updateData"
     tabindex="-1" aria-labelledby="modalChartLabel" aria-hidden="true">
    <div class="modal-dialog min-vw-75">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalChartLabel">
                    <i class="bi bi-bar-chart-fill"></i> Measurements for <span name="name"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div>
                            <a class="btn btn-primary" data-bs-toggle="collapse" href="#filterCollapse" role="button" aria-expanded="false" aria-controls="filterCollapse">
                                Filter options
                            </a>
                        </div>
                        <form class="ajax-form collapse row submit-on-input mt-3"
                              id="filterCollapse"
                              method="get"
                              action="<?= BASE_URL . 'well/measurements' ?>">
                            <input type="hidden" name="oil_well" />
                            <div class="col">
                                <label for="year" class="form-label">Year</label>
                                <input class="form-control" type="number"
                                       id="year"
                                       name="year"
                                       min="2000" max="<?= date('Y') ?>"/>
                            </div>
                            <div class="col">
                                <label for="month" class="form-label">Month</label>
                                <input class="form-control" type="number"
                                       id="month"
                                       name="month"
                                       min="1" max="12"/>
                            </div>
                            <div class="col">
                                <label for="day" class="form-label">Day</label>
                                <input class="form-control" type="number"
                                       id="day"
                                       name="day"
                                       min="0" max="31"/>
                            </div>
                        </form>


                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <canvas id="chart-panel"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
