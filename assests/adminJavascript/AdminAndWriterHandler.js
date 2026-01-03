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

            setTimeout(() => {
                window.location.href = window.location.pathname + "?section=" + sectionName;
            }, 2000);

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