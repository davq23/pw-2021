<?php require_once 'layout/header.php'; ?>
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="row">
                    <div class="col">
                        <h3><i class="bi bi-file-text"></i> My Exams</h3>

                    </div>
                    <div class="col text-end">
                        <button class="btn btn-success bi bi-plus-lg fs-6 text"></button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <table class="table">
                            <thead>
                                <tr class="text-center">
                                    <th>Booking date</th>
                                    <th>Planned date</th>
                                    <th>Actual date</th>
                                    <th>Status</th>
                                    <th>Results</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($this->data['exams'] as $exam): ?>
                                    <tr>
                                        
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