<?php

namespace Reports;

/**
 * Description of Report
 *
 * @author davido
 */
interface Report
{

    public function Details();

    public function Header();

    public function Footer();

    public function Output($dest, $name, $isUTF8 = false);
}
