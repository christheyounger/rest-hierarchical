<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Store;
use PHPUnit\Framework\TestCase;

class StoreTest extends TestCase
{
  public function test()
  {
    $store = new Store();
    $this->assertNull($store->getId());
    $this->assertEmpty($store->getName());
    $store->setName('New Store');
    $this->assertEquals('New Store', $store->getName());
  }
}
