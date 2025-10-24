<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Branch;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource with filtering.
     */
    public function index(Request $request)
    {
        $query = Account::with(['customer', 'branch']);

        // Filter by account type
        if ($request->has('account_type') && $request->account_type != '') {
            $query->where('account_type', $request->account_type);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by branch
        if ($request->has('branch_id') && $request->branch_id != '') {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by customer
        if ($request->has('customer_id') && $request->customer_id != '') {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by balance range
        if ($request->has('min_balance') && $request->min_balance != '') {
            $query->where('balance', '>=', $request->min_balance);
        }
        if ($request->has('max_balance') && $request->max_balance != '') {
            $query->where('balance', '<=', $request->max_balance);
        }

        $accounts = $query->paginate(15);
        $customers = Customer::all();
        $branches = Branch::all();

        return view('accounts.index', compact('accounts', 'customers', 'branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $branches = Branch::all();
        return view('accounts.create', compact('customers', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'branch_id' => 'required|exists:branches,id',
            'account_type' => 'required|in:savings,current,fixed_deposit,loan',
            'balance' => 'required|numeric|min:0',
            'date_opened' => 'required|date',
            'status' => 'required|in:active,closed'
        ]);

        Account::create($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        $account->load(['customer', 'branch', 'transactions' => function($query) {
            $query->orderBy('date_time', 'desc')->limit(10);
        }]);
        
        return view('accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        $customers = Customer::all();
        $branches = Branch::all();
        return view('accounts.edit', compact('account', 'customers', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'branch_id' => 'required|exists:branches,id',
            'account_type' => 'required|in:savings,current,fixed_deposit,loan',
            'balance' => 'required|numeric|min:0',
            'date_opened' => 'required|date',
            'status' => 'required|in:active,closed'
        ]);

        $account->update($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'Account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return redirect()->route('accounts.index')
            ->with('success', 'Account deleted successfully.');
    }
}
