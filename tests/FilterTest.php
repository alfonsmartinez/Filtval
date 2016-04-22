<?php

class FilterTest extends PHPUnit_Framework_TestCase
{

    protected static $filter;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public static function setUpBeforeClass()
    {
    }

    public function getFilters()
    {
        return [
            [['name' => ' example text '], 'example text'],
            [['name' => 'example   text '], 'example   text'],
            [['name' => ' example text'], 'example text'],
        ];
    }

    /**
     * @dataProvider getFilters
     */
    public function testTrim($data, $response)
    {
        $filter = new Filtval\Filter([
            'name' => 'trim'
        ]);
        $responsetext = $filter->filter($data);

        $this->assertEquals($responsetext['name'], $response);
    }

}