<?php

namespace App\Dto;

class UserReadDto
{
    public int $id;
    public string $username;
    public array $roles;

    public function __construct(int $id, string $username, array $roles) {
        $this->id = $id;
        $this->username = $username;
        $this->roles[] = $roles;
    }
}
