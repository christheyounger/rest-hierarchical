<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface BranchInterface
{
  public function getParent(): ?BranchInterface;

  public function setParent(?BranchInterface $parent): void;

  /** @return Collection|BranchInterface[] */
  public function getBranches(): Collection;

  public function setBranches(Collection $collection): void;

  public function addBranch(BranchInterface $branch): void;

  public function removeBranch(BranchInterface $branch): void;
}
