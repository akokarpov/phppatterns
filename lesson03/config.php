<?php

// Пример спагетти-кода, например, в функции cartManager. Конфигурационный файл содержит настройка доступа к MySQL. Есть дублирование кода у функций доступа к заказам и корзине: getOrders, goodManager.

// MySQL settings
const SERVER = "localhost";
const DB = "my_db";
const LOGIN = "root";
const PASS = "";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$connect = mysqli_connect(SERVER,LOGIN,PASS,DB);

// Functions

function goodManager($connect, $goodId) {
    $sqlQuery = "delete from goods where id = $goodId";
    if(mysqli_query($connect,$sqlQuery)) {
        header('Location: /?page=catalog&status=goodDeleted');
    }
}

function getOrders($connect) {
    $sqlQuery = "SELECT cartIds, date FROM orders where status = 1";
    $answer = mysqli_query($connect, $sqlQuery);
    $ordersList = [];
    while($data = mysqli_fetch_assoc($answer)) {
        $orderDate = $data['date'];
        $cartIds = explode(",", $data['cartIds']);
        $goodsInOrder = [];
        for ($i=0; $i < count($cartIds); $i++) {
            $sqlQuery = "SELECT title, count, price, price * count AS sum FROM goods
            INNER JOIN cart ON cart.id = $cartIds[$i] and cart.goodId = goods.id and status = 1";
            $res = mysqli_query($connect, $sqlQuery);
            $goodsData = mysqli_fetch_assoc($res);
            $goodsInOrder[] = $goodsData;
        }
        $ordersList[$orderDate] = $goodsInOrder;        
    }
    return $ordersList;
}

function placeOrder($connect) {

    $sqlQuery = "select id from cart WHERE userId = {$_SESSION['userId']} AND status = 0";
    $answer = mysqli_query($connect, $sqlQuery);
    while($data = mysqli_fetch_assoc($answer)) {
        $goodsIds[] = $data['id'];
    }
    $ids = implode(",", $goodsIds);
    $now = date('d.m.Y H:i:s');
    $sqlQuery = "insert into orders values (id, '{$ids}', '{$now}', 1)";
    mysqli_query($connect, $sqlQuery);

    $sqlQuery = "update cart set status=1 WHERE userId = {$_SESSION['userId']} AND status = 0";
    mysqli_query($connect, $sqlQuery);

    header('Location: /?page=cart&order=done');
}

function getGoodsInCart($connect) {
    $sqlQuery = "SELECT goodId, title, price * count AS sum, count FROM goods
    INNER JOIN cart ON cart.goodId = goods.id
    AND userId = {$_SESSION['userId']} AND status = 0";
    $answer = mysqli_query($connect, $sqlQuery);
    if(!$answer) {
        die(mysqli_error($connect));
    }
    while($data = mysqli_fetch_assoc($answer)) {
        $goodsInCart[] = $data;
    }
    return $goodsInCart;
}

function cartManager($connect, $goodId, $action) {
    $goodId = (int)$goodId;
    $message = "";
    switch ($action) {
        case 'addGood':
            $sqlQuery = "select id from cart where goodId = $goodId and userId = {$_SESSION['userId']} and status = 0";
            $answer = mysqli_query($connect, $sqlQuery);
            $goodInCart = mysqli_fetch_assoc($answer)['id'];
            if($goodInCart == null) {
                $sqlQuery = "insert into cart values (id, $goodId, 1, {$_SESSION['userId']}, 0)";
                $message .= "<h1 style='color: darkgreen'>Товар успешно добавлен в корзину!</h1><br>";
            } else {
                $sqlQuery = "update cart set count=count+1 where goodId = $goodId";
                $message .= "<h1 style='color: darkgreen'>Товар был в корзине! Добавили еще одну единицу!</h1><br>";
            }
            break;
        case 'delGood':
            $sqlQuery = "delete from cart where goodId = $goodId and userId = {$_SESSION['userId']}";
            break;
        case 'addCount':
            $sqlQuery = "update cart set count=count+1 where goodId = $goodId and userId = {$_SESSION['userId']}";
            break;
        case 'remCount':
            $sqlQuery = "select count from cart where goodId = $goodId and userId = {$_SESSION['userId']}";
            $answer = mysqli_query($connect, $sqlQuery);
            $count = mysqli_fetch_assoc($answer)['count'];
            if($count > 1){   
                $sqlQuery = "update cart set count=count-1 where goodId = $goodId and userId = {$_SESSION['userId']}";    
            } else {
                $sqlQuery = "delete from cart where goodId = $goodId and userId = {$_SESSION['userId']}";         
            }
            break;
    }
    mysqli_query($connect, $sqlQuery);
    return $message;
}

function goodsAll($connect) {
    $sqlQuery = "select * from goods order by id desc";
    $answer = mysqli_query($connect, $sqlQuery);
    if(!$answer) {
        die(mysqli_error($connect));
    }
    while($data = mysqli_fetch_assoc($answer)) {
        $goods[] = $data;
    }
    return $goods;
}

function goodGet($connect, $id){
    $sqlQuery = sprintf("select * from goods where id=%d", $id);
    $answer = mysqli_query($connect, $sqlQuery);
    if(!$answer) {
        die(mysqli_error($connect));
    }
    $good = mysqli_fetch_assoc($answer); 
    return $good;
}

?>