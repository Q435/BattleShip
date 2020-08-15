<?php
    session_start();
    $js_files = array("simpleajax", "battleship");
    $css_files = array("online");
    include("include/util.inc.php");
    include("include/opening.inc.php");

    checkLogin();

    if (!isset($_SESSION['game_id'])) {
        checkGame();
        echo "no game_id";
    }

?>

<div id="container">
        <section id="main_section">
            <div class="game_header">
                <h1>
                    Place your ships
                </h1>
                <p>
                    Use the instructions below
                </p>
            </div>
            <section id="boards_container">
                <div id="my_board">
                    <div class="game_header">
                        <h2>
                            Your ships
                        </h2>
                    </div>
                    <div id="ship_selector">
                        <p>
                            <!-- react-text: 47 -->
                            The current orientation is:
                            <!-- /react-text -->
                            <span class="orientation">
								horizontal
							</span>
                        </p>
                        <ul>
                            <li class="">
                                <div class="ship">
									<span>
									</span>
                                    <span>
									</span>
                                    <span>
									</span>
                                    <span>
									</span>
                                    <span>
									</span>
                                </div>
                            </li>
                            <li class="">
                                <div class="ship">
									<span>
									</span>
                                    <span>
									</span>
                                    <span>
									</span>
                                    <span>
									</span>
                                </div>
                            </li>
                            <li class="">
                                <div class="ship">
									<span>
									</span>
                                    <span>
									</span>
                                    <span>
									</span>
                                </div>
                            </li>
                            <li class="">
                                <div class="ship">
									<span>
									</span>
                                    <span>
									</span>
                                </div>
                            </li>
                            <li class="">
                                <div class="ship">
									<span>
									</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="grid">
                        <div class="row">
                            <div class="table_header cell">
                            </div>
                            <div class="table_header cell">
                                A
                            </div>
                            <div class="table_header cell">
                                B
                            </div>
                            <div class="table_header cell">
                                C
                            </div>
                            <div class="table_header cell">
                                D
                            </div>
                            <div class="table_header cell">
                                E
                            </div>
                            <div class="table_header cell">
                                F
                            </div>
                            <div class="table_header cell">
                                G
                            </div>
                            <div class="table_header cell">
                                H
                            </div>
                            <div class="table_header cell">
                                I
                            </div>
                            <div class="table_header cell">
                                J
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                1
                            </div>
                            <div id="00" class="cell">
                            </div>
                            <div id="01" class="cell">
                            </div>
                            <div id="02" class="cell">
                            </div>
                            <div id="03" class="cell">
                            </div>
                            <div id="04" class="cell">
                            </div>
                            <div id="05" class="cell">
                            </div>
                            <div id="06" class="cell">
                            </div>
                            <div id="07" class="cell">
                            </div>
                            <div id="08" class="cell">
                            </div>
                            <div id="09" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                2
                            </div>
                            <div id="10" class="cell">
                            </div>
                            <div id="11" class="cell">
                            </div>
                            <div id="12" class="cell">
                            </div>
                            <div id="13" class="cell">
                            </div>
                            <div id="14" class="cell">
                            </div>
                            <div id="15" class="cell">
                            </div>
                            <div id="16" class="cell">
                            </div>
                            <div id="17" class="cell">
                            </div>
                            <div id="18" class="cell">
                            </div>
                            <div id="19" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                3
                            </div>
                            <div id="20" class="cell">
                            </div>
                            <div id="21" class="cell">
                            </div>
                            <div id="22" class="cell">
                            </div>
                            <div id="23" class="cell">
                            </div>
                            <div id="24" class="cell">
                            </div>
                            <div id="25" class="cell">
                            </div>
                            <div id="26" class="cell">
                            </div>
                            <div id="27" class="cell">
                            </div>
                            <div id="28" class="cell">
                            </div>
                            <div id="29" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                4
                            </div>
                            <div id="30" class="cell">
                            </div>
                            <div id="31" class="cell">
                            </div>
                            <div id="32" class="cell">
                            </div>
                            <div id="33" class="cell">
                            </div>
                            <div id="34" class="cell">
                            </div>
                            <div id="35" class="cell">
                            </div>
                            <div id="36" class="cell">
                            </div>
                            <div id="37" class="cell">
                            </div>
                            <div id="38" class="cell">
                            </div>
                            <div id="39" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                5
                            </div>
                            <div id="40" class="cell">
                            </div>
                            <div id="41" class="cell">
                            </div>
                            <div id="42" class="cell">
                            </div>
                            <div id="43" class="cell">
                            </div>
                            <div id="44" class="cell">
                            </div>
                            <div id="45" class="cell">
                            </div>
                            <div id="46" class="cell">
                            </div>
                            <div id="47" class="cell">
                            </div>
                            <div id="48" class="cell">
                            </div>
                            <div id="49" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                6
                            </div>
                            <div id="50" class="cell">
                            </div>
                            <div id="51" class="cell">
                            </div>
                            <div id="52" class="cell">
                            </div>
                            <div id="53" class="cell">
                            </div>
                            <div id="54" class="cell">
                            </div>
                            <div id="55" class="cell">
                            </div>
                            <div id="56" class="cell">
                            </div>
                            <div id="57" class="cell">
                            </div>
                            <div id="58" class="cell">
                            </div>
                            <div id="59" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                7
                            </div>
                            <div id="60" class="cell">
                            </div>
                            <div id="61" class="cell">
                            </div>
                            <div id="62" class="cell">
                            </div>
                            <div id="63" class="cell">
                            </div>
                            <div id="64" class="cell">
                            </div>
                            <div id="65" class="cell">
                            </div>
                            <div id="66" class="cell">
                            </div>
                            <div id="67" class="cell">
                            </div>
                            <div id="68" class="cell">
                            </div>
                            <div id="69" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                8
                            </div>
                            <div id="70" class="cell">
                            </div>
                            <div id="71" class="cell">
                            </div>
                            <div id="72" class="cell">
                            </div>
                            <div id="73" class="cell">
                            </div>
                            <div id="74" class="cell">
                            </div>
                            <div id="75" class="cell">
                            </div>
                            <div id="76" class="cell">
                            </div>
                            <div id="77" class="cell">
                            </div>
                            <div id="78" class="cell">
                            </div>
                            <div id="79" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                9
                            </div>
                            <div id="80" class="cell">
                            </div>
                            <div id="81" class="cell">
                            </div>
                            <div id="82" class="cell">
                            </div>
                            <div id="83" class="cell">
                            </div>
                            <div id="84" class="cell">
                            </div>
                            <div id="85" class="cell">
                            </div>
                            <div id="86" class="cell">
                            </div>
                            <div id="87" class="cell">
                            </div>
                            <div id="88" class="cell">
                            </div>
                            <div id="89" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                10
                            </div>
                            <div id="90" class="cell">
                            </div>
                            <div id="91" class="cell">
                            </div>
                            <div id="92" class="cell">
                            </div>
                            <div id="93" class="cell">
                            </div>
                            <div id="94" class="cell">
                            </div>
                            <div id="95" class="cell">
                            </div>
                            <div id="96" class="cell">
                            </div>
                            <div id="97" class="cell">
                            </div>
                            <div id="98" class="cell">
                            </div>
                            <div id="99" class="cell">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="opponent_board">
                    <div class="game_header">
                        <h2>
                            Opponent ships
                        </h2>
                    </div>
                    <div class="grid">
                        <div class="row">
                            <div class="table_header cell">
                            </div>
                            <div class="table_header cell">
                                A
                            </div>
                            <div class="table_header cell">
                                B
                            </div>
                            <div class="table_header cell">
                                C
                            </div>
                            <div class="table_header cell">
                                D
                            </div>
                            <div class="table_header cell">
                                E
                            </div>
                            <div class="table_header cell">
                                F
                            </div>
                            <div class="table_header cell">
                                G
                            </div>
                            <div class="table_header cell">
                                H
                            </div>
                            <div class="table_header cell">
                                I
                            </div>
                            <div class="table_header cell">
                                J
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                1
                            </div>
                            <div id="00" class="cell">
                            </div>
                            <div id="01" class="cell">
                            </div>
                            <div id="02" class="cell">
                            </div>
                            <div id="03" class="cell">
                            </div>
                            <div id="04" class="cell">
                            </div>
                            <div id="05" class="cell">
                            </div>
                            <div id="06" class="cell">
                            </div>
                            <div id="07" class="cell">
                            </div>
                            <div id="08" class="cell">
                            </div>
                            <div id="09" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                2
                            </div>
                            <div id="10" class="cell">
                            </div>
                            <div id="11" class="cell">
                            </div>
                            <div id="12" class="cell">
                            </div>
                            <div id="13" class="cell">
                            </div>
                            <div id="14" class="cell">
                            </div>
                            <div id="15" class="cell">
                            </div>
                            <div id="16" class="cell">
                            </div>
                            <div id="17" class="cell">
                            </div>
                            <div id="18" class="cell">
                            </div>
                            <div id="19" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                3
                            </div>
                            <div id="20" class="cell">
                            </div>
                            <div id="21" class="cell">
                            </div>
                            <div id="22" class="cell">
                            </div>
                            <div id="23" class="cell">
                            </div>
                            <div id="24" class="cell">
                            </div>
                            <div id="25" class="cell">
                            </div>
                            <div id="26" class="cell">
                            </div>
                            <div id="27" class="cell">
                            </div>
                            <div id="28" class="cell">
                            </div>
                            <div id="29" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                4
                            </div>
                            <div id="30" class="cell">
                            </div>
                            <div id="31" class="cell">
                            </div>
                            <div id="32" class="cell">
                            </div>
                            <div id="33" class="cell">
                            </div>
                            <div id="34" class="cell">
                            </div>
                            <div id="35" class="cell">
                            </div>
                            <div id="36" class="cell">
                            </div>
                            <div id="37" class="cell">
                            </div>
                            <div id="38" class="cell">
                            </div>
                            <div id="39" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                5
                            </div>
                            <div id="40" class="cell">
                            </div>
                            <div id="41" class="cell">
                            </div>
                            <div id="42" class="cell">
                            </div>
                            <div id="43" class="cell">
                            </div>
                            <div id="44" class="cell">
                            </div>
                            <div id="45" class="cell">
                            </div>
                            <div id="46" class="cell">
                            </div>
                            <div id="47" class="cell">
                            </div>
                            <div id="48" class="cell">
                            </div>
                            <div id="49" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                6
                            </div>
                            <div id="50" class="cell">
                            </div>
                            <div id="51" class="cell">
                            </div>
                            <div id="52" class="cell">
                            </div>
                            <div id="53" class="cell">
                            </div>
                            <div id="54" class="cell">
                            </div>
                            <div id="55" class="cell">
                            </div>
                            <div id="56" class="cell">
                            </div>
                            <div id="57" class="cell">
                            </div>
                            <div id="58" class="cell">
                            </div>
                            <div id="59" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                7
                            </div>
                            <div id="60" class="cell">
                            </div>
                            <div id="61" class="cell">
                            </div>
                            <div id="62" class="cell">
                            </div>
                            <div id="63" class="cell">
                            </div>
                            <div id="64" class="cell">
                            </div>
                            <div id="65" class="cell">
                            </div>
                            <div id="66" class="cell">
                            </div>
                            <div id="67" class="cell">
                            </div>
                            <div id="68" class="cell">
                            </div>
                            <div id="69" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                8
                            </div>
                            <div id="70" class="cell">
                            </div>
                            <div id="71" class="cell">
                            </div>
                            <div id="72" class="cell">
                            </div>
                            <div id="73" class="cell">
                            </div>
                            <div id="74" class="cell">
                            </div>
                            <div id="75" class="cell">
                            </div>
                            <div id="76" class="cell">
                            </div>
                            <div id="77" class="cell">
                            </div>
                            <div id="78" class="cell">
                            </div>
                            <div id="79" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                9
                            </div>
                            <div id="80" class="cell">
                            </div>
                            <div id="81" class="cell">
                            </div>
                            <div id="82" class="cell">
                            </div>
                            <div id="83" class="cell">
                            </div>
                            <div id="84" class="cell">
                            </div>
                            <div id="85" class="cell">
                            </div>
                            <div id="86" class="cell">
                            </div>
                            <div id="87" class="cell">
                            </div>
                            <div id="88" class="cell">
                            </div>
                            <div id="89" class="cell">
                            </div>
                        </div>
                        <div class="row">
                            <div class="table_header cell">
                                10
                            </div>
                            <div id="90" class="cell">
                            </div>
                            <div id="91" class="cell">
                            </div>
                            <div id="92" class="cell">
                            </div>
                            <div id="93" class="cell">
                            </div>
                            <div id="94" class="cell">
                            </div>
                            <div id="95" class="cell">
                            </div>
                            <div id="96" class="cell">
                            </div>
                            <div id="97" class="cell">
                            </div>
                            <div id="98" class="cell">
                            </div>
                            <div id="99" class="cell">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
</div>

<?php
include("include/closing.html");
?>
