<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\ConfidenceWidget\Domain;

use DateTimeImmutable;
use Werkspot\JiraDashboard\SharedKernel\Domain\Model\Sprint\Sprint;
use Werkspot\JiraDashboard\SharedKernel\Domain\ValueObject\Id;

class Confidence
{
    /**
     * @var Id
     */
    private $id;

    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @var ConfidenceValueEnum
     */
    private $value;

    /**
     * @var Sprint
     */
    private $sprint;

    public function __construct(DateTimeImmutable $date, ConfidenceValueEnum $value, Sprint $sprint)
    {
        $this->id = Id::create();
        $this->date = $date;
        $this->value = $value;
        $this->sprint = $sprint;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function setDate(DateTimeImmutable $date): Confidence
    {
        $this->date = $date;
        return $this;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setValue(ConfidenceValueEnum $value): Confidence
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(): ConfidenceValueEnum
    {
        return $this->value;
    }

    public function getSprint(): Sprint
    {
        return $this->sprint;
    }
}
