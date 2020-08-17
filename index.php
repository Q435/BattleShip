<?php
    session_start();
    $css_files = array("common");
    include("include/util.inc.php");
    include("include/opening.inc.php");
?>

    <h1>Welcome to Battleship TIEI-2020</h1>
    <button class="button online" onclick="window.location.href='room.php'">Online game</button>
    <br>
    <button class="button ai">AI game</button>

<?php
    if (isset($_SESSION["user_id"])) {
        echo "<br><button class=\"button record\" onclick=\"window.location.href='record.php'\">My record</button>";
    }
?>

<?php
    include("include/closing.html");
?>
