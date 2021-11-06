<?php

namespace Views;

use Utils\Utils;
use Views\Exceptions\InvalidViewException;

class PHPTemplateView implements View
{
    protected array $data;
    protected string $filename;
    protected string $folder;

    /**
     * @param string $filename
     * @throws InvalidViewException
     */
    public function __construct(string $filename, array $data, string $folder = 'php_templates')
    {
        if (!Utils::endsWith($filename, '.php')) {
            throw new InvalidViewException();
        }

        $this->data = $data;
        $this->filename = $filename;
        $this->folder = $folder;
    }

    /** {@inheritDoc} */
    public function render(): string
    {
        ob_start();

        require __DIR__.DIRECTORY_SEPARATOR.$this->folder.DIRECTORY_SEPARATOR.$this->filename;

        return ob_get_clean();
    }
}