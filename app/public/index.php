<?php
    include_once('game.php');
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Крестики-нолики</title>
</head>
<body>
<?php
    $app = new Ttt_application();
    $app->run();
    echo $app->board_html;
    echo $app->message_html;
    echo "<br>";
    echo $app->score_html;
?>
</body>
</html>