<?php 
require __DIR__."/../../includes/header.php";
?>

<section class="form__section">

    <div class="container form__section-container">
        <h2>Register Account</h2>
        <form action="/auth/signup/register.php" enctype="multipart/form-data" id="registerForm">
            
            <input type="text" name="firstname" placeholder="First Name">
            <div class="alert__message error firstname_error"></div>

            <input type="text" name="lastname" placeholder="Last Name">
            <div class="alert__message error lastname_error"></div>

            <input type="text" name="username" placeholder="Username">
            <div class="alert__message error username_error"></div>

            <input type="email" name="email" placeholder="Email">
            <div class="alert__message error email_error"></div>

            <input type="password" name="password" placeholder="Create Password">
            <div class="alert__message error password_error"></div>

            <input type="password" name="confirmpassword" placeholder="Confirm Password">
            <div class="alert__message error confirmpassword_error"></div>

            <div class="form__control">
                <label for="avatar">User Avatar</label>
                <input type="file" name="avatar" id="avatar">
                <div class="alert__message error avatar_error"></div>
            </div>

            <button type="submit" class="btn">Sign Up</button>
            
            <div class="alert__message error registration_error"></div>

           
        </form>
    </div>

</section>

<script>
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
      const response = await fetch("handler.php", {
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
</script>

<?php 
require __DIR__."/../../includes/footer.php";
?>