<?php 
require __DIR__."/../../includes/header.php";
?>

<section class="form__section">

  <div class="container form__section-container">
    <h2>Log In</h2>

    <form method="post" class="login" novalidate>
      <input type="email" placeholder="Email" name="email">
      <div class="alert__message error gmail_error"></div>

      <input type="password" placeholder="Password" name="password">
      <div class="alert__message error password_error"></div>

      <button type="submit" class="btn">Log in</button>
      <div class="alert__message error login_error"></div>

      <small>
        Don't have an account?
        <a href="/auth/signup/register.php">Sign up</a>
      </small>
    </form>
  </div>

</section>




<?php 
require __DIR__."/../../includes/footer.php";
?>