<?php

class ValidationTest extends PHPUnit_Framework_TestCase
{

    protected $image;

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

        $messages = [
            "required"      => "The :attribute is required",
            "contains"      => "The :attribute value must be :value",
            "max"           => [
                "numeric" => "The :attribute may not be greater than :value.",
                "file"    => "The :attribute may not be greater than :value kilobytes.",
                "string"  => "The :attribute may not be greater than :value characters.",
                "array"   => "The :attribute may not have more than :value items.",
            ],
            "min"           => [
                "numeric" => "The :attribute must be at least :value.",
                "file"    => "The :attribute must be at least :value kilobytes.",
                "string"  => "The :attribute must be at least :value characters.",
                "array"   => "The :attribute must have at least :value items.",
            ],
            "exact_len"     => "The :attribute len must be :value",
            "valid_email"   => "The :attribute format is invalid.",
            "alpha"         => "The :attribute may only contain letters.",
            "alpha_space"   => "The :attribute may only contain letters and spaces.",
            "alpha_dash"    => "The :attribute may only contain letters, numbers, and dashes.",
            "alpha_numeric" => "The :attribute may only contain letters and numbers.",
            "numeric"       => "The :attribute must be a number.",
            "integer"       => "The :attribute must be an integer.",
        ];

        return [
            [$messages, '', 'required', ['The name is required']],
            [$messages, '', 'contains:home:donne', ['The name value must be home,donne']],
            [$messages, 'homm', 'contains:home:donne', ['The name value must be home,donne']],
            [$messages, '', 'integer', ['The name must be an integer.']],
            [$messages, 're', 'integer', ['The name must be an integer.']],
            [$messages, '543d', 'integer', ['The name must be an integer.']],
            [$messages, 'fffffg', 'max:string:5', ['The name may not be greater than 5 characters.']],
            [$messages, 6, 'max:numeric:5', ['The name may not be greater than 5.']],
            [$messages, [1, 4, 5, 6, 7, 8], 'max:array:5', ['The name may not have more than 5 items.']],
            [$messages, 'fff   ', 'max:string:5', ['The name may not be greater than 5 characters.']],
            [$messages, 'fff 342', 'max:string:5', ['The name may not be greater than 5 characters.']],
            [$messages, 'a', 'min:string:3', ['The name must be at least 3 characters.']],
            [$messages, 'ab', 'min:string:3', ['The name must be at least 3 characters.']],
            [$messages, '  ', 'min:string:3', ['The name must be at least 3 characters.']],
            [$messages, 7, 'min:numeric:10', ['The name must be at least 10.']],
        ];
    }

    /**
     * @dataProvider getFilters
     */
    public function testValidationsError($messages, $data, $validators, $response)
    {
        $validation = new Filtval\Validation([
            'name' => $validators
        ], $messages);

        $validation->validate(['name' => $data]);
        $errors = $validation->getErrors();

        $this->assertEquals($errors['name'], $response);
    }


    public function testValidationsErrorImage()
    {
        $this->image = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            __DIR__ . '/i.jpg',
            'i.jpg',
            'image/jpg',
            63
        );
        $image = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            __DIR__ . '/i.jpg',
            'i.jpg',
            'image/jpg',
            63
        );
        $validation = new Filtval\Validation([
            'name' => 'max:file:40',
            'file' => 'min:file:100'
        ], [
            "max" => [
                "numeric" => "The :attribute may not be greater than :value.",
                "file"    => "The :attribute may not be greater than :value kilobytes.",
                "string"  => "The :attribute may not be greater than :value characters.",
                "array"   => "The :attribute may not have more than :value items.",
            ],
            "min"           => [
                "numeric" => "The :attribute must be at least :value.",
                "file"    => "The :attribute must be at least :value kilobytes.",
                "string"  => "The :attribute must be at least :value characters.",
                "array"   => "The :attribute must have at least :value items.",
            ],
        ]);

        $validation->validate(['name' => $this->image, 'file' => $image]);
        $errors = $validation->getErrors();

        $this->assertEquals($errors['name'], ['The name may not be greater than 40 kilobytes.']);
        $this->assertEquals($errors['file'], ['The file must be at least 100 kilobytes.']);
    }
}