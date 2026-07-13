document.addEventListener('DOMContentLoaded', function () {
    const isAuth = window.App.isAuth;
    const { urls, csrf } = window.App;

    if (window.App.flash) {
        showToast(window.App.flash.message, window.App.flash.type);
    }

    // ── Hamburger Toggle ──
    const navToggle = document.getElementById('navToggle');
    const navLinks = document.getElementById('navLinks');
    if (navToggle && navLinks) {
        navToggle.addEventListener('click', function () {
            navLinks.classList.toggle('hidden');
            navLinks.classList.toggle('flex');
        });
        document.addEventListener('click', function (e) {
            const isMobile = window.matchMedia('(max-width: 767px)').matches;
            if (!isMobile) return;
            if (!navLinks.classList.contains('flex')) return;
            if (navLinks.contains(e.target) || navToggle.contains(e.target)) return;
            navLinks.classList.add('hidden');
            navLinks.classList.remove('flex');
        });
    }

    // ── Scroll to Products ──
    const productGrid = document.getElementById('productGrid');
    const viewProductsBtn = document.getElementById('viewProductsBtn');
    if (viewProductsBtn) {
        viewProductsBtn.addEventListener('click', function (e) {
            if (e) e.preventDefault();
            if (productGrid) productGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    // ── Shop / Founder Toggle ──
    const navShopLink = document.getElementById('navShopLink');
    const navFounderLink = document.getElementById('navFounderLink');
    const shopSection = document.getElementById('shopSection');
    const founderSection = document.getElementById('founderSection');

    if (navShopLink) {
        navShopLink.addEventListener('click', function (e) {
            e.preventDefault();
            navLinks.classList.add('hidden');
            navLinks.classList.remove('flex');
            if (shopSection && founderSection) {
                shopSection.classList.remove('hidden');
                founderSection.classList.add('hidden');
            }
            if (productGrid) productGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    if (navFounderLink && shopSection && founderSection) {
        navFounderLink.addEventListener('click', function (e) {
            e.preventDefault();
            navLinks.classList.add('hidden');
            navLinks.classList.remove('flex');
            shopSection.classList.add('hidden');
            founderSection.classList.remove('hidden');
        });
    }

    // ── Hidden Admin (double-click logo) ──
    const logoArea = document.getElementById('logoArea');
    if (logoArea) {
        logoArea.addEventListener('dblclick', function () {
            window.location.href = '/admin';
        });
    }

    // ── Scroll to Stats ──
    const statsSection = document.getElementById('statsSection');
    const navStatusLink = document.getElementById('navStatusLink');
    if (navStatusLink && statsSection) {
        navStatusLink.addEventListener('click', function (e) {
            e.preventDefault();
            navLinks.classList.add('hidden');
            navLinks.classList.remove('flex');
            statsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    // ── Filter Tags ──
    const filterBtns = document.querySelectorAll('.filter-btn');
    const products = document.querySelectorAll('#productGrid > [data-category]');
    const emptyFilterState = document.getElementById('emptyFilterState');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            filterBtns.forEach(b => {
                b.classList.remove('text-[#4da6ff]', 'border-[#4da6ff]', 'shadow-[0_0_10px_rgba(77,166,255,0.2)]');
                b.classList.add('text-gray-400', 'border-gray-800');
            });

            this.classList.remove('text-gray-400', 'border-gray-800');
            this.classList.add('text-[#4da6ff]', 'border-[#4da6ff]', 'shadow-[0_0_10px_rgba(77,166,255,0.2)]');

            const filter = this.dataset.filter;
            let visible = 0;

            products.forEach(product => {
                if (filter === 'all' || product.dataset.category === filter) {
                    product.classList.remove('hidden');
                    visible++;
                } else {
                    product.classList.add('hidden');
                }
            });

            if (emptyFilterState) {
                emptyFilterState.classList.toggle('hidden', visible > 0);
            }
        });
    });
});
