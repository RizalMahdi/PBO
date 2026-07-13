<div class="fixed bottom-8 right-8 z-[60]">
    <button id="historyFab" class="relative bg-[#00ffff] text-black p-4 rounded-full shadow-[0_0_15px_rgba(0,255,255,0.5)] hover:scale-110 transition-transform">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </button>
</div>

<div id="historyModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="historyBackdrop"></div>
    <div class="relative bg-[#0a0f18] border border-white/10 rounded-xl max-w-2xl w-full mx-4 shadow-2xl max-h-[80vh] flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-white/10">
            <h3 class="text-lg font-bold text-white">Purchase History</h3>
            <button id="historyClose" class="p-1 text-gray-500 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="overflow-y-auto p-6 space-y-4">
            @auth
                @forelse ($transactions as $transaction)
                    <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-900/40 text-green-400 border border-green-700/40">{{ $transaction->status }}</span>
                        </div>
                        <div class="space-y-2">
                            @foreach ($transaction->items as $item)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-white">{{ $item->nama_item }} <span class="text-gray-500">x{{ $item->quantity }}</span></span>
                                    <span class="text-[#4da6ff] font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-white/5">
                            <span class="text-gray-400 text-sm">Total</span>
                            <span class="text-white font-bold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-500 text-sm">No purchase history yet.</p>
                    </div>
                @endforelse
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m0 0v2m0-2h2m-2 0H10m9.364-7.364A9 9 0 1112 3a9 9 0 017.364 4.636z"/>
                    </svg>
                    <p class="text-gray-500 text-sm">Login to view your purchase history.</p>
                    <a href="{{ route('login') }}" class="mt-4 inline-block px-5 py-2 bg-gradient-to-r from-[#4da6ff] to-blue-500 text-black font-semibold rounded-lg text-sm hover:scale-[1.02] transition-transform shadow-[0_0_12px_rgba(77,166,255,0.25)]">
                        Login Now
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>
