<?php
require_once _PS_MODULE_DIR_ . 'bs_videoslider/classes/VideoSliderVideo.php';

class AdminBsVideoSliderVideosController extends ModuleAdminController
{
    public $slider_id;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'bs_videoslider_video';
        $this->className = 'VideoSliderVideo';
        $this->identifier = 'id';
        $this->lang = false;

        parent::__construct();

        // دریافت شناسه اسلایدر از URL
        $this->slider_id = (int)Tools::getValue('id_slider');

        // تنظیم فیلتر برای نمایش ویدیوهای مرتبط با اسلایدر
        $this->_where = 'AND id_slider = ' . $this->slider_id;
        $this->_orderBy = 'position';

        // تنظیمات لیست ویدیوها
        $this->fields_list = [
            'id' => [
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ],
            'title' => ['title' => $this->l('Title')],
            'position' => [
                'title' => $this->l('Position'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ]
        ];

        // افزودن اکشن‌ها
        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }

    public function initToolbar()
    {
        parent::initToolbar();

        // افزودن دکمه بازگشت به لیست اسلایدرها
        $back_url = $this->context->link->getAdminLink('AdminBsVideoSlider');
        $this->toolbar_btn['back'] = [
            'href' => $back_url,
            'desc' => $this->l('Back to Sliders')
        ];
    }

    public function renderForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Video Settings'),
                'icon' => 'icon-film'
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Title'),
                    'name' => 'title',
                    'required' => true
                ],
                [
                    'type' => 'textarea',
                    'label' => $this->l('Video Content'),
                    'name' => 'video_content',
                    'required' => true,
                    'desc' => $this->l('YouTube embed code or video URL')
                ],
                [
                    'type' => 'file',
                    'label' => $this->l('Thumbnail'),
                    'name' => 'thumbnail',
                    'display_image' => true,
                    'desc' => $this->l('Upload thumbnail image (800x450px)')
                ],
                [
                    'type' => 'hidden',
                    'name' => 'id_slider'
                ]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-primary pull-right'
            ]
        ];

        // نمایش تصویر بند انگشتی اگر وجود دارد
        if ($this->object->thumbnail) {
            $this->fields_value['thumbnail'] = '<img src="' . _MODULE_DIR_ . 'bs_videoslider/views/img/videos/' . $this->object->thumbnail . '" class="img-thumbnail" style="max-width:200px">';
        }

        return parent::renderForm();
    }

    public function postProcess()
    {
        // پردازش آپلود تصویر
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['size'] > 0) {
            $uploader = new Uploader('thumbnail');
            $uploader->setSavePath(_PS_MODULE_DIR_ . 'bs_videoslider/views/img/videos/');
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);
            if ($uploader->process()) {
                $_POST['thumbnail'] = $uploader->getFileName();
            } else {
                $this->errors[] = $this->l('Error uploading image:') . ' ' . $uploader->getError();
            }
        }

        // تنظیم شناسه اسلایدر
        $_POST['id_slider'] = $this->slider_id;

        return parent::postProcess();
    }

    public function ajaxProcessUpdatePositions()
    {
        $positions = Tools::getValue('video');
        foreach ($positions as $position => $id) {
            Db::getInstance()->update($this->table, [
                'position' => (int)$position + 1
            ], 'id = ' . (int)$id);
        }
        exit(json_encode(['success' => true]));
    }
}
