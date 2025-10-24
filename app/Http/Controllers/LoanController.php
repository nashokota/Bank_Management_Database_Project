<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Customer;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Loan::with('customer');

        // Filter by loan type
        if ($request->has('loan_type') && $request->loan_type != '') {
            $query->where('loan_type', $request->loan_type);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by customer
        if ($request->has('customer_id') && $request->customer_id != '') {
            $query->where('customer_id', $request->customer_id);
        }

        // Filter by loan amount range
        if ($request->has('min_amount') && $request->min_amount != '') {
            $query->where('loan_amount', '>=', $request->min_amount);
        }
        if ($request->has('max_amount') && $request->max_amount != '') {
            $query->where('loan_amount', '<=', $request->max_amount);
        }

        $loans = $query->paginate(15);
        $customers = Customer::all();

        return view('loans.index', compact('loans', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        return view('loans.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'loan_type' => 'required|in:home,car,education,personal',
            'loan_amount' => 'required|numeric|min:1000',
            'interest_rate' => 'required|numeric|min:0|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:ongoing,closed,defaulted'
        ]);

        Loan::create($validated);

        return redirect()->route('loans.index')
            ->with('success', 'Loan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load('customer');
        return view('loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan)
    {
        $customers = Customer::all();
        return view('loans.edit', compact('loan', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'loan_type' => 'required|in:home,car,education,personal',
            'loan_amount' => 'required|numeric|min:1000',
            'interest_rate' => 'required|numeric|min:0|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:ongoing,closed,defaulted'
        ]);

        $loan->update($validated);

        return redirect()->route('loans.index')
            ->with('success', 'Loan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        $loan->delete();

        return redirect()->route('loans.index')
            ->with('success', 'Loan deleted successfully.');
    }
}
