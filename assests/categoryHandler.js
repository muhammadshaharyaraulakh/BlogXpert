document.addEventListener('DOMContentLoaded', () => {
    const addCategoryForm = document.querySelector('#addCategoryForm');
    if (addCategoryForm) {
        addCategoryForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            document.addEventListener("input", () => {
                document.querySelector(".alert__message").style.display = "none";
            })
            const titleError = addCategoryForm.querySelector('.title_error');
            const formData = new FormData(addCategoryForm);

            try {
                const response = await fetch('/admin/handlers/addCategory.php', { method: 'POST', body: formData });
                const data = await response.json();

                if (data.status === 'success') {
                    showToast(data.message, 'success');
                    addCategoryForm.reset();
                    setTimeout(() => {
                        window.location.href = window.location.pathname + "?section=categories";
                    }, 2000);
                } else {
                    if (data.field === 'title') {
                        titleError.textContent = data.message;
                        titleError.style.display = 'block';
                    } else {
                        showToast(data.message, 'error');
                    }
                }
            } catch (error) {
                showToast("Server Error occurred.", "error");
            }
        });
    }
    const deleteForms = document.querySelectorAll('.deleteCategoryForm');
    deleteForms.forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const categoryCard = this.closest('.category-Card');
            const formData = new FormData(this);

            try {
                const response = await fetch('/admin/handlers/deleteCategory.php', { method: 'POST', body: formData });
                const data = await response.json();

                showToast(data.message, data.status);

                if (data.status === 'success') {
                    if (categoryCard) {
                        categoryCard.style.opacity = '0';
                        setTimeout(() => categoryCard.remove(), 300);
                    }
                    setTimeout(() => {
                          window.location.href = window.location.pathname + "?section=categories";
                    }, 2000);
                }
            } catch (error) {
                showToast("Connection failed.", "error");
            }
        });
    });
});