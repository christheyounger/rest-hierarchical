<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface TreeInterface
{
  public function buildTree(Collection $nodes);
}
