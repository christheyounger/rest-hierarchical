<?php

namespace App\Tests\Unit\Entity;

use App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class TreeTest extends TestCase
{
  private $class;

  public function setup()
  {
    $this->class = new class implements Entity\TreeInterface,
      Entity\PathInterface,
      Entity\BranchInterface {
      use Entity\TreeTrait;
      use Entity\PathTrait;
      use Entity\BranchTrait;
      public function getId(): ?int {
        return 1;
      }
    };
  }

  public function test()
  {
    $parent = new $this->class();
    $parent->setPath('/1');
    $child = new $this->class();
    $child->setPath('/1/2');
    $parent->buildTree(new ArrayCollection([$child]));
    $this->assertContains($child, $parent->getBranches(), 'child added');
    $this->assertEquals($parent, $child->getParent(), 'parent set');
  }
}
