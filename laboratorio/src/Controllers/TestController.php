<?php

namespace Controllers;

use Reports\TestReport;
use Views\ReportView;
use Views\View;

/**
 * Description of TestController
 *
 * @author davido
 */
class TestController extends Controller
{

    public function testReport(): View {
        $message = filter_input(INPUT_GET, 'message') ?? 'Hello world!';

        return new ReportView(new TestReport($message));
    }

}
