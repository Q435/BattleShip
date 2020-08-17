<?php

    session_start();
    include("include/util.inc.php");

    checkLogin();

    $userId = $_SESSION['user_id'];
    $gameId = $_SESSION['game_id'];

    $row = (int)$_POST['row'];
    $col = (int)$_POST['col'];
    $shipLength = (int)$_POST['len'];
    $direction = (int)$_POST['dir'];
    $shipBody = str_repeat("0", $shipLength);
    $array = array('len' => $shipLength, 'row' => $row, 'col' => $col, 'dir' => $direction, 'body' => $shipBody);
    $shipJson = json_encode($array);
    $shipJson = addslashes($shipJson);

    $select_result = sqlSELECT($BOARD_TABLE, null, array("user_id" => $userId), true);
    //$sql = "SELECT * FROM $BOARD_TABLE WHERE user_id = $user_id";
    //$select_result = sqlExecute($sql)->fetch();
    if ($select_result) {
        // ship info already exist
        $board = $select_result['board'];
        $board = json_decode($board, true);
        /*
        if ($direction === HORIZONTAL) {
            for ($i = $col; $i < $col + $shipLength; $i++) {
                $board['row' . $row]['col' . $i] = $shipLength;
            }
        } else if ($direction === VERTICAL) {
            for ($i = $row; $i < $row + $shipLength; $i++) {
                $board['row' . $i]['col' . $col] = $shipLength;
            }
        }

        $board = json_encode($board);
        $sql = "UPDATE $BOARD_TABLE SET ship{$shipLength} = '$shipJson', board = '$board'  WHERE user_id = $user_id";
        */
    } else {
        // no ship info, create a new ship info
        $board = array();
        for ($i = 0; $i < 10; $i++) {
            $board['row' . $i] = array();
            for ($j = 0; $j < 10; $j++) {
                $board['row' . $i]['col' . $j] = 0;
            }
        }

        /*
        if ($direction === HORIZONTAL) {
            for ($i = $col; $i < $col + $shipLength; $i++) {
                $board['row' . $row]['col' . $i] = $shipLength;
            }
        } else if ($direction === VERTICAL) {
            for ($i = $row; $i < $row + $shipLength; $i++) {
                $board['row' . $i]['col' . $col] = $shipLength;
            }
        }

        $board = json_encode($board);
        $sql = "INSERT INTO $BOARD_TABLE (game_id, user_id, ship$len, board, hit_num) VALUES ($game_id, $user_id, '$shipJson', '$board', 0)";
        */
    }

    if ($direction === HORIZONTAL) {
        for ($i = $col; $i < $col + $shipLength; $i++) {
            $board['row' . $row]['col' . $i] = $shipLength;
        }
    } else if ($direction === VERTICAL) {
        for ($i = $row; $i < $row + $shipLength; $i++) {
            $board['row' . $i]['col' . $col] = $shipLength;
        }
    }
    $boardJson = json_encode($board);

$result = array("code" => 100);
    if ($select_result) {
        if (sqlUPDATE($BOARD_TABLE, array("ship" . $shipLength => $shipJson, "board" => $board), array("user_id" => $userId))) {
            $result = array( 'code' => 200, 'col' => $col, 'row' => $row, 'len' => $shipLength, 'dir' => $direction);
        }
        //$sql = "UPDATE $BOARD_TABLE SET ship{$shipLength} = '$shipJson', board = '$board'  WHERE user_id = $user_id";
    } else {
        if (sqlINSERT($BOARD_TABLE, array("game_id" => $gameId, "user_id" => $userId, "ship" . $shipLength => $shipJson,
            "board" => $boardJson))) {
            $result = array( 'code' => 200, 'col' => $col, 'row' => $row, 'len' => $shipLength, 'dir' => $direction);
        }
        //$sql = "INSERT INTO $BOARD_TABLE (game_id, user_id, ship$len, board, hit_num) VALUES ($game_id, $user_id, '$shipJson', '$board', 0)";
    }
    //sqlExecute($sql);
    echo json_encode($result);

?>
