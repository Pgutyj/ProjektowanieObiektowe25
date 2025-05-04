<?php


namespace App\Entity\Enum;


enum UserRole: string
{
    case ROLE_USER = 'ROLE_USER';
    case ROLE_ADMIN = 'ROLE_ADMIN';


    public function label(): string
    {
        return match ($this) {
            UserRole::ROLE_USER => 'ROLE_USER',
            UserRole::ROLE_ADMIN => 'ROLE_ADMIN',
        };
    }
}