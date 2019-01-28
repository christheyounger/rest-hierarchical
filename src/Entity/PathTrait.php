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
    $path = $this->getPath();
    $bits = \explode(PathInterface::PATH_SEPARATOR, $path);
    \array_pop($bits);
    return \implode(PathInterface::PATH_SEPARATOR, $bits);
  }

  public function setChildOf(PathInterface $parent): void
  {
    $path = ($parent ? $parent->getPath() : '') . '/' . $this->getId();
    $this->setPath($path);
    foreach ($this->getBranches() as $branch)
    {
      $branch->setChildOf($this);
    }
    $this->setParent($parent);
  }
}
