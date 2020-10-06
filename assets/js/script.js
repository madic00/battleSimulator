window.onload = () => {
    let currentPage = window.location.href;

    if (currentPage.indexOf("page") == -1 || currentPage.indexOf("page=index") != -1) {
        $("#createGame").click(createNewGame);

        loadGames();

    } else if (currentPage.indexOf("game") != -1) {
        $(".error-field").hide();

        $("#submitArmy").click(addArmy);

        $(".startGame").click(startGame);

        $(".runGame").click(runGame);

    }
}


function ajaxGet(url, callback) {
    $.ajax({
        url: url,
        success: callback,
        error: function (xhr, error, status) {
            console.log(xhr, error, status);
        },
    });
}

function ajaxPost(url, data, callback) {
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json",
        data: data,
        success: callback,
        error: function (xhr) {
            console.log(xhr);

            if (xhr.responseJSON.error) {
                alert(xhr.responseJSON.error);
            }

            if (xhr.responseJSON.res) {
                alert(xhr.responseJSON.res);
            }

        }
    })
}

function createNewGame() {
    let url = "api/game/newGame.php";

    let data = {
        btnGame: true
    }

    ajaxPost(url, data, (response) => {
        alert("Game created! Game ID:" + response.game.gameCodeID);

        loadGames();
    })
}

function loadGames() {
    let url = "api/game/getAllGames.php";

    ajaxGet(url, response => {
        let print = printGames(response.games);

        $("#gamesContent").html(print);

    });
}

function startGame() {
    let gameId = $(this).data("gameid");

    let data = {
        gameId,
        btnStartGame: true
    };

    ajaxPost("api/game/startGame.php", data, response => {
        if (response.status) {
            alert(response.res);
        }
    });

}

function runGame() {
    let gameId = $(this).data("gameid");

    ajaxGet("api/game/runGame.php", response => {
        if (response.res) {
            alert(response.res);
        }

        if (response.winner) {
            alert(response.winner);

            console.log(response.winner);

            $("#winner").html(response.winner);
        }

        loadArmies(gameId);
    })
}

function loadArmies(gameId) {
    let url = "api/army/getArmiesByGame.php?idGame=" + gameId;

    ajaxGet(url, response => {
        let print = printArmies(response.armies);

        $("#armiesContent").html(print);
    })
}


// FORM FUNCTIONS
var armyData = {};
var errors = [];
var regexName = /^[A-z\d\s]{5,50}$/;

function addArmy() {
    armyData = {};
    errors = [];

    let gameId = Number($("#gameId").val());

    let armyForm = [
        {
            selector: "armyName",
            regex: regexName,
            type: "input",
            example: "Spartans"
        },
        {
            selector: "armyUnits",
            type: "number",
            min: 80,
            max: 100,
            example: "Army must have between 80 and 100 units"
        },
        {
            selector: "attackStrategy",
            type: "select"
        }
    ];

    for (const el of armyForm) {
        if (el.type == "input") {
            checkInput(el);
        } else if (el.type == "number") {
            checkNumber(el, el.min, el.max);
        } else {
            checkSelect(el);
        }
    }

    armyData["insertArmy"] = true;
    armyData["gameId"] = gameId;

    if (errors.length == 0) {

        ajaxPost("api/army/addArmy.php", armyData, response => {
            console.log(response);

            alert("Army " + response.army.name + " added to the game!");

            loadArmies(gameId);

            $("#armyName").val("");
            $("#armyUnits").val("");
            // $("#attackStrategy").val("0");
            $("#armyName").focus();
        })


    } else {
        return false;
    }

}


// HELPER FORM CHECK FUNCTIONS

function checkInput(el) {
    let field = $("#" + el.selector);
    let regex = el.regex;
    let fieldErr = $("#" + el.selector + "Err");

    if (regex.test(field.val())) {
        field.removeClass("border-danger");
        fieldErr.hide();

        armyData[el.selector] = field.val();

    } else {
        errors.push(el.selector + " not in right format");
        field.val("");
        field.addClass("border border-danger");

        fieldErr.html("Valid format: " + el.example);
        fieldErr.fadeIn();

        return false;
    }
}

function checkNumber(el, min, max) {
    let field = $("#" + el.selector);
    let fieldErr = $("#" + el.selector + "Err");
    let val = Number(field.val());

    if (val < min || val > max) {
        fieldErr.html("Units must be between " + min + " and " + max);
        fieldErr.fadeIn();

        errors.push("Units must be between 80 and 100");
        field.addClass("border border-danger");
        return false;
    } else {
        field.removeClass("border-danger");
        fieldErr.hide();

        armyData[el.selector] = val;

    }
}

function checkSelect(el) {
    let field = $("#" + el.selector);

    if (field.val() == 0) {
        field.addClass("border border-danger");
        errors.push(el.selector + " select one element");
        return false;
    } else {
        field.removeClass("border-danger");

        armyData[el.selector] = Number(field.val());

    }
}




// PRINT FUNCTIONS

function printGames(data) {
    let out = "";

    for (let i = 0; i < data.length; i++) {
        out += `
            <tr>
                <th scope="row">${i + 1}</th>
                <td>${data[i].gameCodeID}</td>
                <td>${data[i].gameStatus}</td>
                <td><a class="btn btn-dark manageGame" href="index.php?page=game&gameId=${data[i].id}">Manage game</button></td>
            </tr>
        `;
    }

    return out;
}

function printArmies(data) {
    let out = "";

    for (let i = 0; i < data.length; i++) {
        out += `
            <tr>
                <th scope="row">${i + 1}</th>
                <td>${data[i].name}</td>
                <td>${data[i].units}</td>
            </tr>
        `;
    }

    return out;
}

