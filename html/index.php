<?php
$title = "TOP";

require "./php/inc/header.php";
?>

<div class="wrapper">

  <h1>Nude</h1>

  <ul class="menu_list">
    <li class="item">Sing up</li>
    <li class="item">Login</li>
  </ul>

  <div id="signup">
    <h2>Sign in</h2>
    <form method="post" action="./board.php">
      <div class="form">
        <label for="name">Name</label>
        <input required type="text" name="name" placeholder="input your name">
      </div>
      <div class="form">
        <label for="mail">Mail</label>
        <input required type="email" name="mail" placeholder="your e-mail">
      </div>
      <div class="form">  
        <label for="pass">Pass</label>
        <input required type="password" name="pass" placeholder="pass min 4words" minlength="4">
      </div>  
      <div class="form">   
        <label for="pass_repeat">Pass Repeat</label>
        <input required type="password" name="pass_repeat" placeholder="please input pass repeat" minlength="4">
      </div>
      <div class="form">
        <input type="submit" value="Sign in" name="signin_submit">
      </div>
    </form>
  </div>

  <div id="login">
    <h2>Login</h2>
    <form method="post" action="./board.php">
      <div class="form">
        <label for="login_mail">Mail</label>
        <input required type="email" name="login_mail" placeholder="input your e-mail">
      </div>
      <div class="form">
        <label form="login_pass">Pass</label>
        <input required type="password" name="login_pass" placeholder="input your pass">
      </div>
      <div class="form">
        <input type="submit" value="Login" name="login_submit">
      </div>
    </form>
  </div>

</div>

<?php
require "./php/inc/footer.php"
?>