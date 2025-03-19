<?php

namespace App\Contracts;

use App\Models\FeeEntry;
use App\Models\Transaction;

interface TransactionServiceContract
{
    public function createForFeeEntry(FeeEntry $feeEntry): ?Transaction;

    public function updateForFeeEntry(FeeEntry $feeEntry): bool;

    public function deleteForFeeEntry(FeeEntry $feeEntry): bool;
}
