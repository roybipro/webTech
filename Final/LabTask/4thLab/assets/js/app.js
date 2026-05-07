const API_URL = '../ajax_handler.php';

const form = document.getElementById('book-form');
const bookId = document.getElementById('book-id');
const title = document.getElementById('title');
const author = document.getElementById('author');
const category = document.getElementById('category');
const statusField = document.getElementById('status');
const formTitle = document.getElementById('form-title');
const saveBtn = document.getElementById('save-btn');
const resetBtn = document.getElementById('reset-btn');
const tableBody = document.getElementById('book-table-body');
const recordCount = document.getElementById('record-count');
const search = document.getElementById('search');
const filterCategory = document.getElementById('filter-category');
const filterStatus = document.getElementById('filter-status');
const message = document.getElementById('message');

async function apiCall(action, options = {}) {
    const params = new URLSearchParams({ action, ...(options.params || {}) });
    const fetchOptions = { method: options.method || 'GET' };

    if (options.body) {
        fetchOptions.headers = { 'Content-Type': 'application/json' };
        fetchOptions.body = JSON.stringify(options.body);
    }

    const response = await fetch(`${API_URL}?${params.toString()}`, fetchOptions);
    const text = await response.text();

    try {
        return JSON.parse(text);
    } catch (error) {
        return {
            success: false,
            message: text || 'Invalid server response.'
        };
    }
}

function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function showMessage(text, type = 'success') {
    message.textContent = text;
    message.className = type === 'error' ? 'error' : '';
    message.style.display = 'block';

    window.clearTimeout(showMessage.timer);
    showMessage.timer = window.setTimeout(() => {
        message.style.display = 'none';
    }, 2600);
}

function getFilters() {
    return {
        search: search.value.trim(),
        category: filterCategory.value,
        status: filterStatus.value
    };
}

async function loadBooks() {
    tableBody.innerHTML = '<tr><td colspan="6">Loading books...</td></tr>';

    const result = await apiCall('read_all', { params: getFilters() });

    if (!result.success) {
        tableBody.innerHTML = '<tr><td colspan="6">Could not load records.</td></tr>';
        showMessage(result.message, 'error');
        return;
    }

    renderBooks(result.data);
}

function renderBooks(books) {
    recordCount.textContent = `${books.length} record${books.length === 1 ? '' : 's'} found`;

    if (books.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6">No books found.</td></tr>';
        return;
    }

    tableBody.innerHTML = books.map((book) => {
        return `
            <tr>
                <td>${book.id}</td>
                <td>${escapeHtml(book.title)}</td>
                <td>${escapeHtml(book.author)}</td>
                <td>${escapeHtml(book.category)}</td>
                <td>${escapeHtml(book.status)}</td>
                <td>
                    <div>
                        <button type="button" data-edit="${book.id}">Edit</button>
                        <button type="button" data-delete="${book.id}">Delete</button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

async function loadCategories() {
    const currentValue = filterCategory.value;
    const result = await apiCall('categories');

    if (!result.success) {
        return;
    }

    filterCategory.innerHTML = '<option value="">All Categories</option>';

    result.data.forEach((item) => {
        const option = document.createElement('option');
        option.value = item;
        option.textContent = item;
        filterCategory.appendChild(option);
    });

    filterCategory.value = currentValue;
}

function resetForm() {
    form.reset();
    bookId.value = '';
    formTitle.textContent = 'Add Book';
    saveBtn.textContent = 'Save Book';
}

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const data = {
        id: bookId.value,
        title: title.value.trim(),
        author: author.value.trim(),
        category: category.value.trim(),
        status: statusField.value
    };

    saveBtn.disabled = true;
    saveBtn.textContent = 'Saving...';

    const action = data.id ? 'update' : 'create';
    const result = await apiCall(action, {
        method: 'POST',
        body: data
    });

    saveBtn.disabled = false;
    saveBtn.textContent = data.id ? 'Update Book' : 'Save Book';

    if (!result.success) {
        showMessage(result.message, 'error');
        return;
    }

    showMessage(result.message);
    resetForm();
    await loadCategories();
    await loadBooks();
});

resetBtn.addEventListener('click', resetForm);

tableBody.addEventListener('click', async (event) => {
    const editButton = event.target.closest('[data-edit]');
    const deleteButton = event.target.closest('[data-delete]');

    if (editButton) {
        const result = await apiCall('read_one', {
            params: { id: editButton.dataset.edit }
        });

        if (!result.success) {
            showMessage(result.message, 'error');
            return;
        }

        const book = result.data;
        bookId.value = book.id;
        title.value = book.title;
        author.value = book.author;
        category.value = book.category;
        statusField.value = book.status;
        formTitle.textContent = 'Update Book';
        saveBtn.textContent = 'Update Book';
        title.focus();
    }

    if (deleteButton) {
        if (!window.confirm('Delete this book record?')) {
            return;
        }

        const result = await apiCall('delete', {
            method: 'POST',
            body: { id: deleteButton.dataset.delete }
        });

        if (!result.success) {
            showMessage(result.message, 'error');
            return;
        }

        showMessage(result.message);
        await loadCategories();
        await loadBooks();
    }
});

let searchTimer;
search.addEventListener('input', () => {
    window.clearTimeout(searchTimer);
    searchTimer = window.setTimeout(loadBooks, 300);
});

filterCategory.addEventListener('change', loadBooks);
filterStatus.addEventListener('change', loadBooks);

document.addEventListener('DOMContentLoaded', async () => {
    await loadCategories();
    await loadBooks();
});
