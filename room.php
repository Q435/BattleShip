<?php
    session_start();
    $js_files = array("simpleajax", "room");
    $css_files = array("common");
    include("include/util.inc.php");

    checkLogin();
    checkGame();

    include("include/opening.inc.php");
?>

    <h1>Online Game</h1>
    <button class="button" id="btn_new">New Game</button>
    <div class="current-games-container">
        <hr>
        <h3>Current Games</h3>
        <ul class="current-games">
        <span id="current-games-list"></span>
        </ul>
    </div>

<?php
    include("include/closing.html");
?>
