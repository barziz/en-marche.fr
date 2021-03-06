<?php

namespace AppBundle\Entity\ReferentOrganizationalChart;

use AppBundle\Entity\Adherent;
use AppBundle\Entity\Referent;
use AppBundle\Validator\ValidAdherentCoReferent;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReferentOrganizationalChart\ReferentPersonLinkRepository")
 *
 * @ValidAdherentCoReferent
 */
class ReferentPersonLink
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned": true})
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @Assert\Email
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $postalAddress;

    /**
     * @var PersonOrganizationalChartItem
     *
     * @ORM\ManyToOne(targetEntity="PersonOrganizationalChartItem", inversedBy="referentPersonLinks", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $personOrganizationalChartItem;

    /**
     * @var Referent
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Referent", inversedBy="referentPersonLinks", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $referent;

    /**
     * @var Adherent
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Adherent", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $adherent;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isCoReferent = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isJecouteManager = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isMunicipalManagerSupervisor = false;

    public function __construct(PersonOrganizationalChartItem $personOrganizationalChartItem, Referent $referent)
    {
        $this->personOrganizationalChartItem = $personOrganizationalChartItem;
        $this->referent = $referent;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getPostalAddress(): ?string
    {
        return $this->postalAddress;
    }

    public function setPostalAddress(?string $postalAddress): void
    {
        $this->postalAddress = $postalAddress;
    }

    public function getReferent(): ?Referent
    {
        return $this->referent;
    }

    public function setReferent(?Referent $referent): void
    {
        $this->referent = $referent;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): void
    {
        $this->adherent = $adherent;
    }

    public function detachAdherent(): void
    {
        $this->adherent = null;
    }

    public function isCoReferent(): bool
    {
        return $this->isCoReferent;
    }

    public function setIsCoReferent(bool $isCoReferent): void
    {
        $this->isCoReferent = $isCoReferent;
    }

    public function isJecouteManager(): bool
    {
        return $this->isJecouteManager;
    }

    public function setIsJecouteManager(bool $isJecouteManager): void
    {
        $this->isJecouteManager = $isJecouteManager;
    }

    public function isMunicipalManagerSupervisor(): bool
    {
        return $this->isMunicipalManagerSupervisor;
    }

    public function setIsMunicipalManagerSupervisor(bool $isMunicipalManagerSupervisor): void
    {
        $this->isMunicipalManagerSupervisor = $isMunicipalManagerSupervisor;
    }

    public function getPersonOrganizationalChartItem(): ?PersonOrganizationalChartItem
    {
        return $this->personOrganizationalChartItem;
    }

    public function setPersonOrganizationalChartItem(
        ?PersonOrganizationalChartItem $personOrganizationalChartItem
    ): void {
        $this->personOrganizationalChartItem = $personOrganizationalChartItem;
    }

    public function getAdminDisplay(): string
    {
        return sprintf('(%s) %s %s', $this->personOrganizationalChartItem->getLabel(), $this->firstName, $this->lastName);
    }
}
