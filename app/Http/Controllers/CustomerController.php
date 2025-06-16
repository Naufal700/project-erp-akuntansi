<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport; // pastikan sudah buat export ini
use App\Imports\CustomerImport; // pastikan sudah buat import ini

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Customer::query();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $customers = $query->orderBy('nama')->paginate(10);
        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Customer::create($validated);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        return view('customer.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $customer->update($validated);

        return redirect()->route('customer.index')->with('success', 'Data customer berhasil diupdate.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customer.index')->with('success', 'Data customer berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new CustomerImport, $request->file('file_excel'));

        return redirect()->route('customer.index')->with('success', 'Data customer berhasil diimport.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new CustomerExport, 'template_customer.xlsx');
    }
}
