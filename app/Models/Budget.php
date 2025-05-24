<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget',
        'month',
        'year'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = Auth::user()->id;
        });
    }

    /**
     * Get the  expense of the budget
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'budget_id', 'id');
    }

}
