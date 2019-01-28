<?php

namespace App\Entity;

interface PathInterface
{
  const PATH_SEPARATOR = '/';

  public function getId(): ?int;

  public function getPath(): ?string;

  public function getParentPath(): string;

  public function setPath(string $path): void;
}
