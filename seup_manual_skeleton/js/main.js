document.addEventListener('DOMContentLoaded', function() {
    initSearch();
    initManualSections();
    initNotificationFilters();
    initAdminForms();
});

function initSearch() {
    const searchInput = document.getElementById('manualSearch');
    if (!searchInput) return;

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const sections = document.querySelectorAll('.manual-section');

        sections.forEach(section => {
            const title = section.querySelector('h3').textContent.toLowerCase();
            const content = section.querySelector('.content').textContent.toLowerCase();

            if (title.includes(searchTerm) || content.includes(searchTerm)) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    });
}

function initManualSections() {
    const sections = document.querySelectorAll('.manual-section h3');

    sections.forEach(heading => {
        heading.addEventListener('click', function() {
            const content = this.nextElementSibling;
            if (content && content.classList.contains('content')) {
                if (content.style.display === 'none') {
                    content.style.display = 'block';
                } else {
                    content.style.display = 'none';
                }
            }
        });
    });
}

function initNotificationFilters() {
    const filterButtons = document.querySelectorAll('.filter-tabs button');
    const notifications = document.querySelectorAll('.notification');

    if (filterButtons.length === 0) return;

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');

            notifications.forEach(notification => {
                if (filter === 'all') {
                    notification.style.display = 'block';
                } else if (notification.classList.contains(filter)) {
                    notification.style.display = 'block';
                } else {
                    notification.style.display = 'none';
                }
            });
        });
    });
}

function initAdminForms() {
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Jeste li sigurni da zelite obrisati ovaj zapis?')) {
                e.preventDefault();
            }
        });
    });

    const toggleButtons = document.querySelectorAll('.btn-toggle');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            if (row) {
                row.classList.toggle('inactive');
            }
        });
    });
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.innerHTML = `<p>${message}</p>`;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}
