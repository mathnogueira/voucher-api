<?php

namespace Tests\Models\Validation;

use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    
    public function test_when_converting_to_assoc_array_should_convert_only_the_fields()
    {
        $model = $this->buildModel();

        $array = $model->toAssocArray();
        $this->assertEquals(['id' => 3, 'name' => 'Elliot', 'surname' => 'Smith'], $array);
    }

    public function test_when_getting_a_non_existent_field_should_return_null()
    {
        $model = $this->buildModel();

        $this->assertEquals(null, $model->invalidField);
    }

    public function test_when_setting_a_non_existent_field_should_throw_Exception()
    {
        $this->expectException(\Exception::class);
        $model = $this->buildModel();

        $model->invalidField = 3;
    }

    public function test_when_setting_a_readonly_field_should_throw_Exception()
    {
        $this->expectException(\Exception::class);
        $model = $this->buildModel();

        $model->name = "John Doe";
    }

    private function buildModel()
    {
        $model = new ModelMock("Elliot", "Smith");
        $model->id = 3;

        return $model;
    }
}

class ModelMock extends \App\Models\Model
{
    protected $fields = ['id', 'name', 'surname'];
    protected $readonlyFields = ['name', 'surname'];

    public function __construct(string $name, string $surname)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->seal();
    }
}
