<?php
    include_once('game.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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