<?php 

    if(!isset($_GET['gameId'])) {
        die("Choose game first!");
    }

    $gameId = $_GET['gameId'];

    if(Game::gameInfo($gameId) == null) {
        die("Choose existing game!");
    }
    
    $game = Game::gameInfo($gameId);


    $armies = Army::getArmiesByGame($gameId);

    $strategies = AttackStrategy::getAll();

    $_SESSION['gameId'] = $gameId;

?>

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center py-5">
                <h1><a href="index.php" title="Home">Battle simulator</a></h1>

                <h2 id="gameCode">Game Id: <?= $game->gameCodeID ?></h2>

                <p id="winner"></p>
                
                <div id="armies" class="my-5">

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Army</th>
                                <th scope="col">Units</th>
                                <!-- <th scope="col">Controls</th> -->
                            </tr>
                        </thead>
                        <tbody id="armiesContent">
                            <?php foreach($armies as $key => $army): ?>
                                <tr>
                                    <th scoe="row"><?= $key + 1 ?></th>

                                    <td><?= $army->name ?></td>

                                    <td><?= $army->units ?></td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <button type="button" data-gameid="<?= $gameId ?>" class="btn btn-dark startGame mr-5">Start Game</button>

                    <button type="button" data-gameid="<?= $gameId ?>" class="btn btn-dark runGame mr-5">Run</button>

                    <button type="button" data-gameid="<?= $gameId ?>" class="btn btn-dark autorun mr-5">Autorun</button>

                </div>


                <hr />

                <div id="addFormContainer" class="col-md-6 mx-auto mb-5">

                    <h3>Add army form</h3>

                    <form id="addArmy">
                        <div class="mb-4">
                            <label for="armyName" class="form-label">Army Name</label>
                            <input type="text" class="form-control" id="armyName" />
                            
                            <small id="armyNameErr" class="form-text text-danger error-field">Valid format: </small>

                        </div>

                        <input type="hidden" name="gameId" id="gameId" value="<?= $gameId ?>" />

                        <div class="row">
                            <div class="col mb-4">
                                <label for="armyUnits" class="form-label">Army Units</label>
                                <input type="number" class="form-control" id="armyUnits" />

                                <small id="armyUnitsErr" class="form-text text-danger error-field">Valid format: </small>
                                
                            </div>

                            <div class="col mb-4">
                                <label for="attackStrategy" class="form-label">Attack Strategy</label>
                                <select id="attackStrategy" class="form-select">

                                <?php foreach($strategies as $st): ?>
                                    <option value="<?= $st->id?>"><?= $st->valueStrategy ?></option>
                                <?php endforeach; ?>
                                    
                                </select>

                            </div>

                        </div>
                         
                        <button type="button" class="btn btn-primary" id="submitArmy" name="submitArmy">Add Army</button>


                    </form>

                </div>

            </div>
        </div>

    </div>