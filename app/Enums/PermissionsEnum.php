<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    case LIST_CLUB = 'list.Club';
    case VIEW_CLUB = 'view.Club';
    case CREATE_CLUB = 'create.Club';
    case UPDATE_CLUB = 'update.Club';
    case DELETE_CLUB = 'delete.Club';
    case LIST_COMPETITION_TYPE = 'list.CompetitionType';
    case VIEW_COMPETITION_TYPE = 'view.CompetitionType';
    case CREATE_COMPETITION_TYPE = 'create.CompetitionType';
    case UPDATE_COMPETITION_TYPE = 'update.CompetitionType';
    case DELETE_COMPETITION_TYPE = 'delete.CompetitionType';
    case LIST_FEE_TYPE = 'list.FeeType';
    case VIEW_FEE_TYPE = 'view.FeeType';
    case CREATE_FEE_TYPE = 'create.FeeType';
    case UPDATE_FEE_TYPE = 'update.FeeType';
    case DELETE_FEE_TYPE = 'delete.FeeType';
    case LIST_MATCHDAY = 'list.Matchday';
    case VIEW_MATCHDAY = 'view.Matchday';
    case CREATE_MATCHDAY = 'create.Matchday';
    case UPDATE_MATCHDAY = 'update.Matchday';
    case DELETE_MATCHDAY = 'delete.Matchday';
    case LIST_PLAYER = 'list.Player';
    case VIEW_PLAYER = 'view.Player';
    case CREATE_PLAYER = 'create.Player';
    case UPDATE_PLAYER = 'update.Player';
    case DELETE_PLAYER = 'delete.Player';
    case LIST_ROLE = 'list.Role';
    case VIEW_ROLE = 'view.Role';
    case CREATE_ROLE = 'create.Role';
    case UPDATE_ROLE = 'update.Role';
    case DELETE_ROLE = 'delete.Role';
    case LIST_TRANSACTION = 'list.Transaction';
    case VIEW_TRANSACTION = 'view.Transaction';
    case CREATE_TRANSACTION = 'create.Transaction';
    case UPDATE_TRANSACTION = 'update.Transaction';
    case DELETE_TRANSACTION = 'delete.Transaction';
}
