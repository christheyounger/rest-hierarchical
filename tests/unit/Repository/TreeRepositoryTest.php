<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Store;
use App\Repository\TreeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;

class TreeRepositoryTest extends TestCase
{
  /** @var EntityManagerInterface */
  private $entityManager;
  /** @var ClassMetadata */
  private $meta;
  /** @var TreeRepository */
  private $repo;

  public function setup()
  {
    $this->entityManager = $this->createMock(EntityManagerInterface::class);
    $this->meta = new ClassMetadata(Store::class);
    $this->repo = new TreeRepository($this->entityManager, $this->meta);
  }

  public function testGetRootNodes()
  {
    $queryBuilder = $this->createMock(QueryBuilder::class);
    $this->entityManager->method('createQueryBuilder')->willReturn($queryBuilder);
    $query = $this->createMock(AbstractQuery::class);
    $queryBuilder->method('getQuery')->willReturn($query);
    $queryBuilder->method(static::anything())->willReturnSelf();
    $stores = [new Store()];
    $query->method('getResult')->willReturn($stores);
    $result = $this->repo->getRootNodes();
    $this->assertEquals($stores, $result, 'stores returned');
  }

  public function testGetNodes()
  {
    $queryBuilder = $this->createMock(QueryBuilder::class);
    $this->entityManager->method('createQueryBuilder')->willReturn($queryBuilder);
    $query = $this->createMock(AbstractQuery::class);
    $queryBuilder->method('getQuery')->willReturn($query);
    $queryBuilder->method(static::anything())->willReturnSelf();
    $rootStore = new Store();
    $stores = [new Store()];
    $query->method('getResult')->willReturn($stores);
    $result = $this->repo->getNodes($rootStore);
    $this->assertEquals($stores, $result, 'stores returned');
  }

  public function testBuildTree()
  {
    $this->repo = $this->getMockBuilder(TreeRepository::class)
                       ->setMethods(['getNodes'])
                       ->setConstructorArgs([$this->entityManager, $this->meta])
                       ->getMock();
    $rootStore = $this->createMock(Store::class);
    $rootStore->expects(static::once())->method('buildTree')
              ->with(static::isinstanceof(Collection::class));
    $stores = [new Store()];
    $this->repo->expects(static::once())->method('getNodes')->with($rootStore)
                                                            ->willReturn($stores);
    $this->repo->buildTree($rootStore);
  }

  public function testGetFullTree()
  {
    $this->repo = $this->getMockBuilder(TreeRepository::class)
                       ->setMethods(['getRootNodes', 'buildTree'])
                       ->setConstructorArgs([$this->entityManager, $this->meta])
                       ->getMock();
    $rootNode = $this->createMock(Store::class);
    $this->repo->expects(static::once())->method('getRootNodes')
                                        ->willReturn([$rootNode]);
    $this->repo->expects(static::once())->method('buildTree')->with($rootNode);
    $result = $this->repo->getFullTree();
    $this->assertContains($rootNode, $result, 'root store returned');
  }

  public function testRemove()
  {
    $this->repo = $this->getMockBuilder(TreeRepository::class)
                       ->setMethods(['getNodes'])
                       ->setConstructorArgs([$this->entityManager, $this->meta])
                       ->getMock();
    $rootStore = new Store();
    $stores = [new Store()];
    $this->repo->expects(static::once())->method('getNodes')->with($rootStore)
                                                            ->willReturn($stores);
    $queryBuilder = $this->createMock(QueryBuilder::class);
    $this->entityManager->method('createQueryBuilder')->willReturn($queryBuilder);
    $query = $this->createMock(AbstractQuery::class);
    $queryBuilder->method('getQuery')->willReturn($query);
    $queryBuilder->method(static::anything())->willReturnSelf();
    $query->expects(static::once())->method('execute');
    $this->repo->remove($rootStore);
  }
}
