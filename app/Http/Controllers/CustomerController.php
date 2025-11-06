<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\CustomersDataTable;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(CustomersDataTable $dataTable)
    {
        return $dataTable->render('customers.index');        
    }

    public function create()
    {
        return view('customers.add-edit');    
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'=>'required|string|max:255',
            'last_name'=>'nullable|string|max:255',
            'email'=>'nullable|email|max:255|unique:customers,email',
            'user_type'=>'required|string|max:255',
            'city'=>'required|string|max:255',
            'state'=>'required|string|max:255',
            'state_code'=>'required|string|max:10',
            'address'=>'required|string|max:255',
            'GSTIN'=>'required|string|max:15',
            'business_name'=>'required|string|max:255',
            'bank_name'=>'required|string|max:255',
            'bank_account_no'=>'required|string|max:20',
            'ifsc_code'=>'required|string|max:11',
        ]) ;  
        Customer::create($validated);
        return redirect()->route('customers.index')->with('success', 'Customer Added Successfully');
    }

    public function show(Customer $customer)
    {        
        if (request()->ajax()) {
            return response()->json($customer);
        }

        return view('users.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.add-edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name'=>'required|string|max:255',
            'last_name'=>'nullable|string|max:255',
            'email'=>'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'user_type'=>'required|string|max:255',
            'city'=>'required|string|max:255',
            'state'=>'required|string|max:255',
            'state_code'=>'required|string|max:10',
            'address'=>'required|string|max:255',
            'GSTIN'=>'required|string|max:15',
            'business_name'=>'required|string|max:255',
            'bank_name'=>'required|string|max:255',
            'bank_account_no'=>'required|string|max:20',
            'ifsc_code'=>'required|string|max:11',
        ]);
        $customer->update($validated);
        return redirect()->route('customers.index', $customer->id)->with('success', 'Customer Updated Successfully');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer Deleted Successfully');
    }

    public function getCustomerDetails($id)
    {
        $customer = Customer::findOrFail($id);

        return response()->json([
            'address' => $customer->address,
        ]);
    }

}
