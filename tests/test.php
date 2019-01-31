<?php

require_once '../vendor/autoload.php';

use Prhost\Packer\Classes\ConveyorBelt;
use Prhost\Packer\Classes\Package;
use Prhost\Packer\Classes\Worker;
use Prhost\Packer\Classes\Product;

//creating packages
$package1 = new Package();
$package1
    ->setIdentifier('package1')
    ->setWidth(20)
    ->setHeight(20)
    ->setLength(30)
    ->setWeight(1)
    ->setMaxWeight(30);  //no define to disable check max weight

$package2 = new Package();
$package2
    ->setIdentifier('package2')
    ->setWidth(20)
    ->setHeight(20)
    ->setLength(20)
    ->setWeight(1);

//create product
$product1 = new Product();
$product1
    ->setIdentifier('product1')
    ->setQuantity(1)
    ->setWeight(20)
    ->setLength(10)
    ->setWidth(30)
    ->setHeight(10)
    ->enableEitherSideIsUp();

$product2 = new Product();
$product2
    ->setIdentifier('product2')
    ->setQuantity(13)
    ->setWeight(10)
    ->setLength(10)
    ->setWidth(30)
    ->setHeight(10);

//on conveyorbelt
$conveyorBelt = new ConveyorBelt();
$conveyorBelt
    ->put($product1)
    ->put($product2);

$worker = new Worker();
$worker
    ->setConveyorBelt($conveyorBelt)
    ->setPackages([
        $package1,
        $package2,
    ]);

$volumes = $worker->arrange();

echo "<br>Total volumes: " . count($volumes) . '<br>';
foreach ($volumes as $volume) {
    var_dump($volume);
    echo "<br>==========================================================<br>";
}

//Left over products on the conveyorbelt
echo "<br>Left over products on the conveyorbelt: " . $worker->getConveyorBelt()->count() . '<br>';
foreach ($worker->getConveyorBelt()->getProducts() as $product) {
    var_dump($product);
    echo "<br>==========================================================<br>";
}