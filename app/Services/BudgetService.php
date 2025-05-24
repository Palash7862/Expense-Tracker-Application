<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Type\Integer;
use Carbon\Carbon;

class BudgetService
{

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Store a new budget.
     *
     * @param Request $request
     * @return Expense
     */
    public function setBudget(Request $request)
    {
        $data = $request->only([
            'budget',
            'month',
            'year',
        ]);

        if( !isset($data['month']) || empty($data['month']) ){
            $data['month'] = Carbon::now()->month;
        }

        if( !isset($data['year']) || empty($data['year']) ){
            $data['year'] = Carbon::now()->year;
        }

        $expense = Budget::create($data);

        return $expense;
    }

    /**
     * Retrieve a single Expense based on the provided ID.
     *
     * @param int $month
     * @param int $year
     * @return Budget
     */
    public function getBudget($month = false, $year =  false)
    {
        $userId = Auth::user()->id;
        $month  = $month ? $month : Carbon::now()->month;
        $year   = $year ? $year : Carbon::now()->year;

        return Budget::where('user_id', $userId)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->first();
    }

    /**
     * Retrieve a single Expense based on the provided ID.
     *
     * @param int $month
     * @param int $year
     * @return Array
     */
    public function getRemainingBudget($month = false, $year =  false)
    {
        $totalBudget        = 0;
        $totalExpense       = 0;
        $remainingBudget    = 0;

        $userId = Auth::user()->id;
        $month  = $month ? $month : Carbon::now()->month;
        $year   = $year ? $year : Carbon::now()->year;

        $budget = Budget::where('user_id', $userId)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->first();

        if( $budget ){
            $totalBudget = $budget->budget;
        }

        $expenses  = $budget?->expenses?->sum('amount');

        if( $expenses ){
            $totalExpense = $expenses;
        }

        $remainingBudget  = $totalBudget - $totalExpense;

        return [
            'total_budget' => $totalBudget,
            'total_expense' => $totalExpense,
            'remaining_budget' => $remainingBudget
        ];
    }
}
