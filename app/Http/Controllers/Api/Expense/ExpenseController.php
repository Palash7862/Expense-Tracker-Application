<?php

namespace App\Http\Controllers\Api\Expense;

use App\Http\Controllers\Api\Expense\Requests\CreateOrUpdateExpenseRequest;
use App\Http\Controllers\Api\Expense\Resources\ExpenseResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\ExpenseService;
use App\Services\BudgetService;


class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * Retrieves a list of Expense based on the request parameters.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $expenses = $this->expenseService->userExpenseList($request);

        return withSuccessResourceList(ExpenseResource::collection($expenses));
    }

    /**
     * Store a new Expense.
     *
     * @param Request $request
     * @return Response
     */
    public function create(CreateOrUpdateExpenseRequest $request, BudgetService $budgetService)
    {

        $budget = $this->expenseService->checkBudget($request, $budgetService);

        if( !$budget ){
            return withError('Please first set budget for this date of expense');
        }

        $expense = $this->expenseService->createExpense($request, $budget);

        // return with success response
        return withSuccess(new ExpenseResource($expense), 'Expense created successfully');
    }

    /**
     * Retrieve a single Expense based on the provided ID.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function show(Request $request, String $id)
    {
        $expense = $this->expenseService->getExpense($id);

        if (!$expense) {
            return withError('Invalid Expense');
        }

        return withSuccess(new ExpenseResource($expense));
    }

    /**
     * Updates the Expense based on the provided ID.
     *
     * @param Request $request
     * @param String $id
     * @return Response
     */
    public function update(CreateOrUpdateExpenseRequest $request, String $id)
    {
        if(!$this->expenseService->getExpense($id)){
            return withError('Invalid Expense');
        }

        $expense = $this->expenseService->expenseUpdate($request, $id);

        return withSuccess(new ExpenseResource($expense), 'Expense updated successfully');
    }

    /**
     * delete a single Expense based on the provided ID.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function delete(Request $request, $id)
    {
        $expense = $this->expenseService->expenseDelete($id);

        if (!$expense) {
            return withError('Invalid Expense');
        }

        return withSuccess(message: 'Expense deleted successfully');
    }
}
