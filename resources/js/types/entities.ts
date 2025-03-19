import { User } from '.';

export interface Permissions {
    list: boolean;
    create: boolean;
    view: boolean;
    update: boolean;
    delete: boolean;
}

export interface Club {
    id: number;
    name: string;
    balance: number;
    user_id: number;
    user: User;
    base_fee: number;
    initial_balance: number;
    players_count?: number;
    created_at: string;
    updated_at: string;
    owner: {
        id: number;
        name: string;
    };
    can: ItemPermissions;
}

export interface Role {
    id: number;
    name: string;
    is_base_fee_active: boolean;
    scope: string;
    permissions: Record<string, Permissions>;
    created_at: string;
    updated_at: string;
    can: ItemPermissions;
}

export interface Player {
    id: number;
    club_id: number;
    name: string;
    active: boolean;
    initial_balance: number;
    user_id?: number;
    user?: { name: string; email: string };
    sex: number;
    balance: number;
    created_at: string;
    updated_at: string;

    role: {
        id: number;
        name: string;
        title: string;
    };
    can: ItemPermissions;
}

export interface FeeType {
    id: number;
    club_id: number;
    name: string;
    description: string;
    amount: number;
    position: number;
    latest_version: FeeTypeVersion;
    created_at: string;
    updated_at: string;
    can: ItemPermissions;
}

export interface FeeTypeVersion {
    id: number;
    fee_type_id: number;
    name: string;
    description: string;
    amount: number;
}

export interface Matchday {
    id: number;
    club_id: number;
    date: string;
    notes: string;
    players_count?: number;
    created_at: string;
    updated_at: string;
    can: ItemPermissions;
}

export interface FeeEntry {
    id: number;
    matchday_id: number;
    player_id: number;
    fee_type_version_id: number;
    fee_type_version: FeeTypeVersion;
    amount: number;
    created_at: string;
    updated_at: string;
    can: ItemPermissions;
}

export interface Transaction {
    id: number;
    club_id: number;
    player_id?: number;
    player?: { id: number; name: string };
    matchday_id?: number;
    matchday?: { id: number; date: string };
    fee_entry?: { id: number; fee_type_version_id: number; fee_type_version: { id: number; name: string } };
    fee_entry_id?: number;
    type: number;
    amount: number;
    date: string;
    notes?: string;
    created_at: string;
    updated_at: string;
    can: ItemPermissions;
}

export const enum TransactionType {
    BASE_FEE = 1,
    FEE,
    PAYMENT,
    TIP,
    EXPENSE,
}
export interface CompetitionType {
    id: number;
    club_id: number;
    name: string;
    type: number;
    description: string;
    is_sex_specific: boolean;
    position: number;
    created_at: string;
    updated_at: string;
    can: ItemPermissions;
}

export interface CompetitionEntry {
    id: number;
    matchday_id: number;
    player_id: number;
    competition_type_id: number;
    amount: number;
    can: ItemPermissions;
}

export interface ItemPermissions {
    view: boolean;
    delete: boolean;
    update: boolean;
}

export interface TDataWithPermissions {
    id: number;
    can: ItemPermissions;
}

export interface PlayerStatistics {
    fees: FeeStatistics[];
    competitions: CompetitionStatistics[];
    transactions: Transaction[];
}

export interface FeeStatistics {
    fee_type_id: number;
    name: string;
    min_value: number;
    max_value: number;
    avg_value: number;
    total_entries: number;
}

export interface CompetitionStatistics {
    competition_type_id: number;
    name: string;
    min_value: number;
    max_value: number;
    avg_value: number;
    total_entries: number;
}
