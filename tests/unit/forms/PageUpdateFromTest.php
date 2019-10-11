<?php declare(strict_types=1);

namespace DmitriiKoziuk\yii2Pages\tests;

use Yii;
use yii\di\Container;
use Faker\Provider\Base;
use Codeception\Test\Unit;
use DmitriiKoziuk\yii2Pages\forms\PageUpdateForm;

class PageUpdateFromTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _after()
    {
        Yii::$container = new Container();
    }

    /**
     * @param array $attributes
     * @dataProvider minimumValidDataProvider
     */
    public function testMinimumValid(array $attributes): void
    {
        $form = new PageUpdateForm($attributes);
        $this->assertTrue($form->validate());
    }

    /**
     * @param array $attributes
     * @dataProvider validDataProvider
     */
    public function testValid(array $attributes): void
    {
        $form = new PageUpdateForm($attributes);
        $this->assertTrue($form->validate());
        $this->assertEquals($attributes, $form->getAttributes(
            null,
            ['created_at', 'updated_at']
        ));
    }

    /**
     * @param string $attributeName
     * @param mixed  $attributeValue
     * @param string $errorMessage
     * @dataProvider notValidDataProvider
     */
    public function testNotValid(string $attributeName, $attributeValue, string $errorMessage): void
    {
        $form = new PageUpdateForm([$attributeName => $attributeValue]);
        $this->assertFalse($form->validate());
        $this->assertTrue($form->hasErrors($attributeName));
        $this->assertContains($errorMessage, $form->getErrors()[$attributeName]);
    }

    /**
     * @param string $attributeName
     * @param string $attributeValue
     * @param $expect
     * @dataProvider trimmedAttributesDataProvider
     */
    public function testAttributeTrim(string $attributeName, string $attributeValue, $expect): void
    {
        $form = new PageUpdateForm([$attributeName => $attributeValue]);
        $form->validate();
        $this->assertEquals($expect, $form->$attributeName);
    }

    public function minimumValidDataProvider(): array
    {
        return [
            'minimum valid form data' => [
                [
                    'id' => 1,
                    'name' => 'Hello',
                ],
            ],
        ];
    }

    public function validDataProvider(): array
    {
        return [
            'valid form data' => [
                [
                    'id' => 1,
                    'name' => 'Hello',
                    'is_active' => PageUpdateForm::ACTIVE,
                    'meta_title' => '',
                    'meta_description' => '',
                    'content' => 'Hello',
                ],
            ],
        ];
    }

    public function notValidDataProvider(): array
    {
        return [
            'id null' => [
                'id',
                null,
                'Id cannot be blank.',
            ],
            'id blank' => [
                'id',
                '',
                'Id cannot be blank.',
            ],
            'name null' => [
                'name',
                null,
                'Name cannot be blank.',
            ],
            'name blank' => [
                'name',
                '',
                'Name cannot be blank.',
            ],
            'name max length 150' => [
                'name',
                Base::lexify(str_repeat('?', 151)),
                'Name should contain at most 150 characters.',
            ],
            'is_active wrong status code' => [
                'is_active',
                4,
                "Page status code '4' not supported."
            ],
            'meta_title max length 255' => [
                'meta_title',
                Base::lexify(str_repeat('?', 256)),
                'Meta Title should contain at most 255 characters.',
            ],
            'meta_description max length 255' => [
                'meta_description',
                Base::lexify(str_repeat('?', 256)),
                'Meta Description should contain at most 255 characters.',
            ],
        ];
    }

    public function trimmedAttributesDataProvider(): array
    {
        return [
            ['name', ' Hello', 'Hello'],
            ['name', ' Hello ', 'Hello'],
            ['name', "\nHello ", 'Hello'],
            ['name', "\nHello\n", 'Hello'],
            ['name', "\tHello ", 'Hello'],
            ['name', "\tHello\t", 'Hello'],
            ['meta_title', ' Hello', 'Hello'],
            ['meta_title', ' Hello ', 'Hello'],
            ['meta_title', "\nHello ", 'Hello'],
            ['meta_title', "\nHello\n", 'Hello'],
            ['meta_title', "\tHello ", 'Hello'],
            ['meta_title', "\tHello\t", 'Hello'],
            ['meta_description', ' Hello', 'Hello'],
            ['meta_description', ' Hello ', 'Hello'],
            ['meta_description', "\nHello ", 'Hello'],
            ['meta_description', "\nHello\n", 'Hello'],
            ['meta_description', "\tHello ", 'Hello'],
            ['meta_description', "\tHello\t", 'Hello'],
        ];
    }
}