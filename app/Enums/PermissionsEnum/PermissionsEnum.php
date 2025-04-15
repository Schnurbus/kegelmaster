<?php

namespace App\Enums\PermissionsEnum;

enum PermissionsEnum: string
{
    case LISTROLES = 'list.Role';
    case VIEWROLES = 'view.Role';
    case CREATEROLES = 'create.Role';
    case UPDATEROLES = 'update.Role';
    case DELETEROLES = 'delete.Role';
}
