<nav class="relative w-full flex justify-between items-center px-4 sm:px-8 py-5 bg-black/40 backdrop-blur-sm border-b border-white/5 sticky top-0 z-50">
    <div id="logoArea" class="flex items-center gap-2 cursor-pointer">
        <svg class="w-6 h-6 text-cyan-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2zm0 4.5l6.5 13h-13L12 6.5z"/></svg>
        <span class="text-xl font-bold tracking-wide">BLOXSHOP</span>
    </div>

    {{-- Hamburger --}}
    <button id="navToggle" class="md:hidden p-2 text-gray-400 hover:text-white transition-colors" aria-label="Toggle navigation">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    {{-- Desktop nav + Mobile dropdown --}}
    <div id="navLinks"
        class="hidden md:flex gap-6 text-sm text-gray-400
               max-md:absolute max-md:top-full max-md:left-0 max-md:right-0
               max-md:flex-col max-md:bg-black/95 max-md:backdrop-blur-sm
               max-md:border-b max-md:border-white/5 max-md:p-6 max-md:gap-4 max-md:z-50">
        <a href="#" id="navShopLink" class="hover:text-white transition-colors py-1">Shop</a>
        <a href="#" id="navStatusLink" class="hover:text-white transition-colors py-1">Status</a>
        <a href="#" id="navFounderLink" class="hover:text-white transition-colors py-1">Founder</a>
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="/admin" class="text-[#4da6ff] hover:text-[#66bbff] transition-colors font-medium py-1">Admin Panel</a>
            @endif
        @endauth
        <hr class="border-white/5 my-2 max-md:block hidden">
        <button class="nav-cart-btn-mobile flex items-center gap-2 py-1 text-gray-400 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z"/></svg>
            <span>Cart</span>
            <span class="cart-badge-mobile ml-auto bg-red-600 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full @auth @if(count($cart) > 0) @else hidden @endif @endauth @guest hidden @endguest">{{ auth()->check() ? array_sum(array_column($cart, 'quantity')) : 0 }}</span>
        </button>
        @auth
            <span class="text-sm text-gray-300 font-medium py-1">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full px-5 py-2 text-sm font-medium border border-gray-700 rounded-md hover:bg-white/5 transition-colors">
                    LOGOUT
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="w-full text-center px-5 py-2 text-sm font-medium border border-gray-700 rounded-md hover:bg-white/5 transition-colors">
                LOGIN
            </a>
        @endauth
    </div>

    <div class="flex items-center gap-4 max-md:hidden">
        <button id="navCartBtn" class="relative p-2 border border-gray-700 rounded-md hover:border-gray-500 transition-colors">
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z"/></svg>
            @auth
                @if (count($cart) > 0)
                    <span class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full">{{ array_sum(array_column($cart, 'quantity')) }}</span>
                @endif
            @endauth
        </button>
        @auth
            <span class="text-sm text-gray-300 font-medium">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-5 py-2 text-sm font-medium border border-gray-700 rounded-md hover:bg-white/5 transition-colors">
                    LOGOUT
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-medium border border-gray-700 rounded-md hover:bg-white/5 transition-colors inline-block">
                LOGIN
            </a>
        @endauth
    </div>
</nav>
