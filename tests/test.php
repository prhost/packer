<?php

require_once '../vendor/autoload.php';

use Prhost\Packer\Classes\ConveyorBelt;
use Prhost\Packer\Classes\Package;
use Prhost\Packer\Classes\Packer;
use Prhost\Packer\Classes\Product;

//Registrando as embalagens
$package1 = new Package();
$package1->setIdentifier('package1');
$package1->setWidth(20);
$package1->setHeight(20);
$package1->setLength(20);
$package1->setWeight(1);
$package1->setMaxWeight(30);  //no define to disable check max weight

$package2 = new Package();
$package2->setIdentifier('package2');
$package2->setWidth(20);
$package2->setHeight(20);
$package2->setLength(20);
$package2->setWeight(1);

$product1 = new Product();
$product1->setIdentifier('product1');
$product1->setQuantity(1);
$product1->setWeight(20);
$product1->setLength(10);
$product1->setWidth(20);
$product1->setHeight(10);

$product2 = new Product();
$product2->setIdentifier('product2');
$product2->setQuantity(13);
$product2->setWeight(10);
$product2->setLength(10);
$product2->setWidth(10);
$product2->setHeight(10);

$conveyorBelt = new ConveyorBelt();
$conveyorBelt->put($product1);
$conveyorBelt->put($product2);

$packer = new Packer();
$packer->setConveyorBelt($conveyorBelt);
$packer->setPackages([
    $package1,
    $package2,
]);

$volumes = $packer->organize();
echo "<br>Total volumes: " . count($volumes) . '<br>';
foreach ($volumes as $volume) {
    var_dump($volume);
    echo "<br>==========================================================<br>";
}

//Left over products on the conveyorbelt
echo "<br>Left over products on the conveyorbelt: " . $packer->getConveyorBelt()->count() . '<br>';
foreach ($packer->getConveyorBelt()->getProducts() as $product) {
    var_dump($product);
    echo "<br>==========================================================<br>";
}