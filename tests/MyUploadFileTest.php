<?php

class MyUploadFileTest extends PHPUnit_Framework_TestCase
{
    protected $file;
    protected $image;

    public function setUp()
    {
        $this->file = tempnam(sys_get_temp_dir(), 'upl'); // create file
        imagepng(imagecreatetruecolor(10, 10), $this->file); // create and write image/png to it
        $this->image = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            $this->file,
            'new_image.png'
        );

        $this->image = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            __DIR__ . '/i.jpg',
            'i.jpg',
            'image/jpeg',
            63
        );
    }

    public function testMime()
    {
        $res = $this->image->getMimeType();

        $this->assertEquals('image/jpeg', $res);
    }

    public function tearDown()
    {
        unlink($this->file);
    }
}