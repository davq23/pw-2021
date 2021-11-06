<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Domains;

use DateTime;
use Domains\Exceptions\InvalidDomainException;

/**
 * Description of Measurement
 *
 * @author davido
 */
class Measurement implements Domain
{
    private $id;
    private float $value;
    private $oilWellId;
    private string $time;
    private $userId;

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId): void {
        $this->userId = $userId;
    }

    public function __construct(
        $id,
        float $value,
        $oilWellId,
        string $time,
        $userId
    ) {
        $this->id = $id;
        $this->value = $value;
        $this->oilWellId = $oilWellId;
        $this->time = $time;
        $this->userId = $userId;
    }

    public function getId() {
        return $this->id;
    }

    public function getValue(): float {
        return $this->value;
    }

    public function getValueBar(): float {
        return ($this->value * 0.0689476) / 1.00000039262;
    }

    public function getOilWellId() {
        return $this->oilWellId;
    }

    public function getTime(): string {
        return $this->time;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setValue(float $value): void {
        $this->value = $value;
    }

    public function setOilWellId($oilWellId): void {
        $this->oilWellId = $oilWellId;
    }

    public function setTime(string $time): void {
        $this->time = $time;
    }

    public function jsonSerialize(): mixed {
        return array(
            'id' => $this->id,
            'oil_well_id' => $this->oilWellId,
            'value' => $this->value,
            'time' => $this->time,
            'bar' => $this->getValueBar()
        );
    }

    public function validate(): void {
        if ($this->value < 0) {
            throw new InvalidDomainException('Invalid measurement value');
        }

        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $this->time);

        if (!$datetime || $datetime > DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))) {
            throw new InvalidDomainException('Invalid date and time');
        }
    }

    public static function fromArray(array $source): Measurement {
        return new Measurement(
            $source['id'] ?? null,
            $source['value'] ?? 0,
            $source['oil_well_id'] ?? null,
            $source['time'] ?? date('Y-m-d H:m:s'),
            $source['user_id'] ?? null
        );
    }

}
