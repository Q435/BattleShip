<?php
    session_start();
    $css_files = array("common");
    include("include/util.inc.php");
    checkLogin();
    include("include/opening.inc.php");
    $userId = $_SESSION['user_id'];
?>

<h1>Record</h1>
<hr>

<?php

    $info = getUserGameInfo($userId);
    echo "<p>Total games: {$info['total_game']} | Win games: {$info['win_game']} | Lose games: {$info['lose_game']}</p>";

?>

<div class="table">
    <table>
    <tr><th>No.</th><th>Opponent name</th><th>Total fired times</th><th>Hit fired times</th><th>Accuracy</th><th>Date</th><th>Board</th></tr>
<?php
    $records = getAllRecord($userId);
    $i = 1;
    foreach ($records as $record) {
        echo makeTr($i, $record);
        $i++;
    }
?>
    </table>
</div>
<?php
include("include/closing.html");
?>
