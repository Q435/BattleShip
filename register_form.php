<?php
    session_start();
    $js_files = array("simpleajax");
    $css_files = array("common");
    include("include/util.inc.php");

    if (isset($_GET["code"])) {
        $msg = getMsg($_GET["code"]);
    }

    include("include/opening.inc.php");

?>

<form id="form" method="post" action="register.php">
    <h1>Register</h1>

    <?php
        if ((isset($msg))) {
            echo "<p class='register_msg'>$msg</p>";
        }
    ?>

    <div class="field">
        <label for="username">Username:</label>
        <input  type="text" class="input" id="username" placeholder="Max 30 letters or numbers.." name="username" maxlength="30"/>
    </div>

    <div class="field">
        <label for="nickname">Nickname:</label>
        <input  type="text" class="input" placeholder="Max 30 letters or numbers.." id="nickname" name="nickname" maxlength="30"/>
    </div>

    <div class="field">
        <label for="password">Password:</label>
        <input  type="password" class="input" placeholder="Max 30 signs.." id="password" name="password" maxlength="30"/>
    </div>

    <input type="submit" name="submit" id="submit" class="button" value="Submit"\>

</form>

<?php
include("include/closing.html");
?>
