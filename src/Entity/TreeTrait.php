<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

trait TreeTrait
{
  public function buildTree(Collection $nodes): void
  {
    /** @var PathInterface[] **/
    $tree = [$this->getPath() => $this];

    foreach($nodes as $node)
    {
      $tree[$node->getPath()] = $node;
      $parent = $tree[$node->getParentPath()] ?? null;
      $node->setParent($parent);
      if ($parent) {
        $parent->addBranch($node);
      }
    }
  }
}
