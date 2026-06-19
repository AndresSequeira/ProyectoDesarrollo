(function () {
	'use strict';

	const html = (value) => String(value || '').replace(/[&<>"']/g, (char) => ({
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#039;',
	}[char]));

	const state = {
		slugs: [],
		filter: 'Todos',
		search: '',
	};

	const elements = ['Todos', 'Fuego', 'Energia', 'Sanacion', 'Tierra', 'Agua', 'Aire', 'Luz', 'Toxico'];

	function cardTemplate(slug) {
		return `
			<button class="bajoterra-card" type="button" data-slug-id="${html(slug.id)}" style="--slug-color:${html(slug.color)}">
				<span class="bajoterra-card__image-wrap">
					<img class="bajoterra-card__image" src="${html(slug.image)}" alt="${html(slug.name)}" loading="lazy">
				</span>
				<span class="bajoterra-card__meta">${html(slug.element)} / ${html(slug.rarity)}</span>
				<strong>${html(slug.name)}</strong>
				<small>${html(slug.type)}</small>
				<p>${html(slug.power)}</p>
			</button>
		`;
	}

	function modalTemplate(slug) {
		return `
			<div class="bajoterra-detail" style="--slug-color:${html(slug.color)}">
				<img class="bajoterra-detail__image" src="${html(slug.image)}" alt="${html(slug.name)}">
				<p class="bajoterra-card__meta">${html(slug.element)} / ${html(slug.rarity)}</p>
				<h3 id="bajoterra-modal-title">${html(slug.name)}</h3>
				<p class="bajoterra-detail__type">${html(slug.type)}</p>
				<dl>
					<div><dt>Dueno o grupo</dt><dd>${html(slug.owner)}</dd></div>
					<div><dt>Poder</dt><dd>${html(slug.power)}</dd></div>
					<div><dt>Personalidad</dt><dd>${html(slug.personality)}</dd></div>
					<div><dt>Debilidad</dt><dd>${html(slug.weakness)}</dd></div>
				</dl>
			</div>
		`;
	}

	function render(app) {
		const grid = app.querySelector('[data-bajoterra-grid]');
		const status = app.querySelector('[data-bajoterra-status]');
		const filtered = state.slugs.filter((slug) => {
			const text = `${slug.name} ${slug.type} ${slug.element} ${slug.power}`.toLowerCase();
			const matchesSearch = text.includes(state.search.toLowerCase());
			const matchesFilter = state.filter === 'Todos' || slug.element === state.filter;
			return matchesSearch && matchesFilter;
		});

		status.textContent = `${filtered.length} babosa${filtered.length === 1 ? '' : 's'} encontrada${filtered.length === 1 ? '' : 's'}`;
		grid.innerHTML = filtered.length
			? filtered.map(cardTemplate).join('')
			: '<p class="bajoterra-empty">No hay babosas con ese filtro.</p>';
	}

	function openModal(app, slug) {
		const modal = app.querySelector('[data-bajoterra-modal]');
		const content = app.querySelector('[data-bajoterra-modal-content]');
		content.innerHTML = modalTemplate(slug);
		modal.setAttribute('aria-hidden', 'false');
		document.body.classList.add('bajoterra-modal-open');
	}

	function closeModal(app) {
		const modal = app.querySelector('[data-bajoterra-modal]');
		modal.setAttribute('aria-hidden', 'true');
		document.body.classList.remove('bajoterra-modal-open');
	}

	function renderFilters(app) {
		const filters = app.querySelector('[data-bajoterra-filters]');
		filters.innerHTML = elements.map((element) => `
			<button class="bajoterra-filter${element === state.filter ? ' is-active' : ''}" type="button" data-element="${html(element)}">
				${html(element)}
			</button>
		`).join('');
	}

	async function loadSlugs(app, refresh) {
		const status = app.querySelector('[data-bajoterra-status]');
		const summary = app.querySelector('[data-bajoterra-summary]');
		const restUrl = app.dataset.restUrl
			|| (window.BajoterraBabosas && window.BajoterraBabosas.restUrl)
			|| `${window.location.origin}/wp-json/bajoterra/v1/babosas`;
		const url = new URL(restUrl, window.location.href);

		if (refresh) {
			url.searchParams.set('refresh', 'true');
		}

		status.textContent = refresh ? 'Actualizando desde la API publica...' : 'Cargando babosas...';

		try {
			const response = await fetch(url.toString());
			if (!response.ok) {
				throw new Error('Respuesta no valida');
			}
			const data = await response.json();
			state.slugs = data.slugs || [];
			summary.textContent = data.context && data.context.extract
				? `${data.context.extract} Fuente: ${data.context.source_name}.`
				: 'Directorio interactivo de babosas de Bajoterra.';
			renderFilters(app);
			render(app);
		} catch (error) {
			status.textContent = 'No se pudo cargar la informacion. Revisa que el plugin este activo y los enlaces permanentes funcionen.';
		}
	}

	function boot(app) {
		const search = app.querySelector('[data-bajoterra-search]');
		const filters = app.querySelector('[data-bajoterra-filters]');
		const refresh = app.querySelector('[data-bajoterra-refresh]');
		const close = app.querySelector('[data-bajoterra-close]');
		const modal = app.querySelector('[data-bajoterra-modal]');
		const grid = app.querySelector('[data-bajoterra-grid]');

		renderFilters(app);
		loadSlugs(app, false);

		search.addEventListener('input', (event) => {
			state.search = event.target.value;
			render(app);
		});

		filters.addEventListener('click', (event) => {
			const button = event.target.closest('[data-element]');
			if (!button) {
				return;
			}
			state.filter = button.dataset.element;
			renderFilters(app);
			render(app);
		});

		refresh.addEventListener('click', () => loadSlugs(app, true));

		grid.addEventListener('click', (event) => {
			const card = event.target.closest('[data-slug-id]');
			if (!card) {
				return;
			}
			const slug = state.slugs.find((item) => String(item.id) === String(card.dataset.slugId));
			if (slug) {
				openModal(app, slug);
			}
		});

		close.addEventListener('click', () => closeModal(app));
		modal.addEventListener('click', (event) => {
			if (event.target === modal) {
				closeModal(app);
			}
		});
		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape') {
				closeModal(app);
			}
		});
	}

	document.addEventListener('DOMContentLoaded', () => {
		document.querySelectorAll('[data-bajoterra-app]').forEach(boot);
	});
}());
