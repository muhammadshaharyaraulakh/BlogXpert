const contactForm = document.getElementById("contactAdminForm");

if (contactForm) {
    contactForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(contactForm);

        try {
            const res = await fetch("/writer/contact.php", {
                method: "POST",
                body: formData
            });

            const data = await res.json();

            showToast(data.message, data.status);

            if (data.status === "success") {
                contactForm.reset();
            }

        } catch (err) {
            console.error(err);
            showToast("Connection failed.", "error");
        }
    });
}
