<?php

    session_start();
    include("include/util.inc.php");

    checkLogin();

    $user_id = $_SESSION['user_id'];

    $row = (int)$_POST['row'];
    $col = (int)$_POST['col'];
    //$id = (int)$_POST['id'];
    $result = array();
    $result['id'] = (int)$_POST['id'];
    $result['col'] = $col;
    $result['row'] = $row;
    $result['len'] = 0;

    $sql = "SELECT * FROM $BOARD_TABLE WHERE user_id = $user_id";
    $select_result = sqlExecute($sql);

    $board = $select_result['board'];
    $board = json_decode($board, true);
    $board_statue = $board['row' . $row]['col' . $col];

    if ($board_statue === 0) {
        $result['result'] = 1;
    } elseif ($board_statue >= 1 && $board_statue <= 5) {
        $ship = $select_result['ship'.$board_statue];
        $ship = json_decode($ship, true);
        $ship_len = $ship['len'];
        $ship_hit = $ship['hit'];
        $ship_dir = $ship['dir'];
        $result['col'] = $col;
        $result['len'] = 0;
        $result['row'] = $row;

        if ($ship_dir === 1) {
            $col_id = $ship['col'];
            $ship_hit[$col_id - $col] = "1";
            $ship['hit'] = $ship_hit;
            $ship_json = json_encode($ship);
            $sql = "UPDATE $BOARD_TABLE SET ship$ship_len = '$ship_json'  WHERE user_id = 2";
            sqlExecute($sql);
            if (stripos($ship_hit, "0") === false) {
                $result['result'] = 3;
                $result['col'] = $ship['col'];
                $result['len'] = $ship['len'];
                $result['row'] = $ship['row'];
                $result['dir'] = 1;
            } else {
                $result['result'] = 2;
            }
        } else {
            $row_id = $ship['row'];
            $ship_hit[$row_id - $row] = "1";
            $ship['hit'] = $ship_hit;
            $ship_json = json_encode($ship);
            $sql = "UPDATE $BOARD_TABLE SET ship$ship_len = '$ship_json'  WHERE user_id = 2";
            sqlExecute($sql);
            if (stripos($ship_hit, "0") === false) {
                $result['result'] = 3;
                $result['col'] = $ship['col'];
                $result['len'] = $ship['len'];
                $result['row'] = $ship['row'];
                $result['dir'] = 2;
            } else {
                $result['result'] = 2;
            }
        }
    }
    echo json_encode($result);
?>
