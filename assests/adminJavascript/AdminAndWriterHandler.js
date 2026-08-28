document.addEventListener('submit', async (e) => {
    const form = e.target.closest("#adminRegistration, #writerRegistration");
    if (!form) return;

    e.preventDefault();

    const isProjectAdmin = form.id === "adminRegistration";
    const prefix = isProjectAdmin ? "admin" : "writer";
    const sectionName = isProjectAdmin ? "admins" : "writer";

    form.querySelectorAll(".alert__message").forEach(el => el.textContent = "");

    const formData = new FormData(form);
    const targetUrl = form.getAttribute('action') || form.action;

    try {
        const response = await fetch(targetUrl, { method: "POST", body: formData });
        const data = await response.json();

        if (data.status === "success") {
            showToast(data.message, "success");
            form.reset();

            const containerSelector = isProjectAdmin ? '#view-admins .posts-container' : '#view-writer .posts-container';
            const container = document.querySelector(containerSelector);
            
            if (container && data.data) {
                const article = document.createElement('article');
                article.className = `post-card post-${isProjectAdmin ? 'admin' : 'writer'}`;
                
                const deleteAction = isProjectAdmin ? '/admin/handlers/deleteAdmin.php' : '/admin/handlers/deleteWriter.php';
                const idName = isProjectAdmin ? 'adminId' : 'writerId';
                const roleLabel = isProjectAdmin ? 'Admin' : 'Writer';
                const formClass = isProjectAdmin ? 'deleteAdminForm' : 'deleteWriterForm';

                article.innerHTML = `
                    <div class="post-info-admin">
                        <div class="header__avatar">
                            <img src="${data.data.avatar ? '/userImages/' + data.data.avatar : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.data.first_name) + '&background=random'}" alt="Avatar">
                        </div>
                        <div>
                            <h3 class="post-title" style="font-size: 1rem;">${data.data.first_name}</h3>
                            <small>${roleLabel}</small>
                        </div>
                    </div>
                    <div class="post-actions">
                        <form class="${formClass}" action="${deleteAction}">
                            <input type="hidden" name="${idName}" value="${data.data.id}">
                            <input type="hidden" name="adminImage" value="${data.data.avatar}">
                            <button type="submit" class="icon-btn delete">
                                <i class="uil uil-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                `;
                container.appendChild(article);
            }
        } else {
            if (data.field && data.field !== "general") {
                const errorDiv = form.querySelector(`.${prefix}_${data.field}_error`);
                if (errorDiv) {
                    errorDiv.textContent = data.message;
                } else {
                    showToast(data.message, "error");
                }
            } else {
                showToast(data.message, "error");
            }
        }
    } catch (error) {
        console.error("Error:", error);
        showToast("System Error: Connection failed.", "error");
    }
});

document.addEventListener('input', (e) => {
    const form = e.target.closest("#adminRegistration, #writerRegistration");
    if (form && e.target.name) {
        const prefix = form.id === "adminRegistration" ? "admin" : "writer";
        const errorDiv = form.querySelector(`.${prefix}_${e.target.name}_error`);
        if (errorDiv) {
            errorDiv.textContent = "";
        }
    }
});