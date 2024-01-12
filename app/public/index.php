<?php
    include_once('game.php');
    $app = new Ttt_application();
    $status = $app->run();
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Крестики-нолики</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Inter&display=swap" rel="stylesheet">
</head>
<body>


<div id='start_frame' class='start_frame'>
<div id='rectangle6' class='rectangle6'></div>
<div id='title' class='title'>N Крестики-нолики</div>


<?php 
    if ($status == 'new_game' || $status == 'start') {
        echo "<div id='start_rect' class='start_rect'></div>";
        echo "<div id='start3' class='start3'>{$app->message_html}</div>";
    } else {
        echo "<div id='rectangle2' class='rectangle2'></div>";
        echo "<div id='information' class='information'>{$app->message_html}</div>";
        echo "<div id='rectangle3' class='rectangle3'></div>";
        echo "<div id='board' class='board'>{$app->board_html}</div>";
        echo "<div id='rectangle4' class='rectangle4'></div>";
        echo "<div id='score' class='score'>{$app->score_html}</div>";   
    }
?>
</div>
</body>
</html>