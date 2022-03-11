<?php

namespace Trexima\EuropeanCvBundle\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class DateRange
{
    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $beginDay;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $beginMonth;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $beginYear;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $endDay;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $endMonth;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $endYear;

    /**
     * @return int|null
     */
    public function getBeginDay(): ?int
    {
        return $this->beginDay;
    }

    /**
     * @param int|null $beginDay
     */
    public function setBeginDay(?int $beginDay): void
    {
        $this->beginDay = $beginDay;
    }

    /**
     * @return int|null
     */
    public function getBeginMonth(): ?int
    {
        return $this->beginMonth;
    }

    /**
     * @param int|null $beginMonth
     */
    public function setBeginMonth(?int $beginMonth): void
    {
        $this->beginMonth = $beginMonth;
    }

    /**
     * @return int|null
     */
    public function getBeginYear(): ?int
    {
        return $this->beginYear;
    }

    /**
     * @param int|null $beginYear
     */
    public function setBeginYear(?int $beginYear): void
    {
        $this->beginYear = $beginYear;
    }

    /**
     * @return int|null
     */
    public function getEndDay(): ?int
    {
        return $this->endDay;
    }

    /**
     * @param int|null $endDay
     */
    public function setEndDay(?int $endDay): void
    {
        $this->endDay = $endDay;
    }

    /**
     * @return int|null
     */
    public function getEndMonth(): ?int
    {
        return $this->endMonth;
    }

    /**
     * @param int|null $endMonth
     */
    public function setEndMonth(?int $endMonth): void
    {
        $this->endMonth = $endMonth;
    }

    /**
     * @return int|null
     */
    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    /**
     * @param int|null $endYear
     */
    public function setEndYear(?int $endYear): void
    {
        $this->endYear = $endYear;
    }
}