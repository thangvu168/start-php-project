<?php

class UserRepository extends Repository
{
  protected string $table = 'users';
  protected array $allowedColumns = [
    'id',
    'username',
    'first_name',
    'last_name',
    'email',
    'password',
    'avatar',
    'phone',
    'created_at',
    'updated_at',
  ];
}
