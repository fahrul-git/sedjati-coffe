<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = Customer::query()
            ->when($request->filled('q'), function (Builder $query) use ($request) {
                $keyword = trim((string) $request->string('q'));

                $query->where(function (Builder $builder) use ($keyword) {
                    $builder
                        ->where('name', 'like', "%{$keyword}%")
                        ->orWhere('phone', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total_customers' => Customer::count(),
            'repeat_customers' => Customer::query()->where('total_transactions', '>=', 2)->count(),
            'total_spending' => Customer::sum('total_spending'),
            'best_spender' => Customer::max('total_spending'),
        ];

        $filters = $request->only(['q']);

        return view('customers.index', compact('customers', 'stats', 'filters'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCustomer($request);

        Customer::create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Data customer berhasil ditambahkan.');
    }

    public function show(Customer $customer): View
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $this->validateCustomer($request, $customer);

        $customer->update($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Data customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Data customer berhasil dihapus.');
    }

    protected function validateCustomer(Request $request, ?Customer $customer = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50', Rule::unique('customers', 'phone')->ignore($customer?->id)],
            'first_purchase_date' => ['nullable', 'date'],
            'total_transactions' => ['required', 'integer', 'min:0'],
            'total_spending' => ['required', 'numeric', 'min:0'],
        ]);
    }
}
