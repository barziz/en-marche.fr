<?php

namespace AppBundle\Repository\ElectedRepresentative;

use AppBundle\Entity\ElectedRepresentative\ElectedRepresentative;
use AppBundle\Entity\ElectedRepresentative\Mandate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MandateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Mandate::class);
    }

    public function getMandatesForPoliticalFunction(ElectedRepresentative $electedRepresentative): array
    {
        return $this
            ->createQueryBuilder('mandate')
            ->andWhere('mandate.electedRepresentative = :elected_representative')
            ->setParameter('elected_representative', $electedRepresentative)
            ->orderBy('mandate.number', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
