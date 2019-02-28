<?php

namespace App\Repository;

use App\Entity\TreeInterface;
use App\Entity\PathInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class TreeRepository extends EntityRepository
{
  public function getRootNodes(): array
  {
    $q = $this->createQueryBuilder('n')
              ->andWhere('length(n.path) <= :length')
              ->setParameter('length', PathInterface::ID_LENGTH);
    return $q->getQuery()->getResult();
  }

  public function getNodes(PathInterface $parent): array
  {
    $q = $this->createQueryBuilder('n')
              ->andWhere('n.path like :path')
              ->andWhere('n.id != :id')
              ->addOrderBy('n.path')
              ->setParameter('id', $parent->getId())
              ->setParameter('path', $parent->getPath() . '%');
    return $q->getQuery()->getResult();
  }

  public function buildTree(TreeInterface $parent)
  {
    $parent->buildTree(new ArrayCollection($this->getNodes($parent)));
  }

  public function getFullTree(): array
  {
    $rootNodes = $this->getRootNodes();
    foreach ($rootNodes as $node) {
      $this->buildTree($node);
    }
    return $rootNodes;
  }

  public function remove(PathInterface $node)
  {
    $branches = $this->getNodes($node);
    $branches[] = $node;
    $builder = $this->getEntityManager()->createQueryBuilder()
        ->delete($this->getClassName(), 'n')
        ->where('n in (:nodes)')
        ->setParameter(':nodes', $branches);
    $builder->getQuery()->execute();
  }
}
