<?php

namespace AppBundle\Entity\Election;

use Algolia\AlgoliaSearchBundle\Mapping\Annotation as Algolia;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @Algolia\Index(autoIndex=false)
 */
class MinistryListTotalResult
{
    use ListFieldTrait;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $total = 0;

    /**
     * @var MinistryVoteResult|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Election\MinistryVoteResult", inversedBy="listTotalResults")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $ministryVoteResult;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): void
    {
        $this->total = $total;
    }

    public function getMinistryVoteResult(): ?MinistryVoteResult
    {
        return $this->ministryVoteResult;
    }

    public function setMinistryVoteResult(MinistryVoteResult $ministryVoteResult): void
    {
        $this->ministryVoteResult = $ministryVoteResult;
    }
}
