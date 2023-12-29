<?php

class Board {
    /*
    * Игровая доска
    */

    // Формируем константы для различных обозначений, 
    // чтобы управлять параметрами можно было в одной части кода.
    const EMPTY = "_";
    const CROSS = "X";
    const NOUGHT = "O";
    const DRAW = "D";
    const UNKNOWN = "?";

    public $data; // Данные доски 
    public $size; // Размер доски


    public function __construct($n)
    {
        /*
        *   Конструктор класса
        */ 
        $this->size = $n; // Размер доски
        $this->data = array(); // данные доски 
        $this->clear();  // очищаем доску

    }

    public function clear() {
        /*
        *   Очистка доски
        */
        $this->data = array_fill(0, $this->size, array_fill(0, $this->size, self::EMPTY));
    }

    public function  set_value($i, $j, $value) {
        /*
        *   Установить в пустую ячейку новое значение
        */
        if ($this->data[$i][$j] == self::EMPTY) {
            $this->data[$i][$j] = $value;
        }
    }

    public function has_empty_cells() {
        /*
        *   Проверка, существуют ли пустые ячейки
        */
        for ($i=0; $i != $this->size; $i++) {
            for ($j=0; $j != $this->size; $j++) {
                if ($this->data[$i][$j] == self::EMPTY) {
                    return true;
                }
            }
         }
         return false;
    }

    public function check_winner($w) {
        /*
        *   Проверка того, что символ w является победителем
        */
        for ($i=0; $i != $this->size; $i++) { // Для каждой строки
            $result = true;
            for ($j=0; $j != $this->size; $j++) { // проверяем, что по всем ячейкам стоит w
                    $result &= ($this->data[$i][$j] == $w);
            }
            if ($result) return $result;
        }
    
        for ($j=0; $j != $this->size; $j++) { // Для каждого столбца
            $result = true;
            for ($i=0; $i != $this->size; $i++) { // проверяем, что по всем ячейкам стоит w
                    $result &= ($this->data[$i][$j] == $w);
            }
            if ($result) return $result;
        }
    
        // Проверяем одну диагональ
        $result = true;
        for ($i=0; $i != $this->size; $i++) {
                $result &= ($this->data[$i][$i] == $w);
        }
        if ($result) return $result;
    
        // Проверяем другую диагональ
        $result = true;
        for ($i=0; $i != $this->size; $i++) {
            $result &= ($this->data[$i][$this->size-1-$i] == $w);
        }
        if ($result) return $result;
    
        // Символ w не выиграл.
        return false;

    }

    public function winner() {
        /*
        *   Возвращает текущую ситуацию на доске 
        *   self::CROSS -- выиграли крестики
        *   self::NOUGHT -- выиграли нолики
        *   self::DRAW -- ничья 
        *   self::UNKNOWN -- пока никто не выиграл
        */
        foreach ([self::CROSS, self::NOUGHT] as $w) { // Проверяем, выиграли крестики или нолики
            if ($this->check_winner($w)) {
                return $w;
            }
        }

        if (!$this->has_empty_cells()) { // Если нет пустых ячеек,
            return self::DRAW; // то ничья
        }

        return self::UNKNOWN; // пока никто не выиграл
    }
}


class Ttt_game {
    /*
    *   Игра N-крестики-нолики
    */ 

    public $board; // Игровая доска 
    public $score; // Счет игроков 
    public $current_player; // Игрок, который должен сделать текущий ход

    public function __construct($n) {
        /*
        *   Конструктор класса
        *   $n -- размер доски
        */

        $this->board = new Board($n); // Создаем новую доску 
        $this->score = [ // Устанавливаем начальный счет 0:0
            board::CROSS => 0,
            board::NOUGHT => 0
        ];
        
        $this->current_player = $this->board::CROSS; // Указываем, что первыми ходят крестики 
    }

    public function new_round() {
        /*
        *   Новый раунд игры
        */ 
        $this->board->clear(); // Очищаем доску 
        $this->current_player = $this->board::CROSS; // Указываем, что первыми ходят крестики 
    }

    public function move($i, $j) {
        /*
        * Ход игры. 
        * ($i, $j) -- клетка, куда пошел текущий игрок
        * Функция возвращает ситуацию на доске после данного хода
        */

        $this->board->set_value($i, $j, $this->current_player); // Ходим за текущего игрока
        $winner = $this->board->winner(); // Проверяем ситуацию на доске

        if (array_key_exists($winner, $this->score)) { // Если выиграли крестики или нолики, 
            $this->score[$winner] += 1; // то увеличиваем соответствующий счет 
        }

        // Иначе игра продолжается, и необходимо сменить игрока, который будет делать следующий ход.
        if ($this->current_player == $this->board::CROSS) {
            $this->current_player = $this->board::NOUGHT;
        } else {
            $this->current_player = $this->board::CROSS;
        }

        return $winner; 
    }

}

class Ttt_application {
    /*
    * Игровое приложение
    */

    public $ttt_game; // Игра 
    public $game_id; // Идентификатор игры 
    private $dbh; // Ссылка на подключение к базе данных
    public $board_html; // HTML c текущей доской 
    public $message_html; // HTML c сообщениями для пользователей
    public $score_html; // HTML c текущим счетом

    public function __construct() {
        /*
        *   Конструктор класса
        */ 

        $this->database_connection(); // Подключаемся к базе данных
    }

    function new_game($n) {
        /*
        * Создание новой игры в базе данных.
        * $n -- размер поля.
        */ 

        $this->ttt_game = new Ttt_game($n); // Создаем новую игру 
        $json = json_encode($this->ttt_game); // Сохраняем игру в json 
        $sql = "INSERT INTO game (json) VALUES (?)"; // Готовим строку запроса для записи в базу данных
        $this->dbh->prepare($sql)->execute([$json]); // Выполняем запрос
        
        // Получаем значение идентификатор игры -- это значение в столбце id в таблице games
        $this->game_id = $this->dbh->lastInsertId(); 
    }

    function load_game($game_id) {
        /*
        *   Загрузка игры из базы данных по идентификатору $game_id
        */
        $this->game_id = $game_id; // Сохраняем идентификатор
        $sql = "SELECT json FROM game WHERE id=?"; // Готовим строку запроса 
        $stmt = $this->dbh->prepare($sql); // Готовим запрос 
        $stmt->execute([$game_id]); // Выполняем запрос
        $row = $stmt->fetch();  // Получаем строку json 
        $json_data = json_decode($row['json'], true); // Преобразовываем строку json в ассоциированный массив  

        $this->ttt_game = new Ttt_game($json_data['board']['size']); // Создаем новую игру нужного размера
        $this->ttt_game->board->data = $json_data['board']['data']; // Перезаписываем ее поле
        $this->ttt_game->score = $json_data['score']; // Перезаписываем текущий счет 
        $this->ttt_game->current_player = $json_data['current_player']; // Перезаписываем текущего игрока
        
        $this->score_html = $this->ttt_game->score[Board::CROSS] . " : " 
                            . $this->ttt_game->score[Board::NOUGHT]; // Готовим строку с текущим счетом
    }

    function save_game() {
        /*
        *   Сохранение игры в базу данных
        */

        $json = json_encode($this->ttt_game); // Создаем json из объекта игры
        $sql = "UPDATE game SET json=? WHERE id=?"; // Строка запроса для перезаписи 
        $this->dbh->prepare($sql)->execute([$json, $this->game_id]); // Выполняем запрос
    }

    private function database_connection(){
        /*
        *   Установка связи с базой данных
        */ 
        $this->dbh = new PDO('mysql:dbname=db;host=mysql', 
                            'user', 
                            '12345', 
                            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    }

    function get_board_html($player, $is_interactive) {
        /*
        *   Создание HTML кода с текущей доской
        *   $player -- за кого играет данных игрок. 
        *   Эта информация тут важна, т.е. она участвует в формировании ссылки для клика
        *
        *   $is_interactive -- должен ли игрок сейчас ходить (кликать) или он просто видит доску
        */ 

        // Картинки, которые соответствуют крестикам, ноликам и пустой ячейке
        $image = [
            Board::CROSS => "cross.png",
            Board::NOUGHT => "nought.png",
            Board::EMPTY => "empty.png",
        ];

        $result = "<table border=1>"; // Создаем HTML таблицу
        
        $n = $this->ttt_game->board->size;
        for ($i=0 ; $i != $n; $i++) {
            $result .= "<tr>";
            for ($j=0 ; $j != $n; $j++) {

                // Картинка, которая соответствует ячейке  
                $img = $image[$this->ttt_game->board->data[$i][$j]]; 
                
                if ($is_interactive) { // Если сейчас ход данного игрока,
                    // то каждая клеточка -- это гиперссылка, которая должна хранить следующую информацию:
                    // id игры, 
                    // (i,j) клетки, куда игрок кликнул
                    // символ, за который играет игрок
                    $params = "game_id={$this->game_id}&i={$i}&j={$j}&player={$player}";
                    
                    // Создаем ячейку таблицы, которая содержит картинку, которая является ссылкой 
                    // c соответствующими параметрами 
                    $result .= "<td><a href='?{$params}'><img src='{$img}' width='20' height='20'></a></td>";
                } else { // Если ход другого игрока
                    // то данному игроку показываем поле без ссылок
                    $result .= "<td><img src='{$img}' width='20' height='20'></td>";
                }
            }
            $result .= "</tr>";
        }
        $result .= "</table>";        
        return $result;
    }


    function get_message_html($player, $result, $is_interactive) {
        /*
        *   Сообщения для игрока
        */
        $message = [
            Board::CROSS => "Выиграли крестики",
            Board::NOUGHT => "Выиграли нолики",
            Board::DRAW => "Ничья",
            Board::UNKNOWN => "Делайте ход"
        ];

        if ($result != Board::UNKNOWN) { // Если игра не продолжается
            $result = $message[$result]; // то показать, что выиграл 

            $params = "game_id={$this->game_id}&player={$player}&continue"; // В ссылку добавляем переменную continue 
            $result .= "<br><a href='?{$params}'>Играть еще раз</a>"; // Предлагаем сыграть еще раз
            return $result;
        } else { // Если игра продолжается 
            if ($is_interactive) { // И сейчас очередь данного игрока
                return $message[$result]; // то пишем, что надо сделать ход
            } else { // Иначе сообщаем, что надо обновить страницу, чтобы проверить, не был ли сделан ход другим
                $params = "game_id={$this->game_id}&player={$player}";
                return "<a href='?{$params}'>Обновить</a>";
            }
        }
    }

    function run() {
        /*
        *   Запуск приложения
        *   Происходят различные действия, в зависимости от того, что получено в адресной строке 
        */


        if (isset($_GET['game_id']) && isset($_GET['player'])) { // Если определен id игры и символ игрока 
            // Сохраняем полученные параметры для удобства использования
            $game_id = $_GET['game_id']; 
            $player = $_GET['player'];

            if (isset($_GET["continue"])) { // Если это была ссылка создания нового раунда игры 
                $this->load_game($game_id); // Загружаем игру из базы 
                $this->ttt_game->new_round(); // Объявляем новый раунд
                $this->save_game(); // Сохраняем игру в базу
            } 
            if (isset($_GET['i']) && isset($_GET['j'])) { // Если это была ссылка хода игрока
                $this->load_game($game_id); // Загружаем игру из базы 
                $this->ttt_game->move($_GET['i'], $_GET['j']); // Делаем в ней новый ход
                $this->save_game(); // Сохраняем игру в базу
            }
            // Независимо от предыдущих условий
            $this->load_game($game_id); // Загружаем игру из базы 
            $result = $this->ttt_game->board->winner(); // Проверяем ситуацию на доске

            // Определяем, будет ли доска интерактивной для данного игрока.
            // Это происходит в ситуации, когда результат игры еще не определен,
            // и символ игрока ($player) совпадает с символом игрока, 
            // который сейчас должен ходить на доске this->ttt_game->current_player
            $is_interactive = ($result == Board::UNKNOWN && $this->ttt_game->current_player == $player);
            
            
            $this->message_html = $this->get_message_html($player, $result, $is_interactive); // Формируем сообщение для игрока для отображения 
            $this->board_html = $this->get_board_html($player, $is_interactive); // Формируем доску для отображения 
            
        } else { // Если сейчас игра не идет
            if (isset($_GET['size'])) { // Но адресной строке был указан размер 
                $this->new_game($_GET['size']); // тогда создаем новую игру данного размера
                $this->board_html = Null;
                $this->message_html = "Создана новая игра id:{$this->game_id}<br>";
                $this->message_html .= "<a href='?game_id={$this->game_id}&player=".Board::CROSS."'>Ссылка для крестиков</a><br>";
                $this->message_html .= "<a href='?game_id={$this->game_id}&player=".Board::NOUGHT."'>Ссылка для ноликов</a><br>";
            } else {
                $this->board_html = Null;
                $this->message_html = "<form action='?' method='get'>";
                $this->message_html .= "Размер поля: <input type='number' name='size'>";
                $this->message_html .= "<input type='submit' value='Начать'>";
            }
        }
        
    }
}