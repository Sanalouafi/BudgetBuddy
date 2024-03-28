<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
{
    public function update(User $user, Expense $expense)
    {
        return $user->id === $expense->user_id;
    }

    public function delete(User $user, Expense $expense)
    {
        return $user->id === $expense->user_id;
    }
}
