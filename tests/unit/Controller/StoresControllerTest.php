<?php

namespace App\Tests\Unit\Controller;

use App\Controller\StoresController;
use App\Entity\Store;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class StoresControllerTest extends TestCase
{
  /** @var EntityManagerInterface */
  private $entityManager;

  public function setup()
  {
    $this->entityManager = $this->createMock(EntityManagerInterface::class);
    $this->controller = new StoresController($this->entityManager);
  }

  public function testCgetAction(): void
  {
    $stores = [new Store()];
    $repo = $this->createMock(EntityRepository::class);
    $this->entityManager->method('GetRepository')->willReturn($repo);
    $repo->expects(static::once())->method('findAll')->willReturn($stores);
    $result = $this->controller->cgetAction();
    $this->assertEquals($stores, $result, 'returns the stores');
  }

  public function testGetAction(): void
  {
    $store = new Store();
    $this->assertEquals(
      $store,
      $this->controller->getAction($store),
      'returns the store'
    );
  }

  public function testPostAction(): void
  {
    $data = ['name' => 'new store'];
    $request = new Request([], $data);
    $this->entityManager->expects(static::once())->method('persist');
    $this->entityManager->expects(static::once())->method('flush');
    $result = $this->controller->postAction($request);
    $this->assertInstanceOf(Store::class, $result, 'returns a store');
    $this->assertEquals('new store', $result->getName(), 'name set correctly');
  }

  public function testPutAction(): void
  {
    $store = new Store();
    $store->setName('old name');
    $data = ['name' => 'new name'];
    $request = new Request([], $data);
    $this->entityManager->expects(static::once())->method('flush');
    $result = $this->controller->putAction($request, $store);
    $this->assertInstanceOf(Store::class, $result, 'returns a store');
    $this->assertEquals($store, $result, 'returned same object');
    $this->assertEquals('new name', $result->getName(), 'name set correctly');
  }

  public function testDeleteAction(): void
  {
    $store = new Store();
    $this->entityManager->expects(static::once())->method('remove')->with($store);
    $this->entityManager->expects(static::once())->method('flush');
    $result = $this->controller->deleteAction($store);
  }
}
