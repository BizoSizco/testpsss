<?php
class VideoSlider extends ObjectModel
{
    public $id;
    public $title;
    public $hook;
    public $items;
    public $loop;
    public $nav;
    public $dots;
    public $autoplay_timeout;
    public $active;

    public static $definition = array(
        'table' => 'bs_videoslider',
        'primary' => 'id',
        'multilang' => false,
        'fields' => array(
            'title' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isCleanHtml',
                'required' => true
            ),
            'hook' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isHookName',
                'required' => true
            ),
            'items' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
            'loop' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'nav' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'dots' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'autoplay_timeout' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
            'active' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            )
        )
    );
}
