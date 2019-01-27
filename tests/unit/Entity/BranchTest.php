<?php

namespace App\Tests\Unit\Entity;

use App\Entity\BranchInterface;
use App\Entity\BranchTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class BranchTest extends TestCase
{
  private $class;

  public function setup()
  {
    $this->class = new class implements BranchInterface {
      use BranchTrait;
    };
  }

  public function testParentage()
  {
    $branch = new $this->class();
    $this->assertNull($branch->getParent(), 'get null parent');
    $branch->setParent(null);
    $parent = new $this->class();
    $branch->setParent($parent);
    $this->assertEquals($parent, $branch->getParent(), 'parent set properly');
    $this->assertContains($branch, $parent->getBranches(), 'reverse relationship set');
    $newParent = new $this->class();
    $branch->setParent($newParent);
    $this->assertNotContains($branch, $parent->getBranches(), 'removed from old parent');
  }

  public function testBranches()
  {
    $parent = new $this->class();
    $this->assertInstanceOf(
      Collection::class,
      $parent->getBranches(),
      'collection initialised'
    );
    $this->assertEmpty($parent->getBranches(), 'no children to begin with');
    $branch = new $this->class();
    $parent->addBranch($branch);
    $this->assertContains($branch, $parent->getBranches(), 'branch present');
    $parent->setBranches(new ArrayCollection());
    $this->assertEmpty($parent->getBranches(), 'no children anymore');
    $parent->addBranch($branch);
    $this->assertCount(1, $parent->getBranches(), '1 branch present');
    $parent->addBranch($branch);
    $this->assertCount(1, $parent->getBranches(), 'still 1 branch present');
    $parent->removeBranch($branch);
    $parent->removeBranch($branch);
    $this->assertEmpty($parent->getBranches(), 'no children anymore');
  }
}
