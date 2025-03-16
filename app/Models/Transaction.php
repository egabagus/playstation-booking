<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $lastest = self::orderBy('trx_id', 'desc')
                ->first();
            $code = 'TRX' . date('Y') . date('m');

            if ($lastest) {
                $lastNumber = (int) substr($lastest->trx_id, strlen($code));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $itemNumber = $code . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
            $model->trx_id = $itemNumber;
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailTransaction::class);
    }
}
