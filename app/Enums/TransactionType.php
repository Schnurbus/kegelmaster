<?php

namespace App\Enums;

enum TransactionType: int
{
    case BASE_FEE = 1;
    case FEE = 2;
    case PAYMENT = 3;
    case TIP = 4;
    case EXPENSE = 5;

    public function label(): string
    {
        return match ($this) {
            self::BASE_FEE => 'Base Fee',
            self::FEE => 'Fee',
            self::PAYMENT => 'Payment',
            self::TIP => 'Tip',
            self::EXPENSE => 'Expense',
        };
    }
}
