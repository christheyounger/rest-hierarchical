<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serial;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TreeRepository")
 * @ORM\Table(indexes={@ORM\Index(name="path_idx", columns={"path"})})
 * @Serial\ExclusionPolicy("all")
 */
class Store implements BranchInterface, PathInterface, TreeInterface
{
    use BranchTrait;
    use PathTrait;
    use TreeTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serial\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serial\Expose()
     */
    private $name;

    public function __construct()
    {
      $this->branches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
