<?php

//1. Наблюдатель: есть сайт HandHunter.gb. На нем работники могут подыскать себе вакансию РНР-программиста. Необходимо реализовать классы искателей с их именем, почтой и стажем работы. Также реализовать возможность в любой момент встать на биржу вакансий (подписаться на уведомления), либо же, напротив, выйти из гонки за местом. Таким образом, как только появится новая вакансия программиста, все жаждущие автоматически получат уведомления на почту (можно реализовать условно).

interface IHandHunter {
    public function attach(HHObserver $observer);
    public function detach(HHObserver $observer);
    public function notify();
}

class HandHunter implements IHandHunter {
    public $vacanciesUpdate;
    private $observers;

    public function __construct() {
        $this->observers = new HHObserversStorage();
    }

    public function update($request) {
        if ($request == 'some_request') {
        return $this->vacanciesUpdate;
        }
    }

    public function attach(HHObserver $observer) {
        $this->observers->attach($observer);
    }

    public function detach(HHObserver $observer) {
        $this->observers->detach($observer);
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function someBusinessLogic() {
        $this->state = rand(0, 10);
        $this->notify();
    }
}

interface HHObserver {
    public function update(HandHunter $subject);
}

class Observer implements HHObserver {
    private $name;
    private $email;
    private $experience;

    public function __construct($name, $email, $experience) {
        $this->name = $name;
        $this->name = $email;
        $this->name = $experience;
    }

    public function update(IHandHunter $subject) {
        echo $subject;
    }
}

$hh = new HandHunter();
$observer = new Observer('Alexander', 'test@test.com', 12);
$hh->attach($observer);
$hh->someBusinessLogic();
$hh->detach($observer);

//2. Стратегия: есть интернет-магазин по продаже носков. Необходимо реализовать возможность оплаты различными способами (Qiwi, Яндекс, WebMoney). Разница лишь в обработке запроса на оплату и получение ответа от платёжной системы. В интерфейсе функции оплаты достаточно общей суммы товара и номера телефона.

calculateSomething();

class Context {
    private $strategy;

    public function __construct(Strategy $strategy) {
        $this->strategy = $strategy;
    }

    public function setStrategy(Strategy $strategy) {
        $this->strategy = $strategy;
    }

    public function doSomeBusinessLogic() {
        calculateSomething();
    }
}

interface Strategy {
    public function doAlgorithm(array $data) : array;
}

class Qiwi implements Strategy
{
    public function doAlgorithm(array $data) {
        $response = pay($data);
        return $response;
    }
}

class Yandex implements Strategy
{
    public function doAlgorithm(array $data) {
        $response = pay($data);
        return $response;
    }
}

class Webmoney implements Strategy
{
    public function doAlgorithm(array $data) {
        $response = pay($data);
        return $response;
    }
}

$context = new Context(new Qiwi());
$context->doSomeBusinessLogic();
$context->setStrategy(new Yandex());
$context->doSomeBusinessLogic();

//3. Команда: вы — разработчик продукта Macrosoft World. Это текстовый редактор с возможностями копирования, вырезания и вставки текста (пока только это). Необходимо реализовать механизм по логированию этих операций и возможностью отмены и возврата действий. Т. е., в ходе работы программы вы открываете текстовый файл .txt, выделяете участок кода (два значения: начало и конец) и выбираете, что с этим кодом делать.

interface Command {
    public function execute() : void;
}

class ComplexCommand implements Command {
    private $receiver;
    private $a;
    private $b;
    private $c;

    public function __construct(Receiver $receiver, string $a, string $b)
    {
        $this->receiver = $receiver;
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }

    public function execute() : void
    {
        $this->receiver->copyText($this->a);
        $this->receiver->cutText($this->b);
        $this->receiver->pasteText($this->c);
    }
}

class Receiver
{
    public function copyText(string $a){
        echo "logged: $a";
    }

    public function cutText(string $b){
        echo "logged: $b";
    }

    public function pasteText(string $c){
        echo "logged: $c";
    }
}

class Invoker {

    private $onStart;
    private $onFinish;

    public function setOnStart(Command $command) {
        $this->onStart = $command;
    }

    public function setOnFinish(Command $command){
        $this->onFinish = $command;
    }
}

$invoker = new Invoker();
$invoker->setOnStart(new ComplexCommand());
$receiver = new Receiver('test text');
$invoker->setOnFinish(new ComplexCommand($receiver, "Send email", "Save report"));

?>