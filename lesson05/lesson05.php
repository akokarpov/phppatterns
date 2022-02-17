<?php

//1. Реализовать на PHP пример Декоратора, позволяющий отправлять уведомления несколькими различными способами (описан в этой методичке).

//Базовый класс Компонента
interface Component
{
    public function operation() : string;
}

//Конкретный компонент без декораторов
class ConcreteComponent implements Component
{
    public function operation() : string
    {
        return "Message without decorators";
    }
}

//Базовый класс Декоратора
class Decorator implements Component
{
    /**
     * @var Component
     */
    protected $component;

    public function __construct(Component $component)
    {

        $this->component = $component;
    }

    /**
     * Декоратор делегирует всю работу обёрнутому компоненту.
     */
    public function operation() : string
    {
        return $this->component->operation();
    }
}

//Конкретные декораторы
class SMS extends Decorator
{
    public function operation() : string
    {
        return "SMS notification(" . parent::operation() . ")";
    }
}

class Email extends Decorator
{
    public function operation() : string
    {
        return "Email notification(" . parent::operation() . ")";
    }
}

class CN extends Decorator
{
    public function operation() : string
    {
        return "CN notification(" . parent::operation() . ")";
    }
}

//Клиентский код
function clientCod(Component $component)
{
    echo "RESULT: " . $component->operation();
}

$simple = new ConcreteComponent();
echo "Client: I've got a simple component:\n";
clientCod($simple);
echo "\n\n";

$decorator1 = new SMS($simple);
$decorator2 = new Email($simple);
$decorator3 = new CN($simple);
echo "Client: Now I've got a decorated component:\n";
clientCod($decorator1);


//2. Реализовать паттерн Адаптер

//Библиотеки
class CircleAreaLib
{
   public function getCircleArea(int $diagonal)
   {
       $area = (M_PI * $diagonal**2)/4;

       return $area;
   }
}

class SquareAreaLib
{
   public function getSquareArea(int $diagonal)
   {
       $area = ($diagonal**2)/2;

       return $area;
   }
}

//Имеющиеся интерфейсы
interface ISquare
{
function squareArea(int $sideSquare);
}

interface ICircle
{
function circleArea(int $circumference);
}

//Адаптеры
class CircleAdapter implements ICircle
{
    private $circle;

    public function __construct(CircleAreaLib $circle)
    {

        $this->circle = $circle;
    }

    public function getCircleArea($circumference)
    {
        $this->circle>circleArea($circumference);
    }
}

class SquareAdapter implements ISquare
{
    private $square;

    public function __construct(CircleAreaLib $square)
    {

        $this->square = $square;
    }

    public function getCircleArea($sideSquare)
    {
        $this->circle>circleArea($sideSquare);
    }
}

//Клиентский код
function clientCode(ICircle $target)
{
    echo $target->circleArea();
}

$target = new ICircle();
clientCode($target);

$lib = new CircleAreaLib();

$adapter = new CircleAdapter($lib);
clientCode($adapter);

?>
