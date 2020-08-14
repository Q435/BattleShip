<?php

    session_start();
    include("include/util.inc.php");

    checkLogin();

    $user_id = $_SESSION['user_id'];
    $game_id = $_SESSION['game_id'];

    $row = (int)$_POST['row'];
    $col = (int)$_POST['col'];
    $len = (int)$_POST['len'];
    $dir = (int)$_POST['dir'];
    $hit = str_repeat("0", $len);
    $array = array('len' => $len, 'row' => $row, 'col' => $col, 'dir' => $dir, 'hit' => $hit);
    $json = json_encode($array);
    $json = addslashes($json);
    $sql = "SELECT * FROM $BOARD_TABLE WHERE user_id = $user_id";
    $select_result = sqlExecute($sql);
    if ($select_result) {
        $board = $select_result['board'];
        $board = json_decode($board, true);
        if ($dir == 1) {
            for ($i = $col; $i < $col + $len; $i++) {
                $board['row' . $row]['col' . $i] = $len;
            }
        } else {
            for ($i = $row; $i < $row + $len; $i++) {
                $board['row' . $i]['col' . $col] = $len;
            }
        }
        $board = json_encode($board);
        $sql = "UPDATE $BOARD_TABLE SET ship$len = '$json', board = '$board'  WHERE user_id = $user_id";
    } else {
        $board = array();
        for ($i = 0; $i < 10; $i++) {
            $board['row'.$i] = array();
            for ($j = 0; $j < 10; $j++) {
                $board['row' . $i]['col' . $j] = 0;
            }
        }
        if ($dir == 1) {
            for ($i = $col; $i < $col + $len; $i++) {
                $board['row' . $row]['col' . $i] = $len;
            }
        } else {
            for ($i = $row; $i < $row + $len; $i++) {
                $board['row' . $i]['col' . $col] = $len;
            }
        }
        $board = json_encode($board);
        $sql = "INSERT INTO $BOARD_TABLE (game_id, user_id, ship$len, board) VALUES ($game_id, $user_id, '$json', '$board')";
    }
    try {
        $PDO->query($sql);
    } catch (PDOException $e) {
        echo $e->getMessage() . "   " . $sql;
    }
    $result = array( 'col' => $col, 'row' => $row, 'len' => $len, 'dir' => $dir);
    echo json_encode($result);

?>
