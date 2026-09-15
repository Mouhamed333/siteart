/**
 * Art' Afric — shop.js
 * Améliore le formulaire de filtres de la boutique (#filter-form) :
 * soumission automatique au changement de tri/catégorie et remise à zéro.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('filter-form');
        if (!form) return;

        /* Soumission automatique quand catégorie ou tri changent */
        ['select[name="categorie"]', 'select[name="tri"]', 'input[name="promo"]', 'input[name="nouveaute"]'].forEach(function (selector) {
            const el = form.querySelector(selector);
            if (el) el.addEventListener('change', function () { form.submit(); });
        });

        /* La recherche et les champs de prix/couleur/matière se soumettent
           uniquement via le bouton "Appliquer" ou la touche Entrée (comportement natif du form). */

        /* Empêche prix min > prix max avant l'envoi */
        form.addEventListener('submit', function (e) {
            const min = form.querySelector('input[name="prix_min"]');
            const max = form.querySelector('input[name="prix_max"]');
            if (min && max && min.value && max.value && parseFloat(min.value) > parseFloat(max.value)) {
                e.preventDefault();
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Le prix minimum est supérieur au prix maximum.' });
                } else {
                    alert('Le prix minimum est supérieur au prix maximum.');
                }
            }
        });
    });
})();
