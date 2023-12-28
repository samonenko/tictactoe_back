<?php

class Board {
    public $data;
    public $size;


    public function __construct($n)
    {
        $this->size = $n;
        $this->data = array_fill(0,$n, array_fill(0, $n, '*'));

    }

    public function to_html() {
        $result = "<table>";
        for ($i=0; $i != $this->size; $i++) {
            $result .= "<tr>";
            for ($j=0; $j != $this->size; $j++) {
                $result .= "<td>" . $this->data[$i][$j] . "</td>";

            }
            $result .= "</tr>";

        }
        return $result;
    }
}

$board = new Board(4);
echo($board->to_html());

/*
$pdo = new PDO('mysql:dbname=db;host=mysql', 'user', '12345', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$query = $pdo->query('SHOW VARIABLES like "version"');

$row = $query->fetch();

echo 'MySQL version:' . $row['Value'];
*/