<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Branch::withCount(['accounts', 'employees']);

        // Search by branch name
        if ($request->has('search') && $request->search != '') {
            $query->where('branch_name', 'like', '%' . $request->search . '%');
        }

        $branches = $query->paginate(15);

        return view('branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_name' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'required|string|max:15'
        ]);

        Branch::create($validated);

        return redirect()->route('branches.index')
            ->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Branch $branch)
    {
        // Build accounts query with filtering
        $accountsQuery = $branch->accounts()->with('customer');
        if ($request->has('account_type') && $request->account_type != '') {
            $accountsQuery->where('account_type', $request->account_type);
        }
        $filteredAccounts = $accountsQuery->get();

        // Build employees query with filtering
        $employeesQuery = $branch->employees();
        if ($request->has('employee_position') && $request->employee_position != '') {
            $employeesQuery->where('position', $request->employee_position);
        }
        $filteredEmployees = $employeesQuery->get();

        // Get unique account types and employee positions for filter dropdowns
        $accountTypes = $branch->accounts()->distinct()->pluck('account_type');
        $employeePositions = $branch->employees()->distinct()->pluck('position');

        return view('branches.show', compact('branch', 'filteredAccounts', 'filteredEmployees', 'accountTypes', 'employeePositions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'branch_name' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'required|string|max:15'
        ]);

        $branch->update($validated);

        return redirect()->route('branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('branches.index')
            ->with('success', 'Branch deleted successfully.');
    }
}
