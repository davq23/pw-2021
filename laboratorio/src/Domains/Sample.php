<?php

namespace Domains;

/**
 * Description of Sample
 *
 * @author davido
 */
class Sample extends Material
{
    protected $examId;
    protected $quantity;

    public function getExamId() {
        return $this->examId;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setExamId($examId): void {
        $this->examId = $examId;
    }

    public function setQuantity($quantity): void {
        $this->quantity = $quantity;
    }

    public function __construct($id, $examId, string $name) {
        parent::__construct($id, $name);

        $this->examId = $examId;
    }

}
