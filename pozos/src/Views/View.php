<?php

namespace Views;

interface View
{
    /**
     * Renders the view
     *
     * @return string
     */
    public function render(): string;
}