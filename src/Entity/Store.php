<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serial;

/**
 * @ORM\Entity()
 * @Serial\ExclusionPolicy("all")
 */
class Store implements BranchInterface
{
    use BranchTrait;
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
