# Roy Bipro Portfolio

A single-page personal portfolio website built with HTML, CSS, and JavaScript.

The portfolio includes:
- Sticky full-width navbar with responsive mobile menu
- Hero section with intro, CTA button, and social links
- About section with education details
- Skillset section with branded skill chips
- Featured projects section
- Blog section connected to Blogger posts with carousel navigation
- Contact section with JavaScript form validation
- Footer with social/contact links
- Light/Dark mode toggle with local storage persistence

## Tech Stack

- HTML5
- CSS3
- Vanilla JavaScript
- Font Awesome (icons)
- Google Fonts (JetBrains Mono)

## Project Structure

```text
portfolio/
	index.html      # Page structure and content sections
	style.css       # All styling, theme rules, and responsive breakpoints
	index.js        # Navbar/menu logic, theme toggle, blog carousel, form validation
	images/
		avatar.png
		my-photo.jpg
		favicon.png
```

## Sections Included

1. Navbar
- Links: Who am I, Skills, Projects, Blog, Contact
- Mobile hamburger menu
- Dark/Light theme toggle

2. Hero
- Intro text
- Role/title
- Short description
- Contact CTA
- Social media links

3. About
- Personal summary
- Education timeline/details

4. Skills
- "The Magic Behind" section
- Skill chips with icon colors

5. Projects
- Three featured GitHub repositories
- Tags and metadata

6. Blog
- Recent blog cards
- Arrow controls and dots
- Responsive card slider behavior

7. Contact
- Name, Email, Message fields
- JavaScript validation and inline error messages

8. Footer
- Brand, copyright, and social links

## JavaScript Features

Implemented in `index.js`:

- Mobile nav open/close behavior
- Close nav on outside click and on link click
- Theme persistence using `localStorage`
- Blog carousel controls (prev/next and dots)
- Contact form validation:
	- Name minimum length and character check
	- Email format validation
	- Message minimum length
	- Inline field errors and form status messages

## Run Locally

### Option 1: XAMPP (current setup)

1. Place the project in your htdocs folder (already done).
2. Start Apache from XAMPP Control Panel.
3. Open in browser:

```text
http://localhost/webTech/Mid/portfolio/
```

### Option 2: VS Code Live Server

1. Open the `portfolio` folder in VS Code.
2. Open `index.html`.
3. Use Live Server to preview.

## Customization Guide

- Personal info and section content: edit `index.html`
- Colors, spacing, typography, and responsive layout: edit `style.css`
- Interactions and validation logic: edit `index.js`
- Profile images/icons: replace assets in `images/`

## Notes

- Blog cards currently use Blogger post URLs and image links.
- Contact form currently validates on the client side only.
- To make contact messages send to email, integrate Formspree or EmailJS.

## Future Improvements

- Add backend/email integration for contact form
- Add smooth active-link highlighting by scroll position
- Add project screenshots instead of abstract placeholders
- Add accessibility audit (keyboard focus and aria refinements)

## Author

Roy Bipro

- GitHub: https://github.com/roybipro
- LinkedIn: https://www.linkedin.com/in/roy-bipro/
- Blog: https://roybipro.blogspot.com/

