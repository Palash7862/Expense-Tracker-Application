<?php

namespace App\Http\Controllers\Api\Budget;

use App\Http\Controllers\Api\Budget\Requests\CreateOrUpdateBudgetRequest;
use App\Http\Controllers\Api\Budget\Resources\BudgetResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\BudgetService;

class BudgetController extends Controller
{
    protected $budgetService;

    public function __construct(BudgetService $budgetService)
    {
        $this->budgetService = $budgetService;
    }

    /**
     * Store a new Budget.
     *
     * @param Request $request
     * @return Response
     */
    public function set(CreateOrUpdateBudgetRequest $request)
    {
        if( $this->budgetService->getBudget() ){
            return withError('Already have budget for this month');
        }

        $expense = $this->budgetService->setBudget($request);

        // return with success response
        return withSuccess(new BudgetResource($expense), 'Budget created successfully');
    }

    /**
     * Retrieve user remaining budget.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function remainingBudget(Request $request)
    {

        $remainingBudget = $this->budgetService->getRemainingBudget();

        return withSuccess($remainingBudget);
    }
}
