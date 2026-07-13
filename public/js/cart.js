document.addEventListener('DOMContentLoaded', function () {
    const isAuth = window.App.isAuth;
    const { urls, csrf } = window.App;

    // ── Helpers ──
    function formatRupiah(n) {
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    function updateNavBadge(count) {
        const navCartBtn = document.getElementById('navCartBtn');
        if (navCartBtn) {
            let badge = navCartBtn.querySelector('span');
            if (count > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full';
                    navCartBtn.appendChild(badge);
                }
                badge.textContent = count;
            } else {
                if (badge) badge.remove();
            }
        }
        document.querySelectorAll('.cart-badge-mobile').forEach(badge => {
            if (count > 0) {
                badge.textContent = count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });
    }

    // ── Cart Sidebar ──
    const cartSidebar = document.getElementById('cartSidebar');
    const cartBackdrop = document.getElementById('cartBackdrop');
    const cartClose = document.getElementById('cartClose');
    const navCartBtn = document.getElementById('navCartBtn');
    const cartItems = document.getElementById('cartItems');
    const cartFooter = document.getElementById('cartFooter');
    const cartTotal = document.getElementById('cartTotal');

    function openCart() {
        if (!isAuth) {
            window.showModal(urls.login);
            return;
        }
        cartSidebar.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeCart() {
        cartSidebar.classList.add('hidden');
        document.body.style.overflow = '';
    }

    if (navCartBtn) navCartBtn.addEventListener('click', openCart);
    document.querySelectorAll('.nav-cart-btn-mobile').forEach(btn => {
        btn.addEventListener('click', function () {
            const nav = document.getElementById('navLinks');
            if (nav) { nav.classList.add('hidden'); nav.classList.remove('flex'); }
            openCart();
        });
    });
    if (cartBackdrop) cartBackdrop.addEventListener('click', closeCart);
    if (cartClose) cartClose.addEventListener('click', closeCart);

    // ── Add to Cart AJAX ──
    const spinnerSvg = '<svg class="w-4 h-4 spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
    const cartIconSvg = '<svg class="w-5 h-5 cart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z"/></svg>';

    document.querySelectorAll('.add-cart-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!isAuth) {
                window.showModal(urls.login);
                return;
            }

            const id = this.dataset.id;
            if (this.disabled) return;
            this.disabled = true;
            this.innerHTML = spinnerSvg;

            fetch(urls.cart + '/' + id, {
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    showToast(data.error, 'error');
                } else {
                    showToast(data.message || 'Item added to cart!', 'success');
                    updateNavBadge(data.count);
                }
            })
            .catch(() => {
                showToast('Failed to add item', 'error');
            })
            .finally(() => {
                this.innerHTML = cartIconSvg;
                this.disabled = false;
            });
        });
    });

    // ── Cart AJAX (quantity + remove) ──
    if (cartItems) {
        cartItems.addEventListener('click', function (e) {
            const target = e.target.closest('button');
            if (!target) return;

            const itemEl = target.closest('.cart-item');
            if (!itemEl) return;

            const id = itemEl.dataset.id;

            // Remove
            if (target.classList.contains('remove-btn')) {
                fetch(urls.cart + '/' + id, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    itemEl.remove();
                    if (cartTotal) cartTotal.textContent = formatRupiah(data.total);
                    updateNavBadge(data.count);
                    if (Object.keys(data.cart).length === 0) {
                        cartItems.innerHTML =
                            '<div class="text-center py-12">' +
                            '<svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z"/></svg>' +
                            '<p class="text-gray-500 text-sm">Your cart is empty</p></div>';
                        if (cartFooter) cartFooter.remove();
                    }
                });
                return;
            }

            // Quantity
            const action = target.classList.contains('inc') ? 'increase' :
                        target.classList.contains('dec') ? 'decrease' : null;
            if (!action) return;

            const qtyBtns = itemEl.querySelectorAll('.qty-btn');
            qtyBtns.forEach(b => b.disabled = true);

            fetch(urls.cart + '/' + id, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ action })
            })
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    showToast(data.error, 'error');
                    return;
                }
                if (data.removed) {
                    itemEl.remove();
                    if (Object.keys(data.cart).length === 0) {
                        cartItems.innerHTML =
                            '<div class="text-center py-12">' +
                            '<svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z"/></svg>' +
                            '<p class="text-gray-500 text-sm">Your cart is empty</p></div>';
                        if (cartFooter) cartFooter.remove();
                    }
                } else {
                    itemEl.querySelector('.qty-text').textContent = data.quantity;
                    itemEl.querySelector('.item-subtotal').textContent = formatRupiah(data.subtotal);
                }
                if (cartTotal) cartTotal.textContent = formatRupiah(data.total);
                updateNavBadge(data.count);
            })
            .catch(() => showToast('Failed to update quantity', 'error'))
            .finally(() => {
                const btns = itemEl?.querySelectorAll('.qty-btn');
                if (btns) btns.forEach(b => b.disabled = false);
            });
        });
    }
});
