<?php
/**
 * @var \Views\PHPTemplateView $this
 */
/**
 * @var \Domains\Exam $exam
 */
$exam = $this->data['exam'];
?>
<?php require_once 'layout/header.php' ?>
<div class="container mt-3">
    <div class="row">
        <div class="col">
            <div class="card">
                <img class="card-img-top" src="holder.js/100px180/" alt="">
                <div class="card-body">
                    <div class="text-center">
                        <h4 class="card-title">Exam results</h4>
                    </div>
                    <form action="<?= BASE_URL . 'exams/results' ?>" method="post">
                        <div class="row">
                            <div class="col">
                                <input type="hidden" name="exam_id"
                                       value="<?= htmlspecialchars($exam->getId()) ?>">
                                <label for="results" class="form-label">Results</label>
                                <textarea id="results" class="form-control" name="results" rows="30" cols="10"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input type="submit" class="btn btn-danger" name="result-add" value="Send">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php'
?>

