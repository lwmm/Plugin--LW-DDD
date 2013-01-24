<?php

namespace LWddd;

require_once dirname(__FILE__) . '/../Validator.php';

/**
 * Test class for Validator.
 * Generated by PHPUnit on 2013-01-23 at 10:48:21.
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase 
{

    /**
     * @var Validator
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() 
    {
        $this->validator = new Validator();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() 
    {
        
    }

    /**
     * @todo Implement testIsEmail().
     */
    public function testIsEmail() 
    {
        $this->assertTrue($this->validator->isEmail("m.mustermann@logic-works.de"));
        $this->assertFalse($this->validator->isEmail("mmustermannlogic-worksde"));
    }

    /**
     * @todo Implement testHasMaxlength().
     */
    public function testHasMaxlength() 
    {
        $this->assertTrue($this->validator->hasMaxlength("abcd5", array("maxlength" => 5)));
        $this->assertFalse($this->validator->hasMaxlength("abcd5abc", array("maxlength" => 5)));
    }

    /**
     * @todo Implement testHasMinlength().
     */
    public function testHasMinlength() 
    {
        $this->assertTrue($this->validator->hasMinlength("abcd5", array("minlength" => 5)));
        $this->assertFalse($this->validator->hasMinlength("abc", array("minlength" => 5)));
    }

    /**
     * @todo Implement testIsRequired().
     */
    public function testIsRequired() 
    {
        $this->assertTrue($this->validator->isRequired("a"));
        $this->assertFalse($this->validator->isRequired(""));
    }

    /**
     * @todo Implement testIsAlnum().
     */
    public function testIsAlnum() 
    {
        $this->assertTrue($this->validator->isAlnum("abciehf823694523bvlsd7 892"));
        $this->assertFalse($this->validator->isAlnum("abciehf82$%)§$?94523bvlsd92"));
    }

    /**
     * @todo Implement testIsBetween().
     */
    public function testIsBetween() 
    {
        $this->assertTrue($this->validator->isBetween("2", array("value1" => "1", "value2" => "5")));
        $this->assertFalse($this->validator->isBetween("a", array("value1" => "d", "value2" => "f")));
    }

    /**
     * @todo Implement testIsDigits().
     */
    public function testIsDigits() 
    {
        $this->assertTrue($this->validator->isDigits("642364278364236842835290"));
        $this->assertFalse($this->validator->isDigits("64236427836423684ß2835290ysdfuksu"));
    }

    /**
     * @todo Implement testIsGreaterThan().
     */
    public function testIsGreaterThan() 
    {
        $this->assertTrue($this->validator->isGreaterThan("mmm", array("value" => "bbb")));
        $this->assertFalse($this->validator->isGreaterThan("aaa", array("value" => "bbb")));
    }

    /**
     * @todo Implement testIsLessThan().
     */
    public function testIsLessThan() 
    {
        $this->assertTrue($this->validator->isLessThan("aaa", array("value" => "bbb")));
        $this->assertFalse($this->validator->isLessThan("mmm", array("value" => "bbb")));
    }

    /**
     * @todo Implement testIsInt().
     */
    public function testIsInt() 
    {
        $this->assertTrue($this->validator->isInt(1));
        $this->assertFalse($this->validator->isInt("1"));
    }

    /**
     * @todo Implement testIsFiletype().
     */
    public function testIsFiletype() 
    {
        $this->assertTrue($this->validator->isFiletype(".php", array("extensions" => ":php:exe:html:js")));
        $this->assertFalse($this->validator->isFiletype(".hallo", array("extensions" => ":php:exe:html:js")));
    }

    /**
     * @todo Implement testIsImage().
     */
    public function testIsImage() 
    {
        $this->assertTrue($this->validator->isImage(".jpg"));
        $this->assertFalse($this->validator->isImage(".php"));
    }
}