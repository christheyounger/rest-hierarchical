<?php

namespace App\Controller;

use App\Entity\Store;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Routing\ClassResourceInterface;

class StoresController extends AbstractFOSRestController implements ClassResourceInterface
{
  /** @var EntityManagerInterface */
  private $entityManager;

  public function __construct(EntityManagerInterface $em)
  {
    $this->entityManager = $em;
  }

  public function cgetAction(): array
  {
    $repo = $this->entityManager->getRepository(Store::class);
    return $repo->findAll();
  }
}
