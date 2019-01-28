<?php

namespace App\DataFixtures;

use App\Entity\BranchInterface;
use App\Entity\Store;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class StoreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $root = new Store();
        $root->setName('Root store');
        $manager->persist($root);
        $this->addBranches($root, $manager);
        $manager->flush();
        $root->setPath('/' . $root->getId());
        foreach ($root->getBranches() as $branch) {
          $branch->setChildOf($root);
        }
        $manager->flush();
    }

    private function addBranches(BranchInterface $parent, ObjectManager $manager, int $depth = 1)
    {
      for ($i=0; $i<5; $i++)
      {
        $branch = new Store();
        $branch->setName(\uniqid() . ' branch');
        $branch->setParent($parent);
        $manager->persist($branch);
        if ($depth < 5) {
          $this->addBranches($branch, $manager, $depth + 1);
        }
      }
    }
}
