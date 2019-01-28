<?php

namespace App\Tests\Unit\Entity;

use App\Entity\BranchInterface;
use App\Entity\BranchTrait;
use App\Entity\PathInterface;
use App\Entity\PathTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
  private $class;

  public function setup()
  {
    $this->class = new class implements PathInterface, BranchInterface {
      use BranchTrait;
      use PathTrait;
      public function getId(): int {
        return 3;
      }
    };
  }

  public function testPaths()
  {
    $node = new $this->class();
    $this->assertNull($node->getPath());
    $node->setPath('/1/2/3');
    $this->assertEquals('/1/2', $node->getParentPath());
    $newParent = new $this->class();
    $newParent->setPath('/5');
    $node->setChildOf($newParent);
    $this->assertEquals('/5/3', $node->getPath());
  }
}
