document.addEventListener('DOMContentLoaded', () => {
    const addCategoryForm = document.querySelector('#addCategoryForm');
    if (addCategoryForm) {
        addCategoryForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            document.addEventListener("input", () => {
                document.querySelector(".alert__message").style.display = "none";
            });

            const titleError = addCategoryForm.querySelector('.title_error');
            const formData = new FormData(addCategoryForm);
            const actionUrl = addCategoryForm.getAttribute('action'); 

            try {
                const response = await fetch(actionUrl, { method: 'POST', body: formData });
                const data = await response.json();

                if (data.status === 'success') {
                    showToast(data.message, 'success');
                    addCategoryForm.reset();
                    
                    // Create and append the new category card
                    const postsContainer = document.querySelector('#view-categories .posts-container');
                    if (postsContainer && data.data) {
                        const article = document.createElement('article');
                        article.className = 'post-card category-Card';
                        article.style.padding = '1rem';
                        
                        article.innerHTML = `
                            <div class="post-info">
                                <h3 class="post-title" style="font-size: 1rem;">${data.data.title}</h3>
                            </div>
                            <div class="post-actions">
                                <form class="deleteCategoryForm" action="/admin/handlers/deleteCategory.php">
                                    <input type="hidden" name="id" value="${data.data.id}">
                                    <button class="icon-btn delete" type="submit"><i class="uil uil-trash-alt"></i></button>
                                </form>
                            </div>
                        `;
                        postsContainer.appendChild(article);
                    }
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

    // Use event delegation for delete forms since they can be added dynamically
    document.addEventListener('submit', async function (e) {
        if (e.target && e.target.classList.contains('deleteCategoryForm')) {
            e.preventDefault();
            const form = e.target;
            const categoryCard = form.closest('.category-Card');
            const formData = new FormData(form);
            const actionUrl = form.getAttribute('action'); 

            try {
                const response = await fetch(actionUrl, { method: 'POST', body: formData });
                const data = await response.json();

                showToast(data.message, data.status);

                if (data.status === 'success') {
                    if (categoryCard) {
                        categoryCard.style.transition = 'opacity 0.3s ease';
                        categoryCard.style.opacity = '0';
                        setTimeout(() => categoryCard.remove(), 300);
                    }
                }
            } catch (error) {
                showToast("Connection failed.", "error");
            }
        }
    });
});
