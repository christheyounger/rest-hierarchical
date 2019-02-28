<?php

namespace App\Entity;

use JMS\Serializer\Annotation as Serial;

trait PathTrait
{
  /**
   * @var string
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  protected $path;

  public function getEncodedId(): string
  {
    $id = \base_convert((string) $this->getId(), 10, 36);
    $max = PathInterface::ID_LENGTH;
    if (\strlen($id) > $max) {
      throw new \LogicException("ID $id is longer than $max characters, cannot encode");
    }
    return \str_pad($id, $max, '0', STR_PAD_LEFT);
  }

  public function getPath():? string
  {
    return $this->path;
  }

  public function setPath(String $path): void
  {
    $this->path = $path;
  }

  public function getParentPath(): string
  {
    return \substr($this->getPath(), 0, -PathInterface::ID_LENGTH);
  }

  public function setChildOf(PathInterface $parent): void
  {
    $path = ($parent ? $parent->getPath() : '') . $this->getEncodedId();
    if (\strlen($path) > PathInterface::MAX_LENGTH) {
      throw new \LogicException("Path $path is too long to store in our database");
    }
    $this->setPath($path);
    foreach ($this->getBranches() as $branch)
    {
      $branch->setChildOf($this);
    }
    $this->setParent($parent);
  }
}
