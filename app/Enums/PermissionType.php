<?php

namespace App\Enums;

enum PermissionType: string
{
    case LIST = 'list';
    case VIEW = 'view';
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
