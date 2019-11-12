<?php
$title = "TOP";

require "./php/inc/header.php";
?>


<h1>Nude</h1>

<div id="signup">
  <h2>Sign Up - 新規受付</h2>
  <form method="post" action="./board.php">
    <div class="form">
      <label for="name">Name</label>
      <input required type="text" name="name" placeholder="input your name">
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
      <input type="submit" value="submit" name="signin_submit">
    </div>
  </form>
</div>

<?php
require "./php/inc/footer.php"
?>