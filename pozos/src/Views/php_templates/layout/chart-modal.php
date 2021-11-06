<!-- Modal -->
<div class="modal fade" id="modalChart" data-bs-backdrop="static" data-bs-keyboard="false"
     url="<?= BASE_URL . 'well/measurements' ?>"
     tabindex="-1" aria-labelledby="modalChartLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalChartLabel">
                    <i class="bi bi-bar-chart-fill"></i> Measurements for <span name="name"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" >
                <canvas id="chart-panel"></canvas>
            </div>
        </div>
    </div>
</div>
