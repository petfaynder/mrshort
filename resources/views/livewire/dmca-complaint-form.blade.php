<div>
    {{-- Background Grid --}}
    <div class="fixed inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px] opacity-50 pointer-events-none"></div>
    
    {{-- Spotlight Effect --}}
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[radial-gradient(circle,rgba(0,191,255,0.08),transparent_60%)] blur-[100px] pointer-events-none"></div>

    <div class="relative z-10 max-w-2xl mx-auto px-4 py-16 md:py-24">
        
        @if($submitted)
            {{-- Success State --}}
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-500/10 border border-green-500/30 mb-8">
                    <span class="material-symbols-outlined text-5xl text-green-400">check_circle</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold tracking-tighter mb-4 leading-none">
                    COMPLAINT<br/>
                    <span class="text-gray-600">RECEIVED.</span>
                </h1>
                <p class="text-gray-400 text-lg mb-8 max-w-md mx-auto">
                    Your complaint has been submitted successfully. We will review it as soon as possible.
                </p>
                <a href="/" class="inline-flex items-center gap-2 bg-white text-gray-900 font-bold py-4 px-8 rounded-full hover:bg-gray-200 transition-all duration-300">
                    <span class="material-symbols-outlined">home</span>
                    Home
                </a>
            </div>
        @else
            {{-- Header --}}
            <div class="mb-10">
                <a href="/" class="inline-flex items-center gap-2 text-gray-500 hover:text-white transition-colors mb-8">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    <span class="text-sm">Go Back</span>
                </a>
                
                <div class="inline-flex items-center px-4 py-2 rounded-full border border-red-500/30 bg-red-500/10 mb-6">
                    <span class="material-symbols-outlined text-red-400 mr-2 text-lg">shield</span>
                    <span class="text-sm font-medium text-red-400 tracking-wide">DMCA Notice</span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-bold tracking-tighter mb-4 leading-[0.9]">
                    CONTENT<br/>
                    <span class="text-gray-600">COMPLAINT.</span>
                </h1>
                
                <p class="text-gray-400 text-lg max-w-lg">
                    Fill out the form below to report copyright infringement or inappropriate content.
                </p>
            </div>

            {{-- Form --}}
            <form wire:submit.prevent="submit" class="space-y-5">
                
                @if($errorMessage)
                    <div class="p-4 bg-red-500/10 border border-red-500/30 rounded-2xl text-red-400 text-sm flex items-center gap-3">
                        <span class="material-symbols-outlined">error</span>
                        {{ $errorMessage }}
                    </div>
                @endif

                {{-- Link Info Card --}}
                <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-[#00BFFF] text-lg">link</span>
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-widest">Reported Link</span>
                    </div>
                    <div class="bg-black/30 rounded-xl px-4 py-3 border border-white/5">
                        <code class="text-[#00BFFF] font-mono text-base md:text-lg break-all">{{ url('/' . $linkCode) }}</code>
                    </div>
                </div>

                {{-- Complaint Type --}}
                <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-5 hover:border-white/20 transition-colors">
                    <label for="complaintType" class="block text-xs font-medium text-gray-500 mb-3 uppercase tracking-widest">
                        Complaint Type <span class="text-red-500">*</span>
                    </label>
                    <select 
                        wire:model="complaintType" 
                        id="complaintType"
                        class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3.5 text-white focus:ring-2 focus:ring-[#00BFFF]/50 focus:border-[#00BFFF]/50 transition-all appearance-none cursor-pointer"
                        style="background-image: url('data:image/svg+xml,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 20 20%27%3e%3cpath stroke=%27%236b7280%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%271.5%27 d=%27M6 8l4 4 4-4%27/%3e%3c/svg%3e'); background-position: right 1rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em;"
                    >
                        <option value="">Select...</option>
                        @foreach($complaintTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('complaintType')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Reporter Info Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Name --}}
                    <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-5 hover:border-white/20 transition-colors">
                        <label for="reporterName" class="block text-xs font-medium text-gray-500 mb-3 uppercase tracking-widest">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            wire:model="reporterName" 
                            id="reporterName"
                            class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3.5 text-white placeholder-gray-600 focus:ring-2 focus:ring-[#00BFFF]/50 focus:border-[#00BFFF]/50 transition-all"
                            placeholder="John Doe"
                        >
                        @error('reporterName')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-5 hover:border-white/20 transition-colors">
                        <label for="reporterEmail" class="block text-xs font-medium text-gray-500 mb-3 uppercase tracking-widest">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            wire:model="reporterEmail" 
                            id="reporterEmail"
                            class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3.5 text-white placeholder-gray-600 focus:ring-2 focus:ring-[#00BFFF]/50 focus:border-[#00BFFF]/50 transition-all"
                            placeholder="you@example.com"
                        >
                        @error('reporterEmail')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-5 hover:border-white/20 transition-colors">
                    <label for="description" class="block text-xs font-medium text-gray-500 mb-3 uppercase tracking-widest">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        wire:model="description" 
                        id="description"
                        rows="4"
                        class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3.5 text-white placeholder-gray-600 focus:ring-2 focus:ring-[#00BFFF]/50 focus:border-[#00BFFF]/50 transition-all resize-none"
                        placeholder="Please describe your complaint in detail..."
                    ></textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-600">Minimum 20 characters</p>
                </div>

                {{-- Legal Notice --}}
                <div class="bg-amber-500/5 border border-amber-500/20 rounded-2xl p-5">
                    <div class="flex gap-3">
                        <span class="material-symbols-outlined text-amber-400 flex-shrink-0 text-xl">gavel</span>
                        <div>
                            <p class="font-semibold text-amber-300 text-sm mb-1">Legal Notice</p>
                            <p class="text-gray-400 text-sm leading-relaxed">
                                By submitting this form, you confirm that the information provided is accurate. False DMCA notices may result in legal consequences.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-[#00BFFF] to-[#FF00FF] text-white font-bold py-4 px-8 rounded-full hover:opacity-90 hover:shadow-[0_0_40px_rgba(0,191,255,0.3)] transition-all duration-300 flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove class="flex items-center gap-2">
                        <span class="material-symbols-outlined">send</span>
                        <span>Submit Complaint</span>
                    </span>
                    <span wire:loading class="flex items-center gap-2">
                        <span class="inline-block animate-spin rounded-full h-5 w-5 border-2 border-white/30 border-t-white"></span>
                        <span>Submitting...</span>
                    </span>
                </button>
            </form>

            {{-- Footer --}}
            <div class="text-center mt-12 pt-8 border-t border-white/5">
                <p class="text-gray-600 text-sm">
                    © {{ date('Y') }} {{ config('app.name') }}
                </p>
            </div>
        @endif
    </div>
</div>
