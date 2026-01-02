document.addEventListener('submit', function (e) {
    const form = e.target.closest('.approve-post, .deletePostForm');
    if (!form) return;

    e.preventDefault();

    const card = form.closest('.pending-card, .filterable-post');
    const sectionParam = card && card.classList.contains('pending-card') ? 'pending' : 'all-posts';
    const submitBtn = form.querySelector('button');
    const formData = new FormData(form);

    if (submitBtn) submitBtn.disabled = true;

    fetch(form.action || form.getAttribute('action'), {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {

            showToast(data.message, data.status);

            if (data.status === 'success') {

                if (card) {
                    card.style.transition = 'opacity 0.3s ease';
                    card.style.opacity = '0';
                    setTimeout(() => card.remove(), 300);
                }

                setTimeout(() => {
                    const url = new URL(window.location.href);
                    url.searchParams.set('section', sectionParam);
                    window.location.href = url.toString();
                }, 2000);
            } else {

                if (submitBtn) submitBtn.disabled = false;
            }
        })
        .catch(err => {
            showToast("System Error: Could not process request.", "error");
            if (submitBtn) submitBtn.disabled = false;
        });
});