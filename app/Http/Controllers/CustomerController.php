<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:customers,phone',
            'email' => 'nullable|email',
        ]);

        Customer::create($request->all());

        return redirect()->back()->with('success', 'Member berhasil didaftarkan!');
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:customers,phone,' . $customer->id,
        ]);

        $customer->update($request->all());

        return redirect()->back()->with('success', 'Data member diperbarui!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->back()->with('success', 'Member dihapus!');
    }
}
