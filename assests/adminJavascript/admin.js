document.addEventListener('DOMContentLoaded', () => {

    const toggleBtn = document.getElementById('sidebar-toggle');
    const mainLayout = document.getElementById('main-layout');

    if (toggleBtn && mainLayout) {
        toggleBtn.addEventListener('click', () => {
            mainLayout.classList.toggle('sidebar-active');
        });
    }

    window.showSection = function (sectionName) {
        const sections = [
            'view-pending',
            'view-all-posts',
            'view-categories',
            'view-admins',
            'view-writer'
        ];

        const navIds = [
            'nav-pending',
            'nav-all-posts',
            'nav-categories',
            'nav-admins',
            'nav-writer'
        ];

        sections.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        });

        navIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.remove('active');
        });

        const activeSection = document.getElementById('view-' + sectionName);
        const activeNav = document.getElementById('nav-' + sectionName);

        if (activeSection) activeSection.classList.remove('hidden');
        if (activeNav) activeNav.classList.add('active');
    };

    const urlParams = new URLSearchParams(window.location.search);
    const sectionToOpen = urlParams.get('section');

    if (sectionToOpen) {
        showSection(sectionToOpen);

        const targetElement = document.getElementById('view-' + sectionToOpen);
        if (targetElement) {
            targetElement.scrollIntoView({ behavior: 'smooth' });
        }
    }

    const filterButtons = document.querySelectorAll('.category-filter-btn');
    const allPosts = document.querySelectorAll('.filterable-post');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {

            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const selectedCategory = btn.dataset.category;

            allPosts.forEach(post => {
                const postCategory = post.dataset.category;

                if (selectedCategory === 'all' || postCategory === selectedCategory) {
                    post.style.display = '';
                } else {
                    post.style.display = 'none';
                }
            });
        });
    });

});

