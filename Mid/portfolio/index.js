const menuToggle = document.querySelector('.menu-toggle');
const navLinks = document.querySelector('.nav-links');
const themeToggle = document.querySelector('.theme-toggle');
const themeIcon = themeToggle?.querySelector('i');

function closeMenu() {
	if (!navLinks || !menuToggle) {
		return;
	}

	navLinks.classList.remove('open');
	menuToggle.setAttribute('aria-expanded', 'false');
}

if (menuToggle && navLinks) {
	menuToggle.addEventListener('click', () => {
		const isOpen = navLinks.classList.toggle('open');
		menuToggle.setAttribute('aria-expanded', String(isOpen));
	});

	navLinks.querySelectorAll('a').forEach((link) => {
		link.addEventListener('click', closeMenu);
	});

	document.addEventListener('click', (event) => {
		if (!navLinks.contains(event.target) && !menuToggle.contains(event.target)) {
			closeMenu();
		}
	});

	window.addEventListener('resize', () => {
		if (window.innerWidth > 480) {
			closeMenu();
		}
	});
}

function updateTheme(theme) {
	const isLight = theme === 'light';

	document.body.classList.toggle('light-theme', isLight);

	if (themeIcon) {
		themeIcon.classList.toggle('fa-sun', !isLight);
		themeIcon.classList.toggle('fa-moon', isLight);
	}

	if (themeToggle) {
		themeToggle.setAttribute('aria-label', isLight ? 'Switch to dark mode' : 'Switch to light mode');
	}
}

const savedTheme = localStorage.getItem('theme');
updateTheme(savedTheme === 'light' ? 'light' : 'dark');

if (themeToggle) {
	themeToggle.addEventListener('click', () => {
		const isLight = document.body.classList.contains('light-theme');
		const nextTheme = isLight ? 'dark' : 'light';
		updateTheme(nextTheme);
		localStorage.setItem('theme', nextTheme);
	});
}

const blogGrid = document.querySelector('.blog-grid');
const blogCards = blogGrid ? Array.from(blogGrid.querySelectorAll('.blog-card')) : [];
const prevBlogBtn = document.querySelector('.blog-arrow-left');
const nextBlogBtn = document.querySelector('.blog-arrow-right');
const blogDots = document.querySelector('.blog-dots');

let blogIndex = 0;

function getBlogCardsPerView() {
	if (window.innerWidth <= 480) {
		return 1;
	}

	if (window.innerWidth <= 768) {
		return 2;
	}

	return 3;
}

function getBlogMaxIndex() {
	return Math.max(0, blogCards.length - getBlogCardsPerView());
}

function renderBlogDots() {
	if (!blogDots) {
		return;
	}

	const totalPages = getBlogMaxIndex() + 1;
	blogDots.innerHTML = '';

	for (let i = 0; i < totalPages; i += 1) {
		const dot = document.createElement('span');

		if (i === blogIndex) {
			dot.classList.add('active');
		}

		dot.addEventListener('click', () => {
			blogIndex = i;
			updateBlogCarousel();
		});

		blogDots.appendChild(dot);
	}
}

function updateBlogCarousel() {
	if (!blogGrid || blogCards.length === 0) {
		return;
	}

	blogIndex = Math.min(blogIndex, getBlogMaxIndex());

	const cardWidth = blogCards[0].getBoundingClientRect().width;
	const gapValue = Number.parseFloat(getComputedStyle(blogGrid).gap) || 0;
	const offset = blogIndex * (cardWidth + gapValue);

	blogGrid.style.transform = `translateX(-${offset}px)`;

	if (prevBlogBtn) {
		prevBlogBtn.disabled = blogIndex === 0;
	}

	if (nextBlogBtn) {
		nextBlogBtn.disabled = blogIndex >= getBlogMaxIndex();
	}

	if (blogDots) {
		const dots = blogDots.querySelectorAll('span');
		dots.forEach((dot, idx) => {
			dot.classList.toggle('active', idx === blogIndex);
		});
	}
}

if (blogGrid && blogCards.length > 0) {
	renderBlogDots();
	updateBlogCarousel();

	if (prevBlogBtn) {
		prevBlogBtn.addEventListener('click', () => {
			blogIndex = Math.max(0, blogIndex - 1);
			updateBlogCarousel();
		});
	}

	if (nextBlogBtn) {
		nextBlogBtn.addEventListener('click', () => {
			blogIndex = Math.min(getBlogMaxIndex(), blogIndex + 1);
			updateBlogCarousel();
		});
	}

	window.addEventListener('resize', () => {
		renderBlogDots();
		updateBlogCarousel();
	});
}

const contactForm = document.querySelector('.contact-form');

if (contactForm) {
	const nameInput = contactForm.querySelector('#contact-name');
	const emailInput = contactForm.querySelector('#contact-email');
	const messageInput = contactForm.querySelector('#contact-message');
	const fields = [nameInput, emailInput, messageInput].filter(Boolean);

	const formStatus = document.createElement('p');
	formStatus.className = 'contact-form-status';
	formStatus.setAttribute('aria-live', 'polite');
	contactForm.appendChild(formStatus);

	function getErrorNode(field) {
		let errorNode = field.parentElement.querySelector('.contact-error');

		if (!errorNode) {
			errorNode = document.createElement('p');
			errorNode.className = 'contact-error';
			errorNode.setAttribute('aria-live', 'polite');
			field.parentElement.appendChild(errorNode);
		}

		return errorNode;
	}

	function setFieldError(field, message) {
		const errorNode = getErrorNode(field);
		errorNode.textContent = message;
		field.classList.add('is-invalid');
		field.setAttribute('aria-invalid', 'true');
	}

	function clearFieldError(field) {
		const errorNode = getErrorNode(field);
		errorNode.textContent = '';
		field.classList.remove('is-invalid');
		field.setAttribute('aria-invalid', 'false');
	}

	function validateField(field) {
		const value = field.value.trim();

		if (field.id === 'contact-name') {
			if (value.length < 2) {
				setFieldError(field, 'Name must be at least 2 characters.');
				return false;
			}

			if (!/^[A-Za-z .'-]+$/.test(value)) {
				setFieldError(field, 'Use letters and common name symbols only.');
				return false;
			}
		}

		if (field.id === 'contact-email') {
			if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value)) {
				setFieldError(field, 'Enter a valid email address.');
				return false;
			}
		}

		if (field.id === 'contact-message') {
			if (value.length < 20) {
				setFieldError(field, 'Message should be at least 20 characters.');
				return false;
			}
		}

		clearFieldError(field);
		return true;
	}

	fields.forEach((field) => {
		field.addEventListener('blur', () => {
			validateField(field);
		});

		field.addEventListener('input', () => {
			if (field.classList.contains('is-invalid')) {
				validateField(field);
			}
		});
	});

	contactForm.addEventListener('submit', (event) => {
		event.preventDefault();
		formStatus.textContent = '';

		const allValid = fields.every((field) => validateField(field));

		if (!allValid) {
			formStatus.textContent = 'Please fix the highlighted fields.';
			formStatus.classList.remove('success');
			return;
		}

		formStatus.textContent = 'Message validated successfully.';
		formStatus.classList.add('success');
		contactForm.reset();
		fields.forEach((field) => clearFieldError(field));
	});
}
