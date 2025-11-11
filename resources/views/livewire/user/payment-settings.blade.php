<div>
    <form wire:submit.prevent="saveSettings">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <label class="flex flex-col">
                <p class="text-heading-light dark:text-heading-dark text-sm font-medium leading-normal pb-2">Ödeme Yöntemi</p>
                <select wire:model="paymentMethod" id="paymentMethod" class="form-select w-full rounded-lg text-white focus:outline-0 focus:ring-0 border border-[#324d67] bg-[#192633] focus:border-primary h-12 px-4 text-base font-normal leading-normal">
                    <option value="">Seçiniz</option>
                    <option value="paypal">PayPal</option>
                    <option value="bank_transfer">Banka Transferi</option>
                </select>
                @error('paymentMethod') <span class="text-red-500 text-xs italic mt-1">{{ $message }}</span> @enderror
            </label>
            <label class="flex flex-col">
                <p class="text-heading-light dark:text-heading-dark text-sm font-medium leading-normal pb-2">Hesap Detayları (E-posta, IBAN, vb.)</p>
                <input wire:model="paymentAccount" id="paymentAccount" type="text" class="form-input w-full rounded-lg text-white focus:outline-0 focus:ring-0 border border-[#324d67] bg-[#192633] focus:border-primary h-12 px-4 placeholder:text-[#92adc9] text-base font-normal leading-normal" placeholder="Hesap bilgilerinizi girin"/>
                @error('paymentAccount') <span class="text-red-500 text-xs italic mt-1">{{ $message }}</span> @enderror
            </label>
        </div>
        {{-- The save button is now in the parent view (settings.index) to trigger the 2FA modal --}}
    </form>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p>{{ session('message') }}</p>
        </div>
    @endif
</div>
