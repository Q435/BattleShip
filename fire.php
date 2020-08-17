<?php

    session_start();
    include("include/util.inc.php");

    checkLogin();

    $userId = $_SESSION['user_id'];
    $player = $_SESSION['player'];

    $row = (int)$_POST['row'];
    $col = (int)$_POST['col'];
    //$id = (int)$_POST['id'];
    $result = array();
    $result['id'] = (int)$_POST['id'];
    $result['col'] = $col;
    $result['row'] = $row;
    $result['len'] = 0;

    $opponentId = $_SESSION['opponent_id'];
    $select_result = sqlSELECT($BOARD_TABLE, null, array("user_id" => $opponentId), true);
    //$sql = "SELECT * FROM $BOARD_TABLE WHERE user_id = $opponentId";
    //$select_result = sqlExecute($sql);

    $board_id = $select_result['Id'];
    $board = $select_result['board'];
    $board = json_decode($board, true);
    $board_statue = $board['row' . $row]['col' . $col];

    sqlUPDATE($BOARD_TABLE, array("total_fire" => "total_fire + 1"), array("user_id" => $userId));

    if ($board_statue === 0) {
        $result['result'] = 1;
        $board['row' . $row]['col' . $col] = 8;
    } elseif ($board_statue >= 1 && $board_statue <= 5) {
        sqlUPDATE($BOARD_TABLE, array("hit_fire" => "hit_fire + 1"), array("user_id" => $userId));
        $ship = $select_result['ship'.$board_statue];
        $ship = json_decode($ship, true);
        $shipLength = $ship['len'];
        $shipHit = $ship['hit'];
        $shipDir = $ship['dir'];
        $result['col'] = $col;
        $result['len'] = 0;
        $result['row'] = $row;
        $board['row' . $row]['col' . $col] = 6;

        if ($shipDir === HORIZONTAL) {
            $col_id = $ship['col'];
            $shipHit[$col_id - $col] = "1";
            $ship['hit'] = $shipHit;
            $ship_json = json_encode($ship);

            sqlUPDATE($BOARD_TABLE, array("ship" . $shipLength => $ship_json), array("user_id" => $opponentId));

            //$sql = "UPDATE $BOARD_TABLE SET ship$ship_len = '$ship_json'  WHERE user_id = $opponentId";
            //sqlExecute($sql);
            if (stripos($shipHit, "0") === false) {
                $result['result'] = 3;
                $result['col'] = $ship['col'];
                $result['len'] = $ship['len'];
                $result['row'] = $ship['row'];
                $result['dir'] = 1;
                for($i = $ship['col']; $i < $ship['col'] + $ship['len']; $i++) {
                    $board['row' . $ship['row']]['col' . $i] = 7;
                }
                sqlUpdateHit($userId, $opponentId);
            } else {
                $result['result'] = 2;
            }
        } else if ($shipDir === VERTICAL) {
            $row_id = $ship['row'];
            $shipHit[$row_id - $row] = "1";
            $ship['hit'] = $shipHit;
            $ship_json = json_encode($ship);

            sqlUPDATE($BOARD_TABLE, array("ship" . $shipLength => $ship_json), array("user_id" => $opponentId));

            //$sql = "UPDATE $BOARD_TABLE SET ship$shipLength = '$ship_json'  WHERE user_id = $opponentId";
            //sqlExecute($sql);
            if (stripos($shipHit, "0") === false) {
                $result['result'] = 3;
                $result['col'] = $ship['col'];
                $result['len'] = $ship['len'];
                $result['row'] = $ship['row'];
                $result['dir'] = 2;
                for($i = $ship['row']; $i < $ship['row'] + $ship['len']; $i++) {
                    $board['row' . $i]['col' . $ship['col']] = 7;
                }
                sqlUpdateHit($userId, $opponentId);
            } else {
                $result['result'] = 2;
            }
        }
    }

    sqlUpdateBoard($board, $board_id);
    changePlayer($userId, $player);

    echo json_encode($result);
?>
