<?php

namespace AppBundle\Adherent\Unregistration\Handlers;

use AppBundle\Entity\Adherent;
use AppBundle\Repository\AdherentSegmentRepository;

class RemoveAdherentSegmentHandler implements UnregistrationAdherentHandlerInterface
{
    private $repository;

    public function __construct(AdherentSegmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function supports(Adherent $adherent): bool
    {
        return true;
    }

    public function handle(Adherent $adherent): void
    {
        $this->repository->removeAuthorItems($adherent);
    }
}
