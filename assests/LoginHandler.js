
    const loginForm = document.querySelector(".login");

    if (loginForm) {
        const emailError = document.querySelector(".gmail_error");
        const passwordError = document.querySelector(".password_error");
        const loginError = document.querySelector(".login_error");

        loginForm.querySelectorAll("input").forEach(input => {
            input.addEventListener("input", () => {
                emailError.textContent = "";
                passwordError.textContent = "";
                loginError.textContent = "";
            });
        });

        loginForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            const formData = new FormData(loginForm);

            try {
                const response = await fetch("/auth/login/handler.php", {
                    method: "POST",
                    body: formData
                });

                const data = await response.json();

                if (data.status === "success") {
                    window.location.href = data.redirect;
                } else {
                    if (data.field === "email") {
                        emailError.textContent = data.message;
                    } else if (data.field === "password") {
                        passwordError.textContent = data.message;
                    } else {
                        loginError.textContent = data.message;
                    }
                }
            } catch (error) {
                console.error("Error:", error);
                loginError.textContent = "Server error. Please try again.";
            }
        });
    } else {
        console.error("Could not find the form element on this page.");
    }
