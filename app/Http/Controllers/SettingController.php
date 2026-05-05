<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = $this->ensureDefaultSettings()
            ->groupBy('group');

        $users = User::query()->orderBy('name')->get();
        $paymentMethodOptions = $this->paymentMethodOptions();

        return view('settings.index', compact('settings', 'users', 'paymentMethodOptions'));
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        $this->ensureDefaultSettings();

        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'business_address' => ['required', 'string'],
            'business_contact' => ['required', 'string', 'max:255'],
            'company_logo' => ['nullable', 'image', 'max:3072'],
            'remove_company_logo' => ['nullable', Rule::in(['0', '1'])],
        ]);

        $currentLogoPath = Setting::getValue('company_logo_path');
        $logoPath = $currentLogoPath;

        if ($request->boolean('remove_company_logo')) {
            $this->deleteLogo($currentLogoPath);
            $logoPath = null;
        }

        if ($request->hasFile('company_logo')) {
            $this->deleteLogo($currentLogoPath);
            $logoPath = $request->file('company_logo')->store('settings', 'public');
        }

        $this->persistSettings([
            'business_name' => $validated['business_name'],
            'business_address' => $validated['business_address'],
            'business_contact' => $validated['business_contact'],
            'company_logo_path' => $logoPath,
        ]);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Pengaturan umum berhasil diperbarui.');
    }

    public function updatePayment(Request $request): RedirectResponse
    {
        $this->ensureDefaultSettings();

        $validated = $request->validate([
            'tax_percent' => ['required', 'numeric', 'min:0'],
            'service_charge_percent' => ['required', 'numeric', 'min:0'],
            'payment_methods' => ['required', 'array', 'min:1'],
            'payment_methods.*' => ['required', Rule::in(array_keys($this->paymentMethodOptions()))],
        ]);

        $this->persistSettings([
            'tax_percent' => (string) $validated['tax_percent'],
            'service_charge_percent' => (string) $validated['service_charge_percent'],
            'payment_methods' => json_encode(array_values($validated['payment_methods'])),
        ]);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Pengaturan pembayaran berhasil diperbarui.');
    }

    public function updateOrder(Request $request): RedirectResponse
    {
        $this->ensureDefaultSettings();

        $validated = $request->validate([
            'order_number_format' => ['required', 'string', 'max:255'],
            'default_payment_status' => ['required', Rule::in(['pending', 'paid'])],
        ]);

        $this->persistSettings([
            'order_number_format' => $validated['order_number_format'],
            'default_payment_status' => $validated['default_payment_status'],
        ]);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Pengaturan pesanan berhasil diperbarui.');
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'kasir'])],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('settings.index')
            ->with('success', 'User baru berhasil ditambahkan.');
    }

    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'kasir'])],
        ]);

        $user->update([
            'role' => $validated['role'],
        ]);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Role user berhasil diperbarui.');
    }

    protected function ensureDefaultSettings()
    {
        $defaults = [
            ['group' => 'general', 'key' => 'business_name', 'label' => 'Nama Usaha', 'value' => 'Sedjati Coffee', 'type' => 'text', 'sort_order' => 1],
            ['group' => 'general', 'key' => 'business_address', 'label' => 'Alamat', 'value' => 'Jl. Sedjati Coffee No. 8, Yogyakarta', 'type' => 'textarea', 'sort_order' => 2],
            ['group' => 'general', 'key' => 'business_contact', 'label' => 'Kontak', 'value' => '0812-3456-7890', 'type' => 'text', 'sort_order' => 3],
            ['group' => 'general', 'key' => 'company_logo_path', 'label' => 'Logo Perusahaan', 'value' => null, 'type' => 'image', 'sort_order' => 4],
            ['group' => 'payment', 'key' => 'tax_percent', 'label' => 'Pajak (%)', 'value' => '11', 'type' => 'number', 'sort_order' => 1],
            ['group' => 'payment', 'key' => 'service_charge_percent', 'label' => 'Service Charge (%)', 'value' => '5', 'type' => 'number', 'sort_order' => 2],
            ['group' => 'payment', 'key' => 'payment_methods', 'label' => 'Metode Pembayaran', 'value' => json_encode(['cash', 'qris', 'debit card']), 'type' => 'json', 'sort_order' => 3],
            ['group' => 'order', 'key' => 'order_number_format', 'label' => 'Format Nomor Pesanan', 'value' => 'SDJ-{date}-{sequence}', 'type' => 'text', 'sort_order' => 1],
            ['group' => 'order', 'key' => 'default_payment_status', 'label' => 'Status Default', 'value' => 'pending', 'type' => 'select', 'sort_order' => 2],
        ];

        foreach ($defaults as $default) {
            Setting::query()->firstOrCreate(
                ['key' => $default['key']],
                $default
            );
        }

        return Setting::query()
            ->orderBy('group')
            ->orderBy('sort_order')
            ->get();
    }

    protected function persistSettings(array $values): void
    {
        foreach ($values as $key => $value) {
            Setting::query()
                ->where('key', $key)
                ->update(['value' => $value]);
        }
    }

    protected function deleteLogo(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function paymentMethodOptions(): array
    {
        return [
            'cash' => 'Tunai',
            'qris' => 'QRIS',
            'debit card' => 'Kartu Debit',
        ];
    }
}
