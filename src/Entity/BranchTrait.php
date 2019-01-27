<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serial;

trait BranchTrait
{
  /**
   * @var BranchInterface
   * @ORM\ManyToOne(targetEntity="App\Entity\Store", inversedBy="branches")
   * @Serial\Groups("parent")
   */
  private $parent;
  /**
   * @var Collection|BranchInterface[]
   * @ORM\OneToMany(targetEntity="App\Entity\Store", mappedBy="parent")
   * @Serial\Groups("branches")
   */
  private $branches;

  public function getParent(): ?BranchInterface
  {
    return $this->parent;
  }

  public function setParent(?BranchInterface $parent): void
  {
    if ($this->parent) {
      $this->parent->removeBranch($this);
    }
    if ($parent) {
      $parent->addBranch($this);
    }
    $this->parent = $parent;
  }

  public function getBranches(): Collection
  {
    if (!$this->branches instanceOf Collection) {
      $this->branches = new ArrayCollection();
    }
    return $this->branches;
  }

  public function setBranches(Collection $branches): void
  {
    $this->branches = $branches;
  }

  public function addBranch(BranchInterface $branch): void
  {
    if (!$this->getBranches()->contains($branch)) {
      $this->getBranches()->add($branch);
    }
  }

  public function removeBranch(BranchInterface $branch): void
  {
    if ($this->getBranches()->contains($branch)) {
      $this->getBranches()->removeElement($branch);
    }
  }
}
