<?php

namespace App\Dto;

class TagReadDto
{
    public string $name;
    public int $id;

    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }
}
