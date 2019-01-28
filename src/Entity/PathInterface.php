<?php

namespace App\Entity;

interface PathInterface
{

  const MAX_DEPTH = 50;

  const ID_LENGTH = 5;

  const MAX_LENGTH = 255;

  public function getId(): ?int;

  public function getEncodedId(): string;

  public function getPath(): ?string;

  public function getParentPath(): string;

  public function setPath(string $path): void;
}
