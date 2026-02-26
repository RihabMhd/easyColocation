<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use App\Models\Colocation;
use App\Models\Settlement;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;

class ExpenseController extends Controller
{
    public function index(Colocation $colocation)
    {
        $expenses = $colocation->expenses()
            ->with(['payer', 'category'])
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('expenses.index', compact('colocation', 'expenses'));
    }

    public function create(Colocation $colocation)
    {
        $members = $colocation->users;

        $categories = Category::whereNull('colocation_id')
            ->orWhere('colocation_id', $colocation->id)
            ->get();

        return view('expenses.create', compact('colocation', 'members', 'categories'));
    }

    public function store(StoreExpenseRequest $request, Colocation $colocation)
    {
        
        $expense = Expense::create([
            'colocation_id' => $colocation->id,
            'user_id' => auth()->id(),
            'category_id' => Category::firstOrCreate(['name' => $request->category_name, 'colocation_id' => $colocation->id])->id,
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

       
        $members = $colocation->users;
        $count = $members->count();

        if ($count > 1) {
            $splitAmount = $expense->amount / $count;

            foreach ($members as $member) {
               
                if ($member->id != $expense->user_id) {
                    Settlement::create([
                        'expense_id'   => $expense->id,
                        'colocation_id' => $colocation->id,
                        'debtor_id'    => $member->id,   
                        'creditor_id'  => $expense->user_id, 
                        'amount'       => $splitAmount,
                        'is_paid'      => false,
                    ]);
                }
            }
        }

        return redirect()->route('expenses.index', $colocation->id)->with('success', 'Dépense et partages enregistrés !');
    }

    public function edit(Colocation $colocation, Expense $expense)
    {
        $members = $colocation->users;
        $categories = Category::whereNull('colocation_id')
            ->orWhere('colocation_id', $colocation->id)
            ->get();

        return view('expenses.edit', compact('colocation', 'expense', 'members', 'categories'));
    }

    public function update(UpdateExpenseRequest $request, Colocation $colocation, Expense $expense)
    {

        $category = Category::firstOrCreate([
            'name' => $request->category_name,
            'colocation_id' => $colocation->id,
        ]);


        $expense->update([
            'user_id' => $request->user_id,
            'category_id' => $category->id,
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('expenses.index', $colocation)
            ->with('success', 'Dépense mise à jour !');
    }
}
