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
      public $id = 341123;
      public function getId(): int {
        return $this->id;
      }
    };
  }

  public function testPaths()
  {
    $node = new $this->class();
    $this->assertNull($node->getPath());
    $node->setPath('000010000200003');
    $this->assertEquals('0000100002', $node->getParentPath());
    $newParent = new $this->class();
    $newParent->setPath('00005');
    $node->setChildOf($newParent);
    $this->assertEquals('0000507b7n', $node->getPath());
    $newParent->setPath(\str_pad("5", 251, "0")); // Child path will be too long!
    $this->expectException(\LogicException::class);
    $node->setChildOf($newParent);

  }

  public function testEncoding()
  {
    $node = new $this->class();
    $node->id = 60466175;
    $this->assertEquals('zzzzz', $node->getEncodedId());
    $node->id = 60466177; // ID is too large
    $this->expectException(\LogicException::class);
    $node->getEncodedId();
  }
}
