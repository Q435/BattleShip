<?php

    session_start();
    include("include/util.inc.php");

    /*
    if (!isset($_SESSION['user_id'])) {
        header("login_form.php");
        die();
    }
*/

    if (!isset($_POST['type'])) {
        echo "error date";
    }

    if (!isset($_POST['event'])) {
        echo "error event";
    }

    $type = $_POST['type'];

    if ($type === "game") {
        $event = $_POST['event'];
        if ($event === "place") {
            $row = (int)$_POST['row'];
            $col = (int)$_POST['col'];
            $len = (int)$_POST['len'];
            $dir = (int)$_POST['dir'];
            $hit = "00000";
            $array = array('len' => $len, 'row' => $row, 'col' => $col, 'dir' => $dir, 'hit' => $hit);
            $json = json_encode($array);
            $json = addslashes($json);
            $sql = "SELECT * FROM $BOARD_TABLE WHERE user_id = 1";
            try {
                $sql_result = $PDO->query($sql)->fetch();
            } catch (PDOException $e) {
                echo $e->getMessage() . "   " . $sql;
            }
            if ($sql_result) {
                $board = $sql_result['board'];
                $board = json_decode($board, true);
                if ($dir === 1) {
                    for ($i = $col; $i < $col + $len; $i++) {
                        $board['row' . $row]['col' . $i] = 1;
                    }
                } else {
                    for ($i = $col; $i < $col + $len; $i++) {
                        $board['row' . $row]['col' . $i] = 1;
                    }
                }
                $board = json_encode($board);
                $sql = "UPDATE $BOARD_TABLE SET ship$len = '$json', board = '$board'  WHERE Id = " . $sql_result['Id'];
            } else {
                $board = array();
                for ($i = 0; $i < 10; $i++) {
                    $board['row'.$i] = array();
                    for ($j = 0; $j < 10; $j++) {
                        $board['row' . $i]['col' . $j] = 0;
                    }
                }
                if ($dir === 1) {
                    for ($i = $col; $i < $col + $len; $i++) {
                        $board['row' . $row]['col' . $i] = 1;
                    }
                } else {
                    for ($i = $col; $i < $col + $len; $i++) {
                        $board['row' . $row]['col' . $i] = 1;
                    }
                }
                $board = json_encode($board);
                $sql = "INSERT INTO $BOARD_TABLE (game_id, user_id, ship$len, board) VALUES (1, 1, '$json', '$board')";
            }
            try {
                $PDO->query($sql);
            } catch (PDOException $e) {
                echo $e->getMessage() . "   " . $sql;
            }
            $result = array( 'col' => $col, 'row' => $row, 'len' => $len, 'dir' => $dir);
            echo json_encode($result);
        } elseif ($event === "fire") {
            $row = (int)$_POST['row'];
            $col = (int)$_POST['col'];
            $sql = "SELECT * FROM $BOARD_TABLE WHERE user_id = 2";
            $result = array();
            try {
                $sql_result = $PDO->query($sql)->fetch();
            } catch (PDOException $e) {
                echo $e->getMessage() . "   " . $sql;
                die();
            }
            $board = $sql_result['board'];
            $board = json_decode($board, true);
            $board_statue = $board['row' . $row]['col' . $col];
            if ($board_statue === 0) {
                $result['result'] = 1;
            } elseif ($board_statue === 1 || $board_statue === 2 || $board_statue === 3 || $board_statue === 4 || $board_statue === 5) {
                $ship = $sql_result['ship'.$board_statue];
                $ship = json_decode($ship, true);
                $ship_hit = $ship['hit'];
                $ship_dir = $ship['dir'];
                if ($ship_dir === 1) {
                    $col_id = $ship['col'];
                    $ship_hit[$col_id - $col] = 1;
                    if (!strpos($ship_hit, "0")) {
                        $result['result'] = 3;
                        $result['col'] = $col_id;
                        $result['len'] = $board_statue;
                    } else {
                        $result['result'] = 2;
                    }
                } else {
                    $row_id = $ship['row'];
                    $ship_hit[$row_id - $row] = 1;
                    if (!strpos($ship_hit, "0")) {
                        $result['result'] = 3;
                        $result['row'] = $row_id;
                        $result['len'] = $board_statue;
                    } else {
                        $result['result'] = 2;
                    }
                }
            }
            echo json_encode($result);
        }
    }

?>
