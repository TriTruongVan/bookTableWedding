<?php

namespace App\Enums;

enum UserRoles: int
{
    case Admin = 0;
    case Customer = 7;
    case ReportViewer = 8;

    public function is(UserRoles $role): bool
    {
        return $this->value === $role->value;
    }

    public function getLabel(): string
    {
        return match ($this) {
            UserRoles::Admin => 'admin',
            UserRoles::Customer => 'customer',
            UserRoles::ReportViewer => 'ReportViewer',
        };
    }
}
