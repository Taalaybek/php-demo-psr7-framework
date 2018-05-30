<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;

class UnitOfWork
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function commit(): void
    {
        $this->em->flush();
    }
}
