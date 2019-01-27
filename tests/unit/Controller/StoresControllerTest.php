<?php

namespace App\Tests\Unit\Controller;

use App\Controller\StoresController;
use App\Entity\Store;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class StoresControllerTest extends TestCase
{
  /** @var EntityManagerInterface */
  private $entityManager;

  public function setup()
  {
    $this->entityManager = $this->createMock(EntityManagerInterface::class);
    $this->controller = new StoresController($this->entityManager);
  }

  public function testCgetAction()
  {
    $stores = [new Store()];
    $repo = $this->createMock(EntityRepository::class);
    $this->entityManager->method('GetRepository')->willReturn($repo);
    $repo->expects(static::once())->method('findAll')->willReturn($stores);
    $result = $this->controller->cgetAction();
    $this->assertEquals($stores, $result);
  }
}
