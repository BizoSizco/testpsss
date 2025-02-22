<?php
class VideoSliderVideo extends ObjectModel
{
    public $id;
    public $id_slider;
    public $title;
    public $video_content;
    public $thumbnail;
    public $position;

    public static $definition = [
        'table' => 'bs_videoslider_video',
        'primary' => 'id',
        'multilang' => false,
        'fields' => [
            'id_slider' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'title' => ['type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true],
            'video_content' => ['type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true],
            'thumbnail' => ['type' => self::TYPE_STRING, 'validate' => 'isFileName'],
            'position' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt']
        ],
    ];

    public function add($auto_date = true, $null_values = false)
    {
        $this->position = (int)$this->getMaxPosition() + 1;
        return parent::add($auto_date, $null_values);
    }

    public function update($null_values = false)
    {
        if (empty($this->position)) {
            $this->position = (int)$this->getMaxPosition() + 1;
        }
        return parent::update($null_values);
    }

    private function getMaxPosition()
    {
        return (int)Db::getInstance()->getValue(
            '
            SELECT MAX(position) 
            FROM `' . _DB_PREFIX_ . 'bs_videoslider_video` 
            WHERE id_slider = ' . (int)$this->id_slider
        );
    }
}
