(function () {
    'use strict';

    class LarryEpisodes {
        constructor(container) {
            this.container = container;
            this.grid = container.querySelector('.larry-episodes-grid');
            this.searchInput = container.querySelector('.larry-search-input');
            this.seasonBtns = container.querySelectorAll('.larry-season-btn');
            this.cards = container.querySelectorAll('.larry-episode-card');
            this.overlay = container.querySelector('.larry-modal-overlay');
            this.modal = container.querySelector('.larry-modal');
            this.modalClose = container.querySelector('.larry-modal-close');
            this.activeSeason = 'all';
            this.activeView = 'grid';
            this.currentQuery = '';

            this.init();
        }

        init() {
            this.bindSearch();
            this.bindSeasonFilters();
            this.bindCardClicks();
            this.bindModalClose();
            this.bindOutsideClick();
            this.bindEscKey();
        }

        bindSearch() {
            if (!this.searchInput) return;
            let debounceTimer;
            this.searchInput.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    this.currentQuery = e.target.value.toLowerCase().trim();
                    this.filterEpisodes();
                }, 200);
            });
        }

        bindSeasonFilters() {
            this.seasonBtns.forEach((btn) => {
                btn.addEventListener('click', () => {
                    this.seasonBtns.forEach((b) => b.classList.remove('is-active'));
                    btn.classList.add('is-active');
                    this.activeSeason = btn.dataset.season || 'all';
                    this.filterEpisodes();
                });
            });
        }

        bindCardClicks() {
            this.cards.forEach((card) => {
                card.addEventListener('click', () => {
                    this.openModal(card);
                });
            });
        }

        bindModalClose() {
            if (this.modalClose) {
                this.modalClose.addEventListener('click', () => this.closeModal());
            }
        }

        bindOutsideClick() {
            if (this.overlay) {
                this.overlay.addEventListener('click', (e) => {
                    if (e.target === this.overlay) this.closeModal();
                });
            }
        }

        bindEscKey() {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') this.closeModal();
            });
        }

        filterEpisodes() {
            let visibleCount = 0;

            this.cards.forEach((card) => {
                const season = card.dataset.season || '1';
                const title = (card.dataset.title || '').toLowerCase();
                const desc = (card.dataset.desc || '').toLowerCase();
                const searchMatch = !this.currentQuery ||
                    title.includes(this.currentQuery) ||
                    desc.includes(this.currentQuery);
                const seasonMatch = this.activeSeason === 'all' || season === this.activeSeason;
                const show = searchMatch && seasonMatch;

                card.style.display = show ? '' : 'none';
                if (show) {
                    card.style.animation = 'none';
                    card.offsetHeight;
                    card.style.animation = '';
                    card.style.animationDelay = (visibleCount * 0.05) + 's';
                    visibleCount++;
                }
            });

            const noResults = this.container.querySelector('.larry-no-results');
            if (noResults) {
                noResults.style.display = visibleCount === 0 ? '' : 'none';
            }
        }

        openModal(card) {
            if (!this.overlay || !this.modal) return;

            const badge = this.modal.querySelector('.modal-season-badge');
            const titleEl = this.modal.querySelector('h2');
            const desc = this.modal.querySelector('.modal-desc');
            const img = this.modal.querySelector('.larry-modal-img');
            const metaYear = this.modal.querySelector('.meta-year');
            const metaRating = this.modal.querySelector('.meta-rating');

            if (badge) badge.textContent = 'Temporada ' + (card.dataset.season || '');
            if (titleEl) titleEl.textContent = card.dataset.title || '';
            if (desc) desc.textContent = card.dataset.desc || '';
            if (metaYear) metaYear.textContent = card.dataset.year || '';
            if (metaRating) {
                const r = parseFloat(card.dataset.rating);
                if (r) {
                    metaRating.innerHTML = '';
                    for (let i = 1; i <= 5; i++) {
                        const star = document.createElement('span');
                        star.className = 'star' + (i <= Math.round(r) ? '' : ' empty');
                        star.textContent = '\u2605';
                        metaRating.appendChild(star);
                    }
                    metaRating.innerHTML += ' <span class="rating-num">' + r.toFixed(1) + '</span>';
                }
            }
            if (img) {
                const src = card.dataset.img || '';
                if (src) {
                    img.src = src;
                    img.alt = card.dataset.title || '';
                    img.style.display = '';
                } else {
                    img.src = '';
                    img.alt = '';
                    img.style.display = 'none';
                }
            }

            this.overlay.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }

        closeModal() {
            if (!this.overlay) return;
            this.overlay.classList.remove('is-open');
            document.body.style.overflow = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.larry-episodes-widget').forEach((el) => {
            if (!el.larryInstance) {
                el.larryInstance = new LarryEpisodes(el);
            }
        });
    });

})();
