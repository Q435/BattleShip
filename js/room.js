window.onload = function () {

    var timeHandler;

    function joinRoom() {
        let gameId = this.getAttribute("data-id");
        ajaxRequest("server.php", "post", { type: "game", event: "join", id : gameId },
            function () {
                let res = JSON.parse(this.responseText);
                if (res.code === 200) {
                    window.clearTimeout(timeHandler);
                    window.location.href = "online.php";
                } else {
                    alert("Join game error!");
                }
            }, function () {
                console.log("joinRoom error");
            });
    }

    function showGameList() {
        let result = JSON.parse(this.responseText);
        let list = document.getElementById("current-games-list");
        list.innerHTML = "";
        if (result.length != 0) {
            document.getElementsByClassName("current-games-container")[0].style.display = "block";
        } else {
            document.getElementsByClassName("current-games-container")[0].style.display = "none";
        }

        for (let i = 0; i < result.length; i++) {
            let li_el = document.createElement("li");
            let a_el = document.createElement("button");
            a_el.classList.add("button");
            a_el.classList.add("small");
            a_el.setAttribute("data-id", result[i].Id);
            a_el.onclick = joinRoom;
            a_el.innerText = "JOIN";
            li_el.innerText = result[i].player1_name + "'s room";
            li_el.appendChild(a_el);
            list.appendChild(li_el);
        }
        timeHandler = window.setTimeout(getGameList, 2000);
    }

    function ajaxError() {
        console.log("Find Games Error");
        timeHandler = window.setTimeout(getGameList, 2000);
    }

    function getGameList() {
        ajaxRequest("find_room.php", "GET", null, showGameList, ajaxError);
    }

    function newGame() {
        ajaxRequest("server.php", "POST", { type: "game", event: "create"},
            function () {
                let res = JSON.parse(this.responseText);
                if (res.code === 200) {
                    window.clearTimeout(timeHandler);
                    window.location.href = "online.php";
                } else {
                    alert("Start new game error!");
                }
            }, function () {

            });
    }

    let btn_new = document.getElementById("btn_new");
    btn_new.onclick = newGame;
    getGameList();
    timeHandler = window.setTimeout(getGameList, 2000);
}
