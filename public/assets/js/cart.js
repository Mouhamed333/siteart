/**
 * Art' Afric — cart.js
 * Gestion du panier en AJAX : ajout, mise à jour des quantités,
 * suppression d'articles et application de code promo.
 * Dépend de window.APP_URL et window.CSRF_TOKEN.
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
                timer: 2200,
                timerProgressBar: true
            });
        } else {
            alert(message);
        }
    }

    async function postForm(url, params) {
        const body = new URLSearchParams({ _csrf_token: CSRF_TOKEN, ...params });
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

    function updateCartCount(count) {
        const badge = document.getElementById('cart-count');
        if (badge && typeof count !== 'undefined') badge.textContent = count;
    }

    function applySummary(summary) {
        if (!summary) return;
        const map = {
            subtotal: summary.subtotal,
            shipping: summary.shipping > 0 ? summary.shipping : null,
            discount: summary.discount,
            tax: summary.tax,
            total: summary.total
        };
        Object.keys(map).forEach(function (key) {
            const el = document.getElementById(key);
            if (!el || map[key] === null || typeof map[key] === 'undefined') return;
            el.textContent = formatPrice(map[key]);
        });
        const shippingEl = document.getElementById('shipping');
        if (shippingEl && !(summary.shipping > 0)) shippingEl.textContent = 'Gratuite';
    }

    function formatPrice(value) {
        const n = Math.round(Number(value) || 0);
        return n.toLocaleString('fr-FR').replace(/\u202f|\u00a0/g, ' ') + ' FCFA';
    }

    document.addEventListener('DOMContentLoaded', function () {
        /* ---------- Ajout rapide depuis une carte produit (boutique / accueil) ---------- */
        document.querySelectorAll('.add-to-cart').forEach(function (btn) {
            btn.addEventListener('click', async function () {
                const id = btn.getAttribute('data-id');
                try {
                    const data = await postForm(APP_URL + '/panier/ajouter', { product_id: id, quantity: 1 });
                    if (data.success) {
                        updateCartCount(data.count);
                        notify(data.message || 'Produit ajouté au panier.', 'success');
                    } else {
                        notify(data.message || 'Impossible d\'ajouter ce produit.', 'error');
                    }
                } catch (err) {
                    notify('Une erreur est survenue.', 'error');
                }
            });
        });

        /* ---------- Formulaire d'ajout sur la fiche produit ---------- */
        const addForm = document.querySelector('.add-to-cart-form');
        if (addForm) {
            addForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(addForm);
                const params = {};
                formData.forEach(function (value, key) {
                    if (key !== '_csrf_token') params[key] = value;
                });
                try {
                    const data = await postForm(APP_URL + '/panier/ajouter', params);
                    if (data.success) {
                        updateCartCount(data.count);
                        notify(data.message || 'Produit ajouté au panier.', 'success');
                    } else {
                        notify(data.message || 'Impossible d\'ajouter ce produit.', 'error');
                    }
                } catch (err) {
                    notify('Une erreur est survenue.', 'error');
                }
            });
        }

        /* ---------- Page panier : quantités et suppression ---------- */
        const cartTable = document.querySelector('.cart-table');
        if (cartTable) {
            async function changeQuantity(key, quantity) {
                if (quantity < 1) return;
                const data = await postForm(APP_URL + '/panier/modifier', { key: key, quantity: quantity });
                if (data.success) {
                    applySummary(data.summary);
                    if (data.summary) updateCartCount(data.summary.count);
                    const row = cartTable.querySelector('tr[data-key="' + key + '"]');
                    if (row && data.summary && data.summary.items && data.summary.items[key]) {
                        const item = data.summary.items[key];
                        const totalCell = row.children[3];
                        if (totalCell) totalCell.textContent = formatPrice(item.price * item.quantity);
                    }
                } else {
                    notify(data.message || 'Impossible de mettre à jour la quantité.', 'error');
                }
            }

            cartTable.addEventListener('click', function (e) {
                const minus = e.target.closest('.qty-minus');
                const plus = e.target.closest('.qty-plus');
                const remove = e.target.closest('.remove-item');

                if (minus || plus) {
                    const key = (minus || plus).getAttribute('data-key');
                    const input = cartTable.querySelector('input[data-key="' + key + '"]');
                    if (!input) return;
                    const max = input.max ? parseInt(input.max, 10) : Infinity;
                    let val = parseInt(input.value, 10) || 1;
                    val = minus ? Math.max(1, val - 1) : Math.min(max, val + 1);
                    input.value = val;
                    changeQuantity(key, val);
                }

                if (remove) {
                    const key = remove.getAttribute('data-key');
                    postForm(APP_URL + '/panier/supprimer', { key: key }).then(function (data) {
                        if (data.success) {
                            const row = cartTable.querySelector('tr[data-key="' + key + '"]');
                            if (row) row.remove();
                            applySummary(data.summary);
                            if (data.summary) updateCartCount(data.summary.count);
                            if (data.summary && data.summary.items && Object.keys(data.summary.items).length === 0) {
                                window.location.reload();
                            }
                            notify('Article retiré du panier.', 'success');
                        } else {
                            notify(data.message || 'Impossible de retirer cet article.', 'error');
                        }
                    });
                }
            });

            cartTable.addEventListener('change', function (e) {
                if (e.target.matches('input[type="number"][data-key]')) {
                    const key = e.target.getAttribute('data-key');
                    const max = e.target.max ? parseInt(e.target.max, 10) : Infinity;
                    let val = Math.max(1, Math.min(max, parseInt(e.target.value, 10) || 1));
                    e.target.value = val;
                    changeQuantity(key, val);
                }
            });
        }

        /* ---------- Code promo ---------- */
        const couponForm = document.getElementById('coupon-form');
        if (couponForm) {
            couponForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const code = couponForm.querySelector('input[name="code"]').value;
                const data = await postForm(APP_URL + '/panier/coupon', { code: code });
                if (data.success) {
                    notify(data.message || 'Code promo appliqué.', 'success');
                    applySummary(data.summary);
                } else {
                    notify(data.message || 'Code promo invalide.', 'error');
                }
            });
        }
    });
})();
