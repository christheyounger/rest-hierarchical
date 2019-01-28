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

  /**
   * @REST\View(serializerGroups={"Default", "branches"})
   */
  public function cgetAction(): array
  {
    $repo = $this->entityManager->getRepository(Store::class);
    return $repo->getFullTree();
  }

  /**
   * @REST\View(serializerGroups={"Default"})
   */
  public function getAction(Store $store): Store
  {
    return $store;
  }

  /**
   * @REST\View(serializerGroups={"Default", "branches"})
   */
  public function getBranchesAction(Store $store): Store
  {
    $repo = $this->entityManager->getRepository(Store::class);
    $repo->buildTree($store);
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
   * @REST\View(serializerGroups={"Default", "parent"})
   */
  public function postBranchAction(Store $store, Request $request): Store
  {
    $branch = new Store();
    $branch->setChildOf($store);
    $branch->setName($request->get('name'));
    $this->entityManager->persist($branch);
    $this->entityManager->flush();
    return $branch;
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

  /**
   * @REST\Put("/stores/{store}/parent/{parent}")
   * @REST\View(serializerGroups={"Default", "parent"})
   */
  public function putParentAction(Store $store, Store $parent): Store
  {
    $repo = $this->entityManager->getRepository(Store::class);
    $repo->buildTree($store); // because we want to reparent branches too!
    $store->setChildOf($parent);
    $this->entityManager->flush();
    return $store;
  }

  public function deleteAction(Store $store): void
  {
    $this->entityManager->getRepository(Store::class)->remove($store);
  }
}
