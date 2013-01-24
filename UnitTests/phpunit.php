<?php 
require_once 'PHPUnit2/Framework/TestSuite.php';
require_once dirname(__FILE__) . "/ValidatorTest.php";


$testClassNames = array(
    "ValidatorTest"
    );

foreach ($testClassNames as $test) {
    $phpunit = new PHPUnit2_Framework_TestSuite($test);
    $phpunit->run();
}