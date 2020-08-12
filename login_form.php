<?php
    session_start();
    $js_files = array("simpleajax");
    $css_files = array("common");
    include("include/util.inc.php");
    include("include/opening.inc.php");

    if (isset($_GET["error_code"])) {
        $msg = errorMsg($_GET["error_code"]);
    }

?>

<form id="form" method="post" action="login.php">
    <h1>Login</h1>

    <?php
    if ((isset($msg))) {
        echo "<p class='error_msg'>$msg</p>";
    }
    ?>

    <div class="field">
        <label for="username">Username:</label>
        <input type="text" class="input" id="username" placeholder="Please input username" name="username" maxlength="30"/>
    </div>

    <div class="field">
        <label for="password">Password:</label>
        <input type="password" class="input" placeholder="Please input password" id="password" name="password" maxlength="30"/>
    </div>

    <input type="submit" name="login" id="login" class="button" value="Login" />

</form>

<?php
include("include/closing.html");
?>
