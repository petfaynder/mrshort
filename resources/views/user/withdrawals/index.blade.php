<x-user-dashboard-layout>
    <div x-data="{ paymentMethod: '' }">
        <div class="mx-auto max-w-7xl">
            <div class="flex flex-wrap justify-between gap-3 mb-8">
                <div class="flex min-w-72 flex-col gap-2">
                    <h1 class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Para Çekme Sayfası</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">Kazançlarınızı yönetin ve para çekme talebinde bulunun.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                <div class="flex flex-col justify-start rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-white/10">
                    <div class="flex w-full flex-col items-start justify-center gap-2">
                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                            <span class="material-symbols-outlined text-base">account_balance_wallet</span>
                            <p class="text-sm font-normal leading-normal">Available Balance</p>
                        </div>
                        <p class="text-gray-900 dark:text-white text-4xl font-bold leading-tight tracking-[-0.015em]">$845.50</p>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Ready for Withdrawal</p>
                    </div>
                </div>
                <div class="flex flex-col justify-start rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-white/10">
                    <div class="flex w-full flex-col items-start justify-center gap-2">
                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                            <span class="material-symbols-outlined text-base">history</span>
                            <p class="text-sm font-normal leading-normal">Bugüne kadar toplam çekilen tutar</p>
                        </div>
                        <p class="text-gray-900 dark:text-white text-4xl font-bold leading-tight tracking-[-0.015em]">$3,450.00</p>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">All time earnings withdrawn</p>
                    </div>
                </div>
                <div class="flex flex-col justify-start rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-white/10">
                    <div class="flex w-full flex-col items-start justify-center gap-3">
                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                            <span class="material-symbols-outlined text-base">info</span>
                            <p class="text-sm font-normal leading-normal">Withdrawal Limits & Fees</p>
                        </div>
                        <div class="text-sm text-gray-700 dark:text-gray-300 space-y-1.5">
                            <p>Min. Withdrawal: <span class="font-medium text-gray-900 dark:text-white">$5.00</span></p>
                            <p>PayPal Fee: <span class="font-medium text-gray-900 dark:text-white">2%</span></p>
                            <p>Bank Transfer Fee: <span class="font-medium text-gray-900 dark:text-white">$1.00</span></p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-start rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-white/10">
                    <div class="flex w-full flex-col items-start justify-center gap-2">
                        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                            <span class="material-symbols-outlined text-base">autorenew</span>
                            <p class="text-sm font-normal leading-normal">Recurring Withdrawals</p>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/50 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-300">Active</span>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Next: 1st of month</p>
                        </div>
                        <a class="text-sm text-primary hover:underline mt-1" href="#">Manage Settings</a>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white dark:bg-gray-800/50 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-white/10">
                    <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] pb-5">Yeni Para Çekme Talebi Oluştur</h2>
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                            <label class="flex flex-col">
                                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">Miktar ($)</p>
                                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" placeholder="Miktar girin" value=""/>
                            </label>
                            <label class="flex flex-col">
                                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">Ödeme Yöntemi</p>
                                <select class="form-select flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-14 p-[15px] text-base font-normal leading-normal" id="paymentMethod" x-model="paymentMethod">
                                    <option value="">Seçiniz</option>
                                    <option value="PayPal">PayPal</option>
                                    <option value="Banka Transferi">Banka Transferi</option>
                                </select>
                            </label>
                        </div>
                        <div class="mt-4 p-4 rounded-lg bg-primary/5 dark:bg-primary/10 border border-primary/20" style="display: none;" x-show="paymentMethod === 'PayPal' || paymentMethod === 'Banka Transferi'">
                            <div style="display: none;" x-show="paymentMethod === 'PayPal'">
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">PayPal ile Ödeme</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Ödeme <span class="font-semibold text-gray-800 dark:text-gray-200">admin-user@example.com</span> adresine gönderilecektir. <a class="text-primary hover:underline" href="#">Değiştir</a></p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">Tahmini işlem süresi: 1-2 iş günü.</p>
                            </div>
                            <div style="display: none;" x-show="paymentMethod === 'Banka Transferi'">
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">Banka Transferi ile Ödeme</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Ödeme <span class="font-semibold text-gray-800 dark:text-gray-200">...1234</span> ile biten IBAN'a gönderilecektir. <a class="text-primary hover:underline" href="#">Değiştir</a></p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">Tahmini işlem süresi: 3-5 iş günü.</p>
                            </div>
                        </div>
                        <div class="mt-6" id="paypal-fields" style="display: none;" x-show="paymentMethod === 'PayPal'">
                            <label class="flex flex-col">
                                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">PayPal E-posta Adresi</p>
                                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" placeholder="paypal@example.com" type="email" value=""/>
                            </label>
                        </div>
                        <div class="grid-cols-1 md:grid-cols-2 gap-6 mt-6" id="bank-fields" style="display: none;" x-show="paymentMethod === 'Banka Transferi'">
                            <label class="flex flex-col">
                                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">IBAN</p>
                                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" placeholder="TR00 0000 0000 0000 0000 0000" value=""/>
                            </label>
                            <label class="flex flex-col">
                                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">Hesap Sahibi Adı</p>
                                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder-text-gray-500 p-[15px] text-base font-normal leading-normal" placeholder="Ad Soyad" value=""/>
                            </label>
                            <label class="flex flex-col mt-6 md:mt-0">
                                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">Banka Adı</p>
                                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" placeholder="Banka Adı" value=""/>
                            </label>
                            <label class="flex flex-col mt-6 md:mt-0">
                                <p class="text-gray-900 dark:text-white text-base font-medium leading-normal pb-2">SWIFT/BIC Kodu (Opsiyonel)</p>
                                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-gray-500 p-[15px] text-base font-normal leading-normal" placeholder="SWIFT/BIC" value=""/>
                            </label>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
                                <span class="truncate">Talep Oluştur</span>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="flex flex-col justify-start rounded-xl bg-white dark:bg-gray-800/50 p-6 shadow-sm border border-gray-200 dark:border-white/10">
                    <h3 class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em] mb-4">İstatistikler (Son 30 Gün)</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Toplam Çekilen</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">$420.50</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Bekleyen Talepler</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">$120.50 (1)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Tamamlanan Talepler</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">2</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">İptal Edilen Talepler</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">1</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Ortalama Tutar</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">$125.17</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em]">Para Çekme Geçmişi</h2>
                </div>
                <div class="bg-white dark:bg-gray-800/50 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-white/10 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 !text-xl">search</span>
                            <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-10 placeholder:text-gray-400 dark:placeholder:text-gray-500 pl-10 pr-4 text-sm font-normal leading-normal" placeholder="Talep ID ile ara..." type="text"/>
                        </div>
                        <select class="form-select flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-10 px-3 text-sm font-normal leading-normal">
                            <option value="">Tüm Durumlar</option>
                            <option value="pending">Beklemede</option>
                            <option value="completed">Tamamlandı</option>
                            <option value="cancelled">İptal Edildi</option>
                        </select>
                        <select class="form-select flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-10 px-3 text-sm font-normal leading-normal">
                            <option value="">Tüm Yöntemler</option>
                            <option value="paypal">PayPal</option>
                            <option value="bank">Banka Transferi</option>
                        </select>
                        <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900/50 focus:border-primary h-10 px-3 placeholder:text-gray-400 dark:placeholder:text-gray-500 text-sm font-normal leading-normal" placeholder="Tarih Aralığı" type="date"/>
                        <button class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-200 dark:bg-white/10 text-gray-800 dark:text-white text-sm font-medium leading-normal hover:bg-gray-300 dark:hover:bg-white/20 transition-colors">
                            <span class="truncate">Filtreyi Temizle</span>
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-white/10">
                    <table class="min-w-full text-sm text-left">
                        <thead class="border-b border-gray-200 dark:border-white/10 text-xs text-gray-500 dark:text-gray-400 uppercase">
                            <tr>
                                <th class="px-6 py-4 font-medium" scope="col">Talep ID</th>
                                <th class="px-6 py-4 font-medium" scope="col">Miktar ($)</th>
                                <th class="px-6 py-4 font-medium" scope="col">Ödeme Yöntemi</th>
                                <th class="px-6 py-4 font-medium" scope="col">Talep Tarihi</th>
                                <th class="px-6 py-4 font-medium" scope="col">Durum</th>
                                <th class="px-6 py-4 font-medium text-right" scope="col">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#84521</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">$120.50</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">Banka Transferi</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">15 Kas, 2023</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 dark:bg-yellow-900/50 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:text-yellow-300">Beklemede</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400 text-xs font-medium">İptal Et</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#83994</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">$250.00</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">PayPal</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">26 Eki, 2023</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/50 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-300">Tamamlandı</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#81345</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">$50.00</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">PayPal</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">05 Eyl, 2023</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/50 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-300">İptal Edildi</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a class="text-primary hover:underline text-xs font-medium" href="#">Destek Al</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-user-dashboard-layout>
