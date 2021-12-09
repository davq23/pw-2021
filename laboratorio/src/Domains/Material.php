<?php

namespace Domains;

/**
 * Description of Material
 *
 * @author davido
 */
class Material implements Domain
{
    protected string $name;
    protected $id;

    public function __construct($id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getId() {
        return $this->id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function jsonSerialize(): mixed {

    }

    public function validate(): void {

    }

    public static function fromArray(array $source): Domain {

    }

}
