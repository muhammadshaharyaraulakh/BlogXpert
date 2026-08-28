document.addEventListener('DOMContentLoaded', () => {

    const toggleBtn = document.getElementById('sidebar-toggle');
    const mainLayout = document.getElementById('main-layout');

    if (toggleBtn && mainLayout) {
        toggleBtn.addEventListener('click', () => {
            mainLayout.classList.toggle('sidebar-active');
        });
    }

    window.showSection = function (sectionName) {
        // Hide all sections dynamically
        const allSections = document.querySelectorAll('main.content section');
        allSections.forEach(el => el.classList.add('hidden'));

        // Remove active class from all sidebar nav links dynamically
        const allNavLinks = document.querySelectorAll('aside ul li a');
        allNavLinks.forEach(el => el.classList.remove('active'));

        // Activate targeted section and nav
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

