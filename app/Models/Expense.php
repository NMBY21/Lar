<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'expense_type_id', 'amount', 'date', 'from_bank_id',
        'from_account_id', 'to_bank_id', 'to_account',
        'payment_type', 'file_path', 'remark'
    ];

    public function expenseType() {
        return $this->belongsTo(ExpenseType::class);
    }

    public function fromBank() {
        return $this->belongsTo(Bank::class, 'from_bank_id');
    }

    public function fromAccount() {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toBank() {
        return $this->belongsTo(Bank::class, 'to_bank_id');
    }
    
}
