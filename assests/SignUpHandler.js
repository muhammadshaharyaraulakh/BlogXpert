
  const form = document.querySelector("#registerForm");
  const globalError = document.querySelector(".registration_error");

  const errorElements = {
    firstname: document.querySelector(".firstname_error"),
    lastname: document.querySelector(".lastname_error"),
    username: document.querySelector(".username_error"),
    email: document.querySelector(".email_error"),
    password: document.querySelector(".password_error"),
    confirmpassword: document.querySelector(".confirmpassword_error"),
    avatar: document.querySelector(".avatar_error")
  };

  const clearErrors = () => {
    Object.values(errorElements).forEach(el => {
      if (el) el.textContent = "";
    });
    globalError.textContent = "";
    globalError.style.color = "red";
  };

  form.querySelectorAll("input").forEach(input => {
    input.addEventListener("input", clearErrors);
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    clearErrors();

    const formData = new FormData(form);

    try {
      const response = await fetch("/auth/signup/handler.php", {
        method: "POST",
        body: formData
      });

      const data = await response.json();

      if (data.status === "success") {
        if (data.redirect && data.redirect.trim() !== "") {
          window.location.href = data.redirect;
        } else {
          globalError.style.color = "green";
          globalError.textContent = data.message;
          form.reset();
        }
      } else {
        if (errorElements[data.field]) {
          errorElements[data.field].textContent = data.message;
        } else {
          globalError.textContent = data.message;
        }
      }
    } catch (error) {
      globalError.textContent = "Server error. Please try again.";
      console.error(error);
    }
  });
