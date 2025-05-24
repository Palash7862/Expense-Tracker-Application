<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExpenseService
{

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieves a list of Expense based on the request parameters.
     *
     * @param Request $request
     * @return collection of Expense
     */
    public function userExpenseList(Request $request)
    {
        $limit  = $request->input('limit', 10);
        $limit  = $limit > 0 ? $limit : 0;
        $userId = Auth::user()->id;

        $expenses = Expense::where('user_id', $userId)->orderBy('id', 'desc');

        //paginate
        $expenses = $expenses->paginate($limit);

        return $expenses;
    }

    /**
     * Store a new Expense.
     *
     * @param Request $request
     * @return Expense
     */
    public function createExpense(Request $request, Budget $budget)
    {
        $data = $request->only([
            'amount',
            'category',
            'description',
            'date',
        ]);

        $expense = Expense::create([
            ...$data,
            'budget_id'=> $budget->id
        ]);

        return $expense;
    }

    /**
     * check budget for new Expense.
     *
     * @param Request $request
     * @param BudgetService $budgetService
     * @return Budget | boolean
     */
    public function checkBudget(Request $request, BudgetService $budgetService)
    {
        $data = Carbon::parse($request->data);

        $budget = $budgetService->getBudget($data->month, $data->year);

        return $budget ? $budget : false;
    }

    /**
     * Retrieve a single Expense based on the provided ID.
     *
     * @param int $id
     * @return Expense
     */
    public function getExpense(String $id)
    {
        return Expense::where('id', $id)->first();
    }

    /**
     * Updates the Expense based on the provided ID.
     *
     * @param Request $request
     * @param String $id
     * @return Expense
     */
    public function expenseUpdate(Request $request, String $id)
    {
        $expense = Expense::where('id', $id)->firstOrFail();

        if (!$expense) {
            return withError('Invalid Expense');
        }

        $data = $request->only([
            'amount',
            'category',
            'description',
            'date',
        ]);

        $expense->update($data);

        return $expense;
    }

    /**
     * delete a single Expense based on the provided ID.
     *
     * @param int $id
     * @return Boolean
     */
    public function expenseDelete($id)
    {
        $userId = Auth::user()->id;
        $expense = Expense::where('user_id', $userId)->where('id', $id)->first();

        return $expense->delete();
    }
}
