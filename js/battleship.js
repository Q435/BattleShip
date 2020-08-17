window.onload = function () {

    var orientation = 1; // 1 Horizontal 2 Vertical
    var ship_length = 0;
    var game_state = 0;
    var ships = new Array(5);
    var ship_place = 0;
    var MY_GRIDS = new Array(10);
    var OPPONENT_GRIDS = new Array(10);
    var FIRE = 0;


    function loadBattle() {
        ajaxRequest("server.php", "post", { type: "game", event: "query", mode: "battle"},
            function () {
                let result = JSON.parse(this.responseText);
                if (result.code === 200) {
                    if (result.current_player === "you") {
                        FIRE = 1;
                        document.querySelector(".game_header > h1").innerText = "Your turn";
                        document.querySelector(".game_header > p").innerText = "Fire on your opponent";
                    } else if (result.current_player === "opponent") {
                        FIRE = 0;
                        document.querySelector(".game_header > h1").innerText = "Opponent turn";
                        document.querySelector(".game_header > p").innerText = "Wait for your opponent to fire";
                    }
                    let board = JSON.parse(result.board);
                    let my_board = document.querySelector("#my_board");
                    for (let i = 0; i < 10; i++) {
                        for (let j = 0; j < 10; j++) {
                            let cell = my_board.querySelector("#\\" + (i + 30) + " \\"+ (j + 30) + " ");
                            let cell_satus = board['row' + i]['col' + j];
                            if (cell_satus === 8) {
                                cell.classList.add("miss");
                            } else if (cell_satus === 6) {
                                cell.classList.add("hit");
                            } else if (cell_satus === 7) {
                                cell.classList.add("dead");
                            }
                        }
                    }
                } else if (result.code === 201) {
                    FIRE = 0;
                    document.querySelector(".game_header > p").innerText = "";
                    if (result.win === "you") {
                        document.querySelector(".game_header > h1").innerText = "You Win";
                    } else {
                        document.querySelector(".game_header > h1").innerText = "Opponent Win";
                    }
                }
                window.setTimeout(loadBattle, 1000);
            }, function () {
                console.log("loadBattle error");
                window.setTimeout(loadBattle, 1000);
            });
    }

    function loadOpponent() {
        ajaxRequest("server.php", "post", { type: "game", event: "query", mode: "opponent"},
            function () {
                let result = JSON.parse(this.responseText);
                if (result.code === 201) {
                    document.querySelector(".game_header > h1").innerText = "Looking for opponent";
                    document.querySelector(".game_header > p").innerText = "Wait for the opponent to join the battle";
                    window.setTimeout(loadOpponent, 1000);
                } else if (result.code === 202) {
                    document.querySelector(".game_header > h1").innerText = "Waiting for opponent";
                    document.querySelector(".game_header > p").innerText = "Battle will start as soon as your opponent is ready";
                    window.setTimeout(loadOpponent, 1000);
                } else if (result.code === 203) {
                    let opponent_board = document.querySelector("#opponent_board");
                    opponent_board.style.visibility = "visible";
                    for (let i = 0; i < 10; i++) {
                        for (let j = 0; j < 10; j++) {
                            let grid = opponent_board.querySelector("#\\" + (i + 30) + " \\"+ (j + 30) + " ");
                            grid.onclick = fire;
                        }
                    }
                    if (result.current_player === "you") {
                        FIRE = 1;
                        document.querySelector(".game_header > h1").innerText = "Your turn";
                        document.querySelector(".game_header > p").innerText = "Fire on your opponent";
                    } else if (result.current_player === "opponent") {
                        FIRE = 0;
                        document.querySelector(".game_header > h1").innerText = "Opponent turn";
                        document.querySelector(".game_header > p").innerText = "Wait for your opponent to fire";
                    }
                    window.setTimeout(loadBattle, 1000);
                }
            }, function () {
                console.log("loadOpponent error");
                window.setTimeout(loadOpponent, 1000);
            });
    }

    function place(row, col, len, dir) {
        ajaxRequest("place.php", "post",
            { row: row, col: col, len: len, dir: dir },
            function () {
                let result = JSON.parse(this.responseText);
                if (result.code === 200) {
                    let col_id = result.col;
                    let row_id = result.row;
                    let dir = result.dir;
                    let len = result.len;
                    for (let i = 0; i < 10; i++) {
                        MY_GRIDS[i] = new Array(10);
                        OPPONENT_GRIDS[i] = new Array(10);
                        for (let j = 0; j < 10; j++) {
                            let grid = document.getElementById("" + i + j);
                            grid.classList.remove("ship-prepare");
                            grid.classList.remove("ship-invalid");
                        }
                    }

                    if (dir == 1) {
                        for (let i = col_id; i < col_id + len; i++) {
                            let cell = document.getElementById("" + row_id + i);
                            cell.classList.remove("ship-prepare");
                            cell.classList.add("ship");
                            MY_GRIDS[row_id][i] = 1;
                        }
                    } else {
                        for (let i = row_id; i < row_id + len; i++) {
                            let cell = document.getElementById("" + i + col_id);
                            cell.classList.remove("ship-prepare");
                            cell.classList.add("ship");
                            MY_GRIDS[i][col_id] = 1;
                        }
                    }
                    let li = ships[len - 1].parentNode;
                    let ul = li.parentNode;
                    ul.removeChild(li);
                    ship_length = 0;
                    if (ul.childElementCount === 0) {
                        game_state = 1; // Complete ship placement
                        let ship_selector = document.getElementById("ship_selector");
                        ship_selector.parentNode.removeChild(ship_selector);
                        document.querySelector(".game_header > h1").innerText = "Looking for opponent";
                        document.querySelector(".game_header > p").innerText = "Wait for the opponent to join the battle";
                        ajaxRequest("server.php", "post", {type: "game", event: "update", statue: "ready"}, null, null);
                        window.setTimeout(loadOpponent, 1000);
                        /*
                        let opponent_board = document.querySelector("#opponent_board");
                        opponent_board.style.visibility = "visible";
                        for (let i = 0; i < 10; i++) {
                            for (let j = 0; j < 10; j++) {
                                let grid = opponent_board.querySelector("#\\" + (i + 30) + " \\"+ (j + 30) + " ");
                                grid.onclick = fire;
                            }
                        }
                        */
                    }
                } else {
                    alert("Place ship error, please try again!");
                }
            },
            function () {
                console.log("Place ship error!");
            });
    }

    function fire() {
        if (FIRE === 1) {
            let cell_id = this.getAttribute("id");
            let col_id = cell_id % 10;
            let row_id = parseInt(cell_id / 10);
            ajaxRequest("fire.php", "post", {row: row_id, col: col_id, id: cell_id},
                function () {
                    let result = JSON.parse(this.responseText);
                    let status = result.result;
                    let col_id = result.col;
                    let row_id = result.row;
                    let len = result.len;
                    let opponent_board = document.querySelector("#opponent_board");
                    let cell = opponent_board.querySelector("#\\" + (row_id + 30) + " \\" + (col_id + 30) + " ");
                    cell.onclick = null;
                    if (status === 1) {
                        cell.classList.add("miss");
                    } else if (status === 2) {
                        cell.classList.add("hit");
                    } else if (status === 3) {
                        let dir = result.dir;
                        if (dir === 1) {
                            for (let i = col_id; i < col_id + len; i++) {
                                let cell = opponent_board.querySelector("#\\" + (row_id + 30) + " \\" + (i + 30) + " ");
                                cell.classList.remove("hit");
                                cell.classList.add("dead");
                            }
                        } else {
                            for (let i = row_id; i < row_id + len; i++) {
                                let cell = opponent_board.querySelector("#\\" + (i + 30) + " \\" + (col_id + 30) + " ");
                                cell.classList.remove("hit");
                                cell.classList.add("dead");
                            }
                        }
                    }
                }, function () {

                });
        }
    }

    function selectShip() {
        if (this.parentNode.classList.contains("active")) {
            if (orientation === 1) {
                orientation = 2;
                document.getElementsByClassName("orientation")[0].innerText = "vertical";
            } else {
                orientation = 1;
                document.getElementsByClassName("orientation")[0].innerText = "horizontal";
            }
        } else {
            let last_ship = document.getElementsByClassName("active");
            for (let i = 0; i < last_ship.length; i++) {
                last_ship[i].classList.remove("active");
            }
            orientation = 1;
            document.getElementsByClassName("orientation")[0].innerText = "horizontal";
            ship_length = this.childElementCount;
            this.parentNode.classList.add("active");
        }
    }

    function checkShipGrid(col_id, row_id) {
        if (orientation === 1) {
            for (let i = col_id; i < col_id + ship_length; i++) {
                let cell = document.getElementById("" + row_id + i);
                if (cell.classList.contains("ship")) {
                    return false;
                }
            }
        } else {
            for (let i = row_id; i < row_id + ship_length; i++) {
                let cell = document.getElementById("" + i + col_id);
                if (cell.classList.contains("ship")) {
                    return false;
                }
            }
        }
        return true;
    }

    function selectCell() {
        ship_place = 0;
        let cell_id = this.getAttribute("id");
        let col_id = cell_id % 10;
        let row_id = parseInt(cell_id / 10);
        if (orientation === 1) {
            if ((col_id + ship_length - 1) > 9 || !checkShipGrid(col_id, row_id)) {
                for (let i = col_id; i < (col_id + ship_length < 10 ? col_id + ship_length : 10); i++) {
                    let cell = document.getElementById("" + row_id + i);
                    cell.classList.add("ship-invalid");
                }
            } else {
                for (let i = col_id; i < col_id + ship_length; i++) {
                    let cell = document.getElementById("" + row_id + i);
                    cell.classList.add("ship-prepare");
                    ship_place = 1;
                }
            }
        } else {
            if ((row_id + ship_length - 1) > 9 || !checkShipGrid(col_id, row_id)) {
                for (let i = row_id; i < (row_id + ship_length < 10 ? row_id + ship_length : 10); i++) {
                    let cell = document.getElementById("" + i + col_id);
                    cell.classList.add("ship-invalid");
                }
            } else {
                for (let i = row_id; i < row_id + ship_length; i++) {
                    let cell = document.getElementById("" + i + col_id);
                    cell.classList.add("ship-prepare");
                    ship_place = 1;
                }
            }
        }
    }

    function removeCell() {
        ship_place = 0;
        let cell_id = this.getAttribute("id");
        let col_id = cell_id % 10;
        let row_id = parseInt(cell_id / 10);
        if (orientation === 1) {
            if ((col_id + ship_length - 1) > 9  || !checkShipGrid(col_id, row_id)) {
                for (let i = col_id; i < 10; i++) {
                    let cell = document.getElementById("" + row_id + i);
                    cell.classList.remove("ship-invalid");
                }
            } else {
                for (let i = col_id; i < col_id + ship_length; i++) {
                    let cell = document.getElementById("" + row_id + i);
                    cell.classList.remove("ship-prepare");
                }
            }
        } else {
            if ((row_id + ship_length - 1) > 9  || !checkShipGrid(col_id, row_id)) {
                for (let i = row_id; i < 10; i++) {
                    let cell = document.getElementById("" + i + col_id);
                    cell.classList.remove("ship-invalid");
                }
            } else {
                for (let i = row_id; i < row_id + ship_length; i++) {
                    let cell = document.getElementById("" + i + col_id);
                    cell.classList.remove("ship-prepare");
                }
            }
        }
    }

    function placeShip() {
        if (game_state === 0 && ship_place === 1) {
            // place ship state
            let cell_id = this.getAttribute("id");
            let col_id = cell_id % 10;
            let row_id = parseInt(cell_id / 10);
            place(row_id, col_id, ship_length, orientation);

            /*
            if (orientation === 1) {
                for (let i = col_id; i < col_id + ship_length; i++) {
                    let cell = document.getElementById("" + row_id + i);
                    cell.classList.remove("ship-prepare");
                    cell.classList.add("ship");
                    MY_GRIDS[row_id][i] = 1;
                }
            } else {
                for (let i = row_id; i < row_id + ship_length; i++) {
                    let cell = document.getElementById("" + i + col_id);
                    cell.classList.remove("ship-prepare");
                    cell.classList.add("ship");
                    MY_GRIDS[i][col_id] = 1;
                }
            }
            let li = ships[ship_length - 1].parentNode;
            let ul = li.parentNode;
            ul.removeChild(li);
            ship_length = 0;
            if (ul.childElementCount === 0) {
                game_state = 1; // Complete ship placement
                let ship_selector = document.getElementById("ship_selector");
                ship_selector.parentNode.removeChild(ship_selector);
                document.querySelector(".game_header > h1").innerText = "Waiting for opponent";
                document.querySelector(".game_header > p").innerText = "Battle will start as soon as your opponent is ready";
                let opponent_board = document.querySelector("#opponent_board");
                opponent_board.style.visibility = "visible";
                for (let i = 0; i < 10; i++) {
                    for (let j = 0; j < 10; j++) {
                        let grid = opponent_board.querySelector("#\\" + (i + 30) + " \\"+ (j + 30) + " ");
                        grid.onclick = fire;
                    }
                }
            }
            */
        }
    }

    var div_ships = document.getElementsByClassName("ship");
    for (let i = 0; i < ships.length; i++) {
        div_ships[i].onclick = selectShip;
        ships[div_ships[i].childElementCount - 1] = div_ships[i];
    }

    for (let i = 0; i < 10; i++) {
        MY_GRIDS[i] = new Array(10);
        OPPONENT_GRIDS[i] = new Array(10);
        for (let j = 0; j < 10; j++) {
            let grid = document.getElementById("" + i + j);
            grid.onmouseover = selectCell;
            grid.onmouseout = removeCell;
            grid.onclick = placeShip;
            MY_GRIDS[i][j] = 0;
            OPPONENT_GRIDS[i][j] = 0;
        }
    }
}
