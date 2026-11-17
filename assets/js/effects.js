/**
 * Nova Shop — Effects & Interactions Engine
 * Các hiệu ứng: scroll reveal, fly-to-cart, toast, progress bar, back-to-top, ripple, lazy load
 */

(function () {
    'use strict';

    /* ═══════════════════════════════════════════════
       1. PAGE LOADING PROGRESS BAR
    ═══════════════════════════════════════════════ */
    const ProgressBar = (() => {
        let bar = null;
        let timer = null;
        let currentWidth = 0;

        function create() {
            if (bar) return;
            bar = document.createElement('div');
            bar.id = 'nprogress-bar';
            document.body.prepend(bar);
        }

        function start() {
            create();
            currentWidth = 0;
            bar.style.width = '0%';
            bar.style.opacity = '1';
            bar.classList.remove('done');
            clearInterval(timer);
            timer = setInterval(() => {
                currentWidth = Math.min(currentWidth + Math.random() * 12, 85);
                bar.style.width = currentWidth + '%';
            }, 200);
        }

        function done() {
            clearInterval(timer);
            if (!bar) return;
            bar.style.width = '100%';
            setTimeout(() => {
                bar.classList.add('done');
                setTimeout(() => { bar.style.width = '0%'; currentWidth = 0; }, 500);
            }, 150);
        }

        return { start, done };
    })();

    // Trigger on page links (not hash, not external)
    document.addEventListener('click', (e) => {
        const a = e.target.closest('a[href]');
        if (!a) return;
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript') ||
            href.startsWith('mailto') || href.startsWith('tel') ||
            a.target === '_blank' || a.hasAttribute('data-bs-toggle')) return;
        try {
            const url = new URL(href, location.href);
            if (url.origin === location.origin) ProgressBar.start();
        } catch {}
    });

    window.addEventListener('load', () => ProgressBar.done());

    /* ═══════════════════════════════════════════════
       2. SCROLL REVEAL
    ═══════════════════════════════════════════════ */
    const initScrollReveal = () => {
        const revealEls = document.querySelectorAll('.reveal');
        if (!revealEls.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        revealEls.forEach(el => observer.observe(el));
    };

    /* ═══════════════════════════════════════════════
       3. TOAST NOTIFICATION SYSTEM
    ═══════════════════════════════════════════════ */
    const Toast = (() => {
        let container = null;

        function ensureContainer() {
            if (container) return;
            container = document.createElement('div');
            container.id = 'toast-container';
            document.body.appendChild(container);
        }

        const icons = {
            success: 'bi-check-circle-fill',
            error:   'bi-x-circle-fill',
            warning: 'bi-exclamation-triangle-fill',
            info:    'bi-info-circle-fill',
            cart:    'bi-bag-check-fill',
        };

        function show(message, type = 'success', duration = 3000) {
            ensureContainer();

            const toast = document.createElement('div');
            toast.className = `ns-toast ${type}`;
            toast.innerHTML = `
                <span class="toast-icon"><i class="bi ${icons[type] || icons.info}" aria-hidden="true"></i></span>
                <span class="toast-message">${message}</span>
                <button class="toast-close" aria-label="Đóng">×</button>
            `;

            const close = () => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            };

            toast.querySelector('.toast-close').addEventListener('click', close);
            container.appendChild(toast);

            // Force reflow then animate in
            requestAnimationFrame(() => {
                requestAnimationFrame(() => toast.classList.add('show'));
            });

            setTimeout(close, duration);
            return toast;
        }

        return { show };
    })();

    // Expose globally
    window.NovaToast = Toast;

    /* ═══════════════════════════════════════════════
       4. FLY-TO-CART ANIMATION
    ═══════════════════════════════════════════════ */
    function flyToCart(originEl, productName) {
        // Find cart icon
        const cartIcon = document.querySelector('.cart-icon-target, .header-cart, .header-action-btn[href*="cart"]');
        if (!cartIcon) {
            return;
        }

        const originRect = originEl.getBoundingClientRect();
        const cartRect = cartIcon.getBoundingClientRect();

        // Create flying element
        const fly = document.createElement('div');
        fly.className = 'fly-item';
        fly.innerHTML = '<i class="bi bi-bag-fill" aria-hidden="true"></i>';
        fly.style.cssText = `
            left: ${originRect.left + originRect.width / 2 - 25}px;
            top:  ${originRect.top + originRect.height / 2 - 25}px;
        `;
        document.body.appendChild(fly);

        // Animate to cart
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                fly.classList.add('flying');
                fly.style.left = (cartRect.left + cartRect.width / 2 - 25) + 'px';
                fly.style.top  = (cartRect.top + cartRect.height / 2 - 25) + 'px';
            });
        });

        // On arrival: pulse the cart icon only (badge + toast are handled by cart.js)
        setTimeout(() => {
            fly.remove();
            cartIcon.classList.add('cart-pulse');
            cartIcon.addEventListener('animationend', () => cartIcon.classList.remove('cart-pulse'), { once: true });
        }, 680);
    }

    // Hook vào tất cả nút "Thêm vào giỏ"
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-add-cart], .btn-add-cart');
        if (!btn) return;

        const card = btn.closest('.product-card');
        const name = card ? (card.dataset.name || card.querySelector('.product-name')?.textContent?.trim() || 'Sản phẩm') : 'Sản phẩm';

        flyToCart(btn, name.substring(0, 30) + (name.length > 30 ? '...' : ''));
    });

    /* ═══════════════════════════════════════════════
       5. BUTTON RIPPLE EFFECT
    ═══════════════════════════════════════════════ */
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-ripple, .btn-buy-now, .btn-search, .btn-add-cart, .btn-checkout');
        if (!btn) return;

        const rect = btn.getBoundingClientRect();
        const ripple = document.createElement('span');
        const size = Math.max(rect.width, rect.height) * 2;
        ripple.className = 'ripple';
        ripple.style.cssText = `
            width: ${size}px;
            height: ${size}px;
            left: ${e.clientX - rect.left - size / 2}px;
            top: ${e.clientY - rect.top - size / 2}px;
        `;
        // Add position: relative to btn if not set
        if (getComputedStyle(btn).position === 'static') btn.style.position = 'relative';
        btn.style.overflow = 'hidden';
        btn.appendChild(ripple);
        ripple.addEventListener('animationend', () => ripple.remove(), { once: true });
    });

    /* ═══════════════════════════════════════════════
       6. BACK TO TOP BUTTON
    ═══════════════════════════════════════════════ */
    const initBackToTop = () => {
        const btn = document.createElement('button');
        btn.id = 'back-to-top';
        btn.setAttribute('aria-label', 'Về đầu trang');
        btn.innerHTML = '<i class="bi bi-arrow-up" aria-hidden="true"></i>';
        btn.title = 'Về đầu trang';
        document.body.appendChild(btn);

        const toggle = () => btn.classList.toggle('visible', window.scrollY > 400);
        window.addEventListener('scroll', toggle, { passive: true });

        btn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    };

    /* ═══════════════════════════════════════════════
       7. LAZY IMAGE LOADING (blur-up)
    ═══════════════════════════════════════════════ */
    const initLazyImages = () => {
        const imgs = document.querySelectorAll('img[data-src], img[loading="lazy"]');
        if (!imgs.length) return;

        // Apply blur class to product images
        document.querySelectorAll('.product-img, .product-detail-img').forEach(img => {
            const markLoaded = () => {
                img.classList.add('loaded');
                const wrap = img.closest('.product-img-wrap');
                if (wrap) wrap.classList.add('img-loaded');
            };

            if (img.complete) {
                markLoaded();
                return;
            }

            img.classList.add('lazy-img');
            img.addEventListener('load', markLoaded, { once: true });
            img.addEventListener('error', markLoaded, { once: true });
        });

        // IntersectionObserver for data-src
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const img = entry.target;
                if (img.dataset.src) {
                    img.classList.add('lazy-img');
                    img.src = img.dataset.src;
                    img.addEventListener('load', () => img.classList.add('loaded'), { once: true });
                    delete img.dataset.src;
                }
                observer.unobserve(img);
            });
        }, { rootMargin: '200px' });

        imgs.forEach(img => observer.observe(img));
    };

    /* ═══════════════════════════════════════════════
       8. COUNTDOWN TIMER — Flip on second change
    ═══════════════════════════════════════════════ */
    const initCountdown = () => {
        const container = document.querySelector('[data-countdown]');
        if (!container) return;

        const hEl = document.getElementById('cd-h');
        const mEl = document.getElementById('cd-m');
        const sEl = document.getElementById('cd-s');
        if (!hEl || !mEl || !sEl) return;

        let prevH = -1;
        let prevM = -1;
        let prevS = -1;

        const flip = (el, value) => {
            el.classList.remove('countdown-flip');
            void el.offsetWidth; // reflow
            el.textContent = String(value).padStart(2, '0');
            el.classList.add('countdown-flip');
        };

        const tick = () => {
            const now = new Date();
            const end = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);
            let diff = Math.max(0, Math.floor((end - now) / 1000));
            const h = Math.floor(diff / 3600);
            diff %= 3600;
            const m = Math.floor(diff / 60);
            const s = diff % 60;

            if (h !== prevH) {
                flip(hEl, h);
                prevH = h;
            }
            if (m !== prevM) {
                flip(mEl, m);
                prevM = m;
            }
            if (s !== prevS) {
                flip(sEl, s);
                prevS = s;
            }
        };

        tick();
        setInterval(tick, 1000);
    };

    /* ═══════════════════════════════════════════════
       9. SKELETON CARDS khi tải trang (shimmer)
    ═══════════════════════════════════════════════ */
    function injectSkeletons(container, count = 4) {
        if (!container) return;
        for (let i = 0; i < count; i++) {
            const col = document.createElement('div');
            col.className = 'col skeleton-col';
            col.innerHTML = `
                <div class="skeleton-card">
                    <div class="skeleton skeleton-img"></div>
                    <div class="skeleton skeleton-title"></div>
                    <div class="skeleton skeleton-title-short"></div>
                    <div class="skeleton skeleton-price"></div>
                </div>
            `;
            container.appendChild(col);
        }
    }

    /* ═══════════════════════════════════════════════
       10. WISHLIST BUTTON (UI only — không cần backend)
    ═══════════════════════════════════════════════ */
    const initWishlist = () => {
        // Inject wishlist button vào mỗi product card
        document.querySelectorAll('.product-card').forEach(card => {
            if (card.querySelector('.btn-wishlist')) return;
            const btn = document.createElement('button');
            btn.className = 'btn-wishlist';
            btn.setAttribute('aria-label', 'Yêu thích');
            btn.innerHTML = '<i class="bi bi-heart" aria-hidden="true"></i>';
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const isActive = btn.classList.toggle('active');
                btn.innerHTML = isActive
                    ? '<i class="bi bi-heart-fill" aria-hidden="true"></i>'
                    : '<i class="bi bi-heart" aria-hidden="true"></i>';
                Toast.show(
                    isActive ? 'Đã thêm vào danh sách yêu thích!' : 'Đã xóa khỏi yêu thích',
                    isActive ? 'success' : 'warning',
                    2000
                );
            });
            card.style.position = 'relative';
            card.appendChild(btn);
        });
    };

    /* ═══════════════════════════════════════════════
       11. SEARCH SUGGESTIONS (từ khóa phổ biến, route /search)
    ═══════════════════════════════════════════════ */
    const initSearchSuggestions = () => {
        const searchInput = document.querySelector('.search-input, input[name="q"]');
        if (!searchInput) return;

        const form = searchInput.closest('form');
        const wrapper = form || searchInput.parentElement;
        wrapper.style.position = 'relative';

        const dropdown = document.createElement('div');
        dropdown.className = 'search-suggestions';
        wrapper.appendChild(dropdown);

        const popularTerms = [
            'Laptop gaming',
            'Laptop văn phòng',
            'iPhone',
            'PC văn phòng',
            'Màn hình 144Hz',
            'Bàn phím cơ',
            'Chuột không dây',
            'SSD 1TB',
            'RAM DDR5',
        ];

        const render = (terms, title) => {
            dropdown.innerHTML = `
                <div class="suggestion-title">${title}</div>
                ${terms.map(term => `
                    <button type="button" class="suggestion-item" data-term="${term}">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <span class="s-name">${term}</span>
                    </button>
                `).join('')}
            `;
            dropdown.classList.add('show');
        };

        const filterTerms = value => {
            const keyword = value.trim().toLowerCase();
            if (!keyword) return popularTerms;
            return popularTerms.filter(term => term.toLowerCase().includes(keyword));
        };

        searchInput.addEventListener('input', () => {
            const filtered = filterTerms(searchInput.value);
            if (!filtered.length) {
                dropdown.classList.remove('show');
                return;
            }
            render(filtered, searchInput.value.trim() ? 'Gợi ý tìm kiếm' : 'Tìm kiếm phổ biến');
        });

        searchInput.addEventListener('focus', () => {
            render(filterTerms(searchInput.value), searchInput.value.trim() ? 'Gợi ý tìm kiếm' : 'Tìm kiếm phổ biến');
        });

        dropdown.addEventListener('click', (e) => {
            const item = e.target.closest('.suggestion-item');
            if (!item) return;
            searchInput.value = item.dataset.term;
            dropdown.classList.remove('show');
            searchInput.closest('form')?.submit();
        });

        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) dropdown.classList.remove('show');
        });

        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') dropdown.classList.remove('show');
        });

        form?.addEventListener('submit', () => dropdown.classList.remove('show'));
    };

    /* ═══════════════════════════════════════════════
       11b. CATEGORIES DROPDOWN (header)
    ═══════════════════════════════════════════════ */
    const initCategoriesMenu = () => {
        const toggle = document.querySelector('[data-categories-toggle]');
        const panel = document.querySelector('[data-categories-panel]');
        if (!toggle || !panel) return;

        const close = () => {
            panel.classList.remove('show');
            toggle.setAttribute('aria-expanded', 'false');
        };

        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = panel.classList.toggle('show');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', (e) => {
            if (!panel.contains(e.target) && !toggle.contains(e.target)) close();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') close();
        });

        panel.addEventListener('click', (e) => {
            if (e.target.closest('a')) close();
        });
    };

    /* ═══════════════════════════════════════════════
       12. STAGGER ANIMATION cho product grid
    ═══════════════════════════════════════════════ */
    const initStaggerCards = () => {
        const grids = document.querySelectorAll('#product-grid, .products-grid, .row[data-stagger]');
        grids.forEach(grid => {
            const cards = grid.querySelectorAll('.product-card, .col');
            cards.forEach((card, i) => {
                card.classList.add('reveal');
                card.style.transitionDelay = `${(i % 6) * 0.06}s`;
            });
        });
    };

    /* ═══════════════════════════════════════════════
       13. SMOOTH NUMBER COUNT (dashboard stats)
    ═══════════════════════════════════════════════ */
    const animateCount = (el) => {
        const target = parseFloat(el.dataset.count || el.textContent.replace(/[^\d.]/g, ''));
        const isFloat = el.dataset.count?.includes('.');
        const duration = 1200;
        const start = performance.now();

        const run = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const value = target * eased;
            el.textContent = isFloat ? value.toFixed(1) : Math.floor(value).toLocaleString('vi-VN');
            if (progress < 1) requestAnimationFrame(run);
            else el.textContent = isFloat ? target.toFixed(1) : target.toLocaleString('vi-VN');
        };
        requestAnimationFrame(run);
    };

    const initCountAnimation = () => {
        const els = document.querySelectorAll('[data-count]');
        if (!els.length) return;

        const obs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCount(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        els.forEach(el => obs.observe(el));
    };

    /* ═══════════════════════════════════════════════
       14. HOVER on nav items — micro-bounce
    ═══════════════════════════════════════════════ */
    const initNavHover = () => {
        document.querySelectorAll('.nav-item .nav-link').forEach(link => {
            link.addEventListener('mouseenter', () => {
                link.style.transition = 'all 0.15s cubic-bezier(.34,1.56,.64,1)';
            });
        });
    };

    /* ═══════════════════════════════════════════════
       15. SCROLL PROGRESS BAR (accent, 3px)
    ═══════════════════════════════════════════════ */
    const initScrollProgress = () => {
        const bar = document.createElement('div');
        bar.id = 'scroll-progress';
        bar.setAttribute('aria-hidden', 'true');
        document.body.prepend(bar);

        const sync = () => {
            const doc = document.documentElement;
            const max = doc.scrollHeight - doc.clientHeight;
            const progress = max > 0 ? (doc.scrollTop / max) * 100 : 0;
            bar.style.width = progress + '%';
        };

        sync();
        window.addEventListener('scroll', sync, { passive: true });
        window.addEventListener('resize', sync);
    };

    /* ═══════════════════════════════════════════════
       16. DETAIL GALLERY — zoom + thumbnail fade
    ═══════════════════════════════════════════════ */
    const formatPrice = (value) => new Intl.NumberFormat('vi-VN', {
        maximumFractionDigits: 0,
    }).format(Math.max(0, Math.round(Number(value) || 0))) + '₫';

    const initDetailGallery = () => {
        const gallery = document.querySelector('[data-gallery]');
        const lightbox = document.querySelector('[data-gallery-lightbox]');
        if (!gallery || !lightbox) return;

        const main = gallery.querySelector('[data-gallery-main]');
        const openBtn = gallery.querySelector('[data-gallery-open]');
        const thumbs = Array.from(gallery.querySelectorAll('[data-gallery-thumbs] .detail-thumb'));
        const count = gallery.querySelector('[data-gallery-count]');
        const lightboxImg = lightbox.querySelector('[data-lightbox-img]');
        const lightboxCounter = lightbox.querySelector('[data-lightbox-counter]');
        const closeBtn = lightbox.querySelector('[data-lightbox-close]');
        const prevBtn = lightbox.querySelector('[data-lightbox-prev]');
        const nextBtn = lightbox.querySelector('[data-lightbox-next]');
        const stage = lightbox.querySelector('[data-lightbox-stage]');
        if (!main || !openBtn || !lightboxImg || !closeBtn || !stage) return;

        const slides = thumbs.length
            ? thumbs.map((thumb, index) => ({
                src: thumb.dataset.gallerySrc || main.src,
                alt: thumb.dataset.galleryAlt || main.alt,
                fallback: thumb.querySelector('img')?.dataset.fallback || main.dataset.fallback || '',
                index,
            }))
            : [{
                src: main.currentSrc || main.src,
                alt: main.alt,
                fallback: main.dataset.fallback || '',
                index: 0,
            }];

        let currentIndex = Math.min(
            slides.length - 1,
            Math.max(0, parseInt(main.dataset.galleryIndex || '0', 10) || 0)
        );
        let fadeTimer = null;
        let closeTimer = null;
        let lastFocused = null;
        let pointerStart = null;

        const isOpen = () => !lightbox.hidden;

        const useFallback = (img) => {
            const fallback = img.dataset.fallback;
            if (!fallback || img.dataset.fallbackApplied === '1') return;
            img.dataset.fallbackApplied = '1';
            img.src = fallback;
        };

        main.addEventListener('error', () => useFallback(main));
        lightboxImg.addEventListener('error', () => useFallback(lightboxImg));
        thumbs.forEach((thumb) => {
            const img = thumb.querySelector('img');
            if (img) img.addEventListener('error', () => useFallback(img));
        });

        const syncIndicators = () => {
            const label = `${currentIndex + 1} / ${slides.length}`;
            if (count) count.textContent = label;
            if (lightboxCounter) lightboxCounter.textContent = label;
            thumbs.forEach((thumb, index) => {
                const active = index === currentIndex;
                thumb.classList.toggle('active', active);
                thumb.setAttribute('aria-pressed', active ? 'true' : 'false');
            });
        };

        const applyMainSlide = (slide) => {
            main.dataset.galleryIndex = String(currentIndex);
            main.src = slide.src;
            main.alt = slide.alt;
            delete main.dataset.fallbackApplied;
        };

        const applyLightboxSlide = (slide) => {
            lightboxImg.src = slide.src;
            lightboxImg.alt = slide.alt;
            delete lightboxImg.dataset.fallbackApplied;
        };

        const setIndex = (index, options = {}) => {
            const nextIndex = ((index % slides.length) + slides.length) % slides.length;
            const changed = nextIndex !== currentIndex;
            if (!changed && !options.force) {
                syncIndicators();
                return;
            }

            currentIndex = nextIndex;
            const slide = slides[currentIndex];
            syncIndicators();

            if (options.lightboxOnly) {
                applyLightboxSlide(slide);
                return;
            }

            clearTimeout(fadeTimer);
            if (changed && options.animate !== false) {
                main.classList.add('is-fading');
                fadeTimer = setTimeout(() => {
                    applyMainSlide(slide);
                    main.classList.remove('is-fading');
                }, 140);
            } else {
                applyMainSlide(slide);
                main.classList.remove('is-fading');
            }

            if (isOpen()) applyLightboxSlide(slide);
        };

        const openLightbox = (index) => {
            lastFocused = document.activeElement;
            clearTimeout(closeTimer);
            setIndex(index, { animate: false, force: true, lightboxOnly: true });
            lightbox.hidden = false;
            lightbox.setAttribute('aria-hidden', 'false');
            lightbox.classList.toggle('is-single', slides.length < 2);
            document.body.classList.add('lightbox-open');
            requestAnimationFrame(() => {
                lightbox.classList.add('is-open');
                closeBtn.focus({ preventScroll: true });
            });
        };

        const closeLightbox = () => {
            if (!isOpen()) return;
            lightbox.classList.remove('is-open');
            lightbox.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('lightbox-open');
            clearTimeout(closeTimer);
            closeTimer = setTimeout(() => {
                lightbox.hidden = true;
                const target = lastFocused && document.contains(lastFocused) ? lastFocused : openBtn;
                target.focus({ preventScroll: true });
            }, 220);
        };

        const step = (delta) => setIndex(currentIndex + delta, { animate: false });

        thumbs.forEach((thumb, index) => {
            thumb.addEventListener('click', () => setIndex(index));
        });

        openBtn.addEventListener('click', () => openLightbox(currentIndex));
        closeBtn.addEventListener('click', closeLightbox);
        prevBtn?.addEventListener('click', () => step(-1));
        nextBtn?.addEventListener('click', () => step(1));

        lightbox.addEventListener('click', (event) => {
            if (event.target === lightbox) closeLightbox();
        });

        stage.addEventListener('pointerdown', (event) => {
            if (event.pointerType === 'mouse' && event.button !== 0) return;
            pointerStart = {
                x: event.clientX,
                y: event.clientY,
                id: event.pointerId,
            };
            stage.setPointerCapture?.(event.pointerId);
        });

        const endSwipe = (event) => {
            if (!pointerStart || pointerStart.id !== event.pointerId) return;
            const dx = event.clientX - pointerStart.x;
            const dy = event.clientY - pointerStart.y;
            pointerStart = null;
            if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy)) {
                step(dx < 0 ? 1 : -1);
            }
        };

        stage.addEventListener('pointerup', endSwipe);
        stage.addEventListener('pointercancel', () => { pointerStart = null; });

        document.addEventListener('keydown', (event) => {
            if (!isOpen()) return;

            if (event.key === 'Escape') {
                event.preventDefault();
                closeLightbox();
                return;
            }

            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                step(-1);
                return;
            }

            if (event.key === 'ArrowRight') {
                event.preventDefault();
                step(1);
                return;
            }

            if (event.key !== 'Tab') return;

            const focusable = [closeBtn, prevBtn, nextBtn].filter((el) => el && el.offsetParent !== null);
            if (!focusable.length) return;
            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        });

        syncIndicators();
    };

    const initDetailTabs = () => {
        const buttons = Array.from(document.querySelectorAll('[data-detail-tab]'));
        if (!buttons.length) return;

        const panels = Array.from(document.querySelectorAll('[data-detail-panel]'));

        const activate = (name, focusPanel = false) => {
            buttons.forEach((button) => {
                const active = button.dataset.detailTab === name;
                button.classList.toggle('active', active);
                button.setAttribute('aria-selected', active ? 'true' : 'false');
                button.tabIndex = active ? 0 : -1;
            });

            panels.forEach((panel) => {
                panel.hidden = panel.dataset.detailPanel !== name;
            });

            if (focusPanel) {
                const panel = panels.find((item) => item.dataset.detailPanel === name);
                panel?.focus({ preventScroll: true });
            }
        };

        buttons.forEach((button, index) => {
            button.addEventListener('click', () => activate(button.dataset.detailTab));

            button.addEventListener('keydown', (event) => {
                let targetIndex = -1;

                if (event.key === 'ArrowRight') targetIndex = (index + 1) % buttons.length;
                else if (event.key === 'ArrowLeft') targetIndex = (index - 1 + buttons.length) % buttons.length;
                else if (event.key === 'Home') targetIndex = 0;
                else if (event.key === 'End') targetIndex = buttons.length - 1;

                if (targetIndex === -1) return;

                event.preventDefault();
                const target = buttons[targetIndex];
                activate(target.dataset.detailTab);
                target.focus();
            });
        });

        const hash = window.location.hash;
        if (hash) {
            const target = buttons.find((button) => `#${button.getAttribute('aria-controls')}` === hash);
            if (target) activate(target.dataset.detailTab);
        }
    };

    const initDetailVariants = () => {
        const form = document.querySelector('[data-detail-form]');
        const group = form?.querySelector('[data-variants]');
        const block = document.querySelector('[data-price-block]');
        if (!form || !group || !block) return;

        const inputs = Array.from(group.querySelectorAll('input[name="variant_id"]'));
        if (!inputs.length) return;

        const skuEl = document.querySelector('[data-detail-sku]');
        const currentEl = block.querySelector('[data-price-current]');
        const oldEl = block.querySelector('[data-price-old]');
        const discountEl = block.querySelector('[data-price-discount]');
        const basePrice = Number(block.dataset.basePrice) || 0;
        const baseSale = Number(block.dataset.baseSale) || 0;

        const sync = (input) => {
            if (!input) return;

            const variantPrice = Number(input.dataset.variantPrice) || 0;
            const sku = input.dataset.variantSku || '';
            const current = variantPrice > 0 ? variantPrice : (baseSale > 0 ? baseSale : basePrice);
            const old = variantPrice > 0
                ? (variantPrice < basePrice ? basePrice : 0)
                : (baseSale > 0 ? basePrice : 0);
            const discount = old > 0 ? Math.round(((old - current) / old) * 100) : 0;

            if (skuEl && sku) skuEl.textContent = sku;
            if (currentEl) currentEl.textContent = formatPrice(current);

            if (oldEl) {
                oldEl.textContent = old > 0 ? formatPrice(old) : '';
                oldEl.hidden = old <= 0;
            }

            if (discountEl) {
                discountEl.textContent = discount > 0 ? `-${discount}%` : '';
                discountEl.hidden = discount <= 0;
            }
        };

        inputs.forEach((input) => {
            input.addEventListener('change', () => {
                if (input.checked) sync(input);
            });
        });

        sync(inputs.find((input) => input.checked));
    };

    /* ═══════════════════════════════════════════════
       17. CART ROW COLLAPSE + AUTO SUBMIT + CONFIRM
    ═══════════════════════════════════════════════ */
    const initCartInteractions = () => {
        document.querySelectorAll('form.js-cart-remove').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (form.dataset.confirmed === '1') return;
                e.preventDefault();

                const confirmText = form.querySelector('[data-confirm]')?.dataset.confirm;
                if (confirmText && !window.confirm(confirmText)) return;

                const row = form.closest('tr');
                if (!row) {
                    form.dataset.confirmed = '1';
                    form.submit();
                    return;
                }

                row.classList.add('row-collapsing');
                setTimeout(() => {
                    form.dataset.confirmed = '1';
                    form.submit();
                }, 320);
            });
        });

        document.querySelectorAll('[data-auto-submit]').forEach(input => {
            input.addEventListener('change', () => {
                input.closest('form')?.submit();
            });
        });

        document.querySelectorAll('[data-confirm]').forEach(el => {
            const form = el.closest('form');
            if (!form || form.classList.contains('js-cart-remove')) return;
            form.addEventListener('submit', (e) => {
                if (form.dataset.confirmed === '1') return;
                if (!window.confirm(el.dataset.confirm)) {
                    e.preventDefault();
                } else {
                    form.dataset.confirmed = '1';
                }
            });
        });
    };

    /* ═══════════════════════════════════════════════
       INIT ALL
    ═══════════════════════════════════════════════ */
    document.addEventListener('DOMContentLoaded', () => {
        initScrollReveal();
        initBackToTop();
        initLazyImages();
        initCountdown();
        initWishlist();
        initSearchSuggestions();
        initCategoriesMenu();
        initStaggerCards();
        initCountAnimation();
        initNavHover();
        initScrollProgress();
        initDetailGallery();
        initDetailTabs();
        initDetailVariants();
        initCartInteractions();

        // Re-run reveal after dynamic content
        setTimeout(initScrollReveal, 500);
    });

    // Export cho các file khác dùng
    window.Nova = {
        toast: Toast,
        flyToCart,
        injectSkeletons,
        animateCount,
    };

})();

