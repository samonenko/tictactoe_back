# Пример реализации back-end проекта "Крестики-нолики"

## Название
N-Крестики-нолики 

## Описание

Данный проект представляет собой back-end приложение для игры в крестики-нолики на поле произвольного размера для двух игроков.

В приложении можно задать произвольный размер поля и сыграть на нем несколько партий. Партия считается выигранной, если игрок составил из своих символов вертикаль, горизонталь или одну из диагоналей на игровом поле. Если все клетки заполнены, но никто не выиграл, то получается ничья. 

На первом экране предлагается выбор размер доски. Создается новая игра с уникальным идентификатором и две ссылки для игры за крестики, и игры за нолики. Все игры сохраняются в базу данных. Любую игру можно продолжить по данным ссылкам.

Страницы игроков автоматически не обновляются, чтобы увидеть, что ход перешел к игроку надо обновить страницу.

## Стек технологий
* PHP
* HTML
* CSS
* Visual Studio Code
* Figma

## Информационные элементы и элементы управления (web-версия)
|Название|Тип|Назначение|
|---|---|---|
|Размер игры| Строка ввода (число)| Задает текущий размер поля для игры|
|Начать игру| Кнопка | Начинает новую игру с заданным размером поля|
|Игровое поле| Холст | Поле для игры. Откликается на клики|
|Информация| Текст | Отображения информации о результате игры и текущий счет|
|Обновить| Текст | Отображения информации о результате игры и текущий счет|