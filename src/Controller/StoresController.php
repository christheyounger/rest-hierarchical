<?php

namespace App\Controller;

use App\Entity\Store;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;

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

  public function getAction(Store $store): Store
  {
    return $store;
  }

  /**
   * @REST\RequestParam(name="name", nullable=false)
   */
  public function postAction(Request $request): Store
  {
    $store = new Store();
    $store->setName($request->get('name'));
    $this->entityManager->persist($store);
    $this->entityManager->flush();
    return $store;
  }

  /**
   * @REST\RequestParam(name="name", nullable=false)
   */
  public function putAction(Request $request, Store $store): Store
  {
    $store->setName($request->get('name'));
    $this->entityManager->flush();
    return $store;
  }

  public function deleteAction(Store $store): void
  {
    $this->entityManager->remove($store);
    $this->entityManager->flush();
  }
}
