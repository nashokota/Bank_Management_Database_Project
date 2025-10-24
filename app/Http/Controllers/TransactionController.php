<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['account.customer', 'account.branch']);

        // Filter by transaction type
        if ($request->has('transaction_type') && $request->transaction_type != '') {
            $query->where('transaction_type', $request->transaction_type);
        }

        // Filter by account
        if ($request->has('account_id') && $request->account_id != '') {
            $query->where('account_id', $request->account_id);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('date_time', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('date_time', '<=', $request->end_date . ' 23:59:59');
        }

        // Filter by amount range
        if ($request->has('min_amount') && $request->min_amount != '') {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->has('max_amount') && $request->max_amount != '') {
            $query->where('amount', '<=', $request->max_amount);
        }

        $transactions = $query->orderBy('date_time', 'desc')->paginate(15);
        $accounts = Account::with('customer')->get();

        return view('transactions.index', compact('transactions', 'accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Account::with(['customer', 'branch'])->where('status', 'active')->get();
        return view('transactions.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'transaction_type' => 'required|in:deposit,withdraw,transfer,loan_payment',
            'amount' => 'required|numeric|min:0.01',
            'date_time' => 'nullable|date',
            'description' => 'nullable|string|max:255'
        ]);

        DB::transaction(function () use ($validated, $request) {
            $account = Account::find($validated['account_id']);
            
            // Calculate new balance based on transaction type
            $newBalance = $account->balance;
            if (in_array($validated['transaction_type'], ['deposit', 'loan_payment'])) {
                $newBalance += $validated['amount'];
            } else {
                $newBalance -= $validated['amount'];
            }

            // Check if sufficient funds for withdrawal/transfer
            if ($newBalance < 0 && in_array($validated['transaction_type'], ['withdraw', 'transfer'])) {
                throw new \Exception('Insufficient funds');
            }

            // Create transaction
            Transaction::create([
                'account_id' => $validated['account_id'],
                'transaction_type' => $validated['transaction_type'],
                'amount' => $validated['amount'],
                'date_time' => $request->has('date_time') ? $request->date_time : now(),
                'balance_after_transaction' => $newBalance,
                'description' => $validated['description']
            ]);

            // Update account balance
            $account->update(['balance' => $newBalance]);
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction completed successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['account.customer', 'account.branch']);
        
        // Get related transactions from the same account (recent 5)
        $relatedTransactions = Transaction::where('account_id', $transaction->account_id)
            ->where('id', '!=', $transaction->id)
            ->orderBy('date_time', 'desc')
            ->limit(5)
            ->get();
        
        return view('transactions.show', compact('transaction', 'relatedTransactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $accounts = Account::with(['customer', 'branch'])->where('status', 'active')->get();
        return view('transactions.edit', compact('transaction', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'transaction_type' => 'required|in:deposit,withdraw,transfer,loan_payment',
            'amount' => 'required|numeric|min:0.01',
            'date_time' => 'required|date',
            'description' => 'nullable|string|max:255'
        ]);

        DB::transaction(function () use ($validated, $transaction) {
            $account = $transaction->account;
            
            // Calculate balance adjustment
            $oldEffect = in_array($transaction->transaction_type, ['deposit', 'loan_payment']) 
                ? $transaction->amount : -$transaction->amount;
            $newEffect = in_array($validated['transaction_type'], ['deposit', 'loan_payment']) 
                ? $validated['amount'] : -$validated['amount'];
            
            $balanceAdjustment = $newEffect - $oldEffect;
            $newAccountBalance = $account->balance + $balanceAdjustment;
            $newTransactionBalance = $newAccountBalance;
            
            // Check for sufficient funds
            if ($newAccountBalance < 0) {
                throw new \Exception('Transaction would result in insufficient funds');
            }
            
            // Update transaction
            $transaction->update([
                'transaction_type' => $validated['transaction_type'],
                'amount' => $validated['amount'],
                'date_time' => $validated['date_time'],
                'balance_after_transaction' => $newTransactionBalance,
                'description' => $validated['description']
            ]);
            
            // Update account balance
            $account->update(['balance' => $newAccountBalance]);
        });

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            $account = $transaction->account;
            
            // Reverse the transaction effect on balance
            $transactionEffect = in_array($transaction->transaction_type, ['deposit', 'loan_payment']) 
                ? -$transaction->amount : $transaction->amount;
            
            $newBalance = $account->balance + $transactionEffect;
            
            // Check for sufficient funds after reversal
            if ($newBalance < 0) {
                throw new \Exception('Cannot delete transaction: would result in negative balance');
            }
            
            // Update account balance
            $account->update(['balance' => $newBalance]);
            
            // Delete transaction
            $transaction->delete();
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully and balance adjusted.');
    }
}
