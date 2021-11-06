<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Domains;

use Domains\Exceptions\InvalidDomainException;

/**
 * Description of OilWell
 *
 * @author davido
 */
class OilWell implements Domain
{
    private $id;
    private string $name;
    private float $depth;
    private float $estimatedReserves;

    public function __construct(
        $id,
        string $name,
        float $depth,
        float $estimatedReserves
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->depth = $depth;
        $this->estimatedReserves = $estimatedReserves;
    }

    public function getId() {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDepth(): float {
        return $this->depth;
    }

    public function getEstimatedReserves(): float {
        return $this->estimatedReserves;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setDepth(float $depth): void {
        $this->depth = $depth;
    }

    public function setEstimatedReserves(float $estimatedReserves): void {
        $this->estimatedReserves = $estimatedReserves;
    }

    //put your code here
    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'depth' => $this->depth,
            'estimated_reserves' => $this->estimatedReserves
        );
    }

    public function validate(): void {
        $lenName = mb_strlen($this->name);

        if ($lenName > 120 || $lenName < 3) {
            throw new InvalidDomainException('Name of oil well must be between 3 and 120 characters');
        }

        if ($this->depth <= 0) {
            throw new InvalidDomainException('Invalid oil well depth');
        }

        if ($this->estimatedReserves <= 0) {
            throw new InvalidDomainException('Invalid estimated reserves');
        }
    }

    public static function fromArray(array $source): OilWell {
        return new OilWell(
            $source['id'] ?? null,
            $source['name'] ?? '',
            $source['depth'] ?? 0,
            $source['estimated_reserves'] ?? 0
        );
    }

}
