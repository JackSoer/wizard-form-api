<?php
require dirname(__DIR__) . '/App/Utils/Request.php';
require dirname(__DIR__) . '/App/Utils/Validator.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
</head>
<style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    .register {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background-color: green;
      color: white;
      padding: 15px;
      border-radius: 10px;
      gap: 8px;
    }

    div {
      display: flex;
      flex-direction: column;
      width: 100%;

    }

    .title {
      text-align: center;
      margin-bottom: 15px;
    }

    input {
      width: 100%;
      font-size: 16px;
      padding: 5px;
    }

    .register__sbmt {
      width: 100%;
      padding: 8px;
      font-weight: 700;
      background-color: white;
      font-size: 18px;
      cursor: pointer;
      border: none;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .register__sbmt:hover {
      background-color: lightgreen;
    }

    .error {
      color: red;
      font-weight: 700;
      margin-top: 3px;
    }
</style>
<body>
  <h1 class="title">Register</h1>
  <form action="../App/Actions/register.php" method="post" enctype="multipart/form-data" class="register">
    <div>
      <label for="name" class="register__label">Name</label>
      <input
        type="text"
        class="register__input"
        id="name"
        name="name"
        value="<?php echo Request::getOldValue('name') ?>"
        <?php Validator::getErrorAttributes('name')?>
        required>
      <?php if (Validator::hasValidationError('name')): ?>
        <p class="error">
          <?php Validator::getErrorMessage('name')?>
        </p>
      <?php endif;?>
    </div>

    <div>
      <label for="email" class="register__label">Email</label>
      <input
        type="email"
        id="email"
        name="email"
        value="<?php echo Request::getOldValue('email') ?>"
        <?php Validator::getErrorAttributes('email')?>
        required
        class="register__input">
      <?php if (Validator::hasValidationError('email')): ?>
        <p class="error">
          <?php Validator::getErrorMessage('email')?>
        </p>
      <?php endif;?>
    </div>

    <div>
      <label for="password" class="register__label">Password</label>
      <input
        type="password"
        id="password"
        name="password"
        <?php Validator::getErrorAttributes('password')?>
        required
        class="register__input">
      <?php if (Validator::hasValidationError('password')): ?>
        <p class="error">
          <?php Validator::getErrorMessage('password')?>
        </p>
      <?php endif;?>
    </div>

    <div>
      <label for="password_confirmation" class="register__label">Confirm Password</label>
      <input
        type="password"
        id="password_confirmation"
        name="password_confirmation"
        <?php Validator::getErrorAttributes('passwordConfirmation')?>
        required
        class="register__input">
      <?php if (Validator::hasValidationError('passwordConfirmation')): ?>
        <p class="error">
          <?php Validator::getErrorMessage('passwordConfirmation')?>
        </p>
      <?php endif;?>
    </div>

    <div>
      <label for="avatar" class="register__label">Avatar</label>
      <input
        type="file"
        id="avatar"
        name="avatar"
        class="register__input"
        <?php Validator::getErrorAttributes('avatar')?>>
      <?php if (Validator::hasValidationError('avatar')): ?>
        <p class="error">
          <?php Validator::getErrorMessage('avatar')?>
        </p>
      <?php endif;?>
    </div>

    <button class="register__sbmt">Send</button>
  </form>

  <?php Validator::clearValidationSession()?>
 </body>
</html>
