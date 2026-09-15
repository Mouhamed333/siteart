/**
 * Art' Afric — main.js
 * Comportements globaux : menu mobile, recherche live, thème sombre,
 * favoris, onglets produit, sélecteurs de quantité, widget de chat, avis.
 * Dépend de window.APP_URL et window.CSRF_TOKEN (définis dans layouts/main.php).
 */
(function () {
    'use strict';

    const APP_URL = window.APP_URL || '';
    const CSRF_TOKEN = window.CSRF_TOKEN || '';

    function notify(message, icon) {
        if (window.Swal) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon || 'success',
                title: message,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        } else {
            alert(message);
        }
    }

    async function postJSON(url, data) {
        const body = new URLSearchParams({ _csrf_token: CSRF_TOKEN, ...data });
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': CSRF_TOKEN
            },
            body
        });
        return res.json();
    }

    document.addEventListener('DOMContentLoaded', function () {
        /* ---------- Menu mobile ---------- */
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const mainNav = document.querySelector('.main-nav');
        if (menuToggle && mainNav) {
            menuToggle.addEventListener('click', function () {
                mainNav.classList.toggle('nav-open');
            });
        }

        /* ---------- Recherche live ---------- */
        const searchToggle = document.getElementById('search-toggle');
        const searchModal = document.getElementById('search-modal');
        const searchInput = document.getElementById('live-search');
        const searchResults = document.getElementById('search-results');
        const modalClose = searchModal ? searchModal.querySelector('.modal-close') : null;
        let searchTimer = null;

        function openSearch() {
            if (!searchModal) return;
            searchModal.classList.add('active');
            searchModal.setAttribute('aria-hidden', 'false');
            if (searchInput) searchInput.focus();
        }
        function closeSearch() {
            if (!searchModal) return;
            searchModal.classList.remove('active');
            searchModal.setAttribute('aria-hidden', 'true');
        }
        if (searchToggle) searchToggle.addEventListener('click', openSearch);
        if (modalClose) modalClose.addEventListener('click', closeSearch);
        if (searchModal) {
            searchModal.addEventListener('click', function (e) {
                if (e.target === searchModal) closeSearch();
            });
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSearch();
        });

        if (searchInput && searchResults) {
            searchInput.addEventListener('input', function () {
                const q = searchInput.value.trim();
                clearTimeout(searchTimer);
                if (q.length < 2) {
                    searchResults.innerHTML = '';
                    return;
                }
                searchTimer = setTimeout(async function () {
                    try {
                        const res = await fetch(APP_URL + '/api/recherche?q=' + encodeURIComponent(q), {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        const items = data.results || [];
                        if (items.length === 0) {
                            searchResults.innerHTML = '<p class="no-results">Aucun résultat.</p>';
                            return;
                        }
                        searchResults.innerHTML = items.map(function (p) {
                            return '<a href="' + p.url + '">' +
                                '<img src="' + APP_URL + '/assets/images/products/' + p.image + '" alt="">' +
                                '<span>' + p.name + '</span>' +
                                '</a>';
                        }).join('');
                    } catch (err) {
                        searchResults.innerHTML = '<p class="no-results">Erreur de recherche.</p>';
                    }
                }, 300);
            });
        }

        /* ---------- Favoris ---------- */
        document.querySelectorAll('.toggle-favorite').forEach(function (btn) {
            btn.addEventListener('click', async function () {
                const id = btn.getAttribute('data-id');
                try {
                    const data = await postJSON(APP_URL + '/api/favoris', { product_id: id });
                    if (data.success) {
                        const icon = btn.querySelector('i');
                        if (icon) icon.className = data.added ? 'fas fa-heart' : 'far fa-heart';
                        notify(data.message, 'success');
                    } else {
                        notify(data.message || 'Une erreur est survenue.', 'error');
                    }
                } catch (err) {
                    notify('Connectez-vous pour ajouter aux favoris.', 'error');
                }
            });
        });

        /* ---------- Onglets produit ---------- */
        document.querySelectorAll('.tabs .tab').forEach(function (tab) {
            tab.addEventListener('click', function () {
                const target = tab.getAttribute('data-tab');
                document.querySelectorAll('.tabs .tab').forEach(function (t) { t.classList.remove('active'); });
                document.querySelectorAll('.tab-content').forEach(function (c) { c.classList.remove('active'); });
                tab.classList.add('active');
                const content = document.getElementById(target);
                if (content) content.classList.add('active');
            });
        });

        /* ---------- Galerie produit ---------- */
        const mainImage = document.getElementById('main-image');
        document.querySelectorAll('.thumb').forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                document.querySelectorAll('.thumb').forEach(function (t) { t.classList.remove('active'); });
                thumb.classList.add('active');
                const src = thumb.getAttribute('data-src');
                if (mainImage && src) mainImage.src = src;
            });
        });

        /* ---------- Sélecteurs de quantité (hors panier, ex: fiche produit) ---------- */
        document.querySelectorAll('.qty-selector').forEach(function (selector) {
            const input = selector.querySelector('input');
            const minus = selector.querySelector('.qty-minus');
            const plus = selector.querySelector('.qty-plus');
            if (!input) return;
            if (minus) {
                minus.addEventListener('click', function () {
                    const min = parseInt(input.min || '1', 10);
                    const val = Math.max(min, (parseInt(input.value, 10) || 1) - 1);
                    input.value = val;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                });
            }
            if (plus) {
                plus.addEventListener('click', function () {
                    const max = input.max ? parseInt(input.max, 10) : Infinity;
                    const val = Math.min(max, (parseInt(input.value, 10) || 1) + 1);
                    input.value = val;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                });
            }
        });

        /* ---------- Formulaire d'avis ---------- */
        const reviewForm = document.getElementById('review-form');
        if (reviewForm) {
            reviewForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(reviewForm);
                const productIdInput = document.querySelector('input[name="product_id"]');
                const data = {
                    product_id: productIdInput ? productIdInput.value : '',
                    rating: formData.get('rating'),
                    title: formData.get('title') || '',
                    comment: formData.get('comment') || ''
                };
                try {
                    const res = await postJSON(APP_URL + '/api/avis', data);
                    if (res.success) {
                        notify(res.message, 'success');
                        reviewForm.reset();
                    } else {
                        notify(res.message || 'Impossible d\'envoyer votre avis.', 'error');
                    }
                } catch (err) {
                    notify('Une erreur est survenue.', 'error');
                }
            });
        }
    });
})();
