window.onload = function () {

    var orientation = 1; // 1 Horizontal 2 Vertical
    var ship_length = 0;
    var game_state = 0;
    var ships = new Array([5]);
    var ship_place = 0;

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
            if (orientation === 1) {
                for (let i = col_id; i < col_id + ship_length; i++) {
                    let cell = document.getElementById("" + row_id + i);
                    cell.classList.remove("ship-prepare");
                    cell.classList.add("ship");
                }
            } else {
                for (let i = row_id; i < row_id + ship_length; i++) {
                    let cell = document.getElementById("" + i + col_id);
                    cell.classList.remove("ship-prepare");
                    cell.classList.add("ship");
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
                document.querySelector("#opponent_board_container").style.visibility = "visible";
            }
        }
    }

    var div_ships = document.getElementsByClassName("ship");
    for (let i = 0; i < ships.length; i++) {
        div_ships[i].onclick = selectShip;
        ships[div_ships[i].childElementCount - 1] = div_ships[i];
    }

    for (let i = 0; i < 10; i++) {
        for (let j = 0; j < 10; j++) {
            let grid = document.getElementById("" + i + j);
            grid.onmouseover = selectCell;
            grid.onmouseout = removeCell;
            grid.onclick = placeShip;
        }
    }
}
