<?php

namespace App\Http\Controllers\Api\Expense\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'amount'        => $this->amount,
            'category'      => $this->category,
            'description'   => $this->description,
            'date'          => $this->date,
        ];
    }
}
