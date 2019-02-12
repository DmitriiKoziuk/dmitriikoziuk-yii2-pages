<?php
namespace DmitriiKoziuk\yii2Pages\entities;

use DmitriiKoziuk\yii2Pages\forms\PageInputForm;
use DmitriiKoziuk\yii2Pages\records\PageRecord;

class PageEntity
{
    /**
     * @var PageRecord
     */
    private $_pageRecord;

    /**
     * @var string
     */
    private $_content;

    public function __construct(PageRecord $pageRecord, string $content)
    {
        $this->_pageRecord = $pageRecord;
        $this->_content = $content;
    }

    public function getId(): string
    {
        return $this->_pageRecord->id;
    }

    public function getName(): string
    {
        return $this->_pageRecord->name;
    }

    public function getSlug(): string
    {
        return $this->_pageRecord->slug;
    }

    public function getUrl(): string
    {
        return $this->_pageRecord->url;
    }

    public function getContent(): string
    {
        return $this->_content;
    }

    public function getMetaTitle(): string
    {
        return $this->_pageRecord->meta_title;
    }

    public function getMetaDescription(): string
    {
        return $this->_pageRecord->meta_description;
    }

    public function getInputForm(): PageInputForm
    {
        $pageInputForm = new PageInputForm();
        $pageInputForm->setAttributes($this->_pageRecord->getAttributes());
        $pageInputForm->content = $this->_content;
        return $pageInputForm;
    }
}