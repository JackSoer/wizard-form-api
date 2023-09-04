<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
</head>
<style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    .header {
      position: fixed;
      top: 0;
      right: 0;
      left: 0;
      height: 50px;
      background-color: green;
      padding: 0 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .header__title {
      height: 100%;
      color: white;
      display: flex;
      align-items: center;
    }

    .nav__item {
      font-size: 20px;
      color: white;
      background-color: white;
      border: 1px solid lightgreen;
      border-radius: 10px;
      padding: 10px;
      color: green;
    }
</style>
<body>
    <header class="header">
      <h1 class="header__title">Home</h1>
      <nav class="nav">
        <a href="./register.php" class="nav__item">
          Register
        </a>
      </nav>
    </header>
</body>
</html>
