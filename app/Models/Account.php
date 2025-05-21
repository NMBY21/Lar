<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['bank_id', 'account'];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
