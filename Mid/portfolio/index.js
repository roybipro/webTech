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
