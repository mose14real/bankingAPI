<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'account_id',
        'date_time',
        'sender_name',
        'sender_acct',
        'receiver_name',
        'receiver_acct',
        'description',
        'type',
        'currency',
        'amount',
        'opening_balance',
        'closing_balance',
        'reference',
        'status'
    ];

    public function accounts(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public static function findByUuid(string $uuid)
    {
        return self::where('uuid', $uuid)->first();
    }
}
