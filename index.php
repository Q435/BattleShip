<?php
    session_start();
    include("include/util.inc.php");
    include("include/opening.inc.php");
?>

    <h1>Welcome to Battleship TIEI-2020</h1>
    <button class="button online">Online game</button>
    <br>
    <button class="button ai">AI game</button>

<?php
    if (isset($_SESSION["user_id"])) {
        echo "<br><button class='button record'>My record</button>";
    }
?>

<?php
    include("include/closing.html");
?>
