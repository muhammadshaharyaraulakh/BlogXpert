document.addEventListener("submit", async function (e) {
    const deleteForm = e.target.closest(".deleteAdminForm, .deleteWriterForm");
    if (!deleteForm) return;

    e.preventDefault();
    const sectionToRefresh = deleteForm.classList.contains('deleteAdminForm') ? 'admins' : 'writer';
    const targetUrl = deleteForm.getAttribute('action') || deleteForm.action;
    const card = deleteForm.closest('.post-admin, .post-writer');
    const formData = new FormData(deleteForm);

    try {
        const response = await fetch(targetUrl, {
            method: "POST",
            body: formData
        });
        const data = await response.json();

        showToast(data.message, data.status);

        if (data.status === 'success') {
            if (card) {
                card.style.transition = "all 0.4s ease";
                card.style.opacity = "0";
                card.style.transform = "scale(0.9)";
            }
            setTimeout(() => {
                if (card) card.remove();
                const newUrl = window.location.pathname + "?section=" + sectionToRefresh;
                window.history.pushState({ path: newUrl }, '', newUrl);
                showSection(sectionToRefresh);
            }, 1500);
        }
    } catch (error) {
        showToast("Server error occurred.", "error");
    }
});