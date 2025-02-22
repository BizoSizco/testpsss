<?php
require_once _PS_MODULE_DIR_ . 'bs_videoslider/classes/VideoSlider.php';

class AdminBsVideoSliderController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'bs_videoslider';
        $this->className = 'VideoSlider';
        $this->identifier = 'id';

        parent::__construct();

        // تنظیمات فیلدهای لیست
        $this->fields_list = [
            'id' => [
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ],
            'title' => ['title' => $this->l('Title')],
            'hook' => ['title' => $this->l('Hook')],
            'active' => [
                'title' => $this->l('Status'),
                'type' => 'bool',
                'active' => 'status',
                'align' => 'center'
            ]
        ];

        // اکشن‌های گروهی
        $this->bulk_actions = [
            'delete' => [
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            ]
        ];

        // افزودن اکشن‌های تک آیتم
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->addRowAction('manageVideos');
    }

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_btn['new_slider'] = [
            'href' => self::$currentIndex . '&add' . $this->table . '&token=' . $this->token,
            'desc' => $this->l('Add new slider'),
            'icon' => 'process-icon-new'
        ];
        parent::initPageHeaderToolbar();
    }

    public function renderList()
    {
        $this->toolbar_title = $this->l('Manage Video Sliders');

        // تنظیمات ستون اکشن‌ها
        $this->fields_list['actions'] = [
            'title' => $this->l('Actions'),
            'align' => 'center',
            'callback' => 'renderActionsButtons',
            'orderby' => false,
            'class' => 'fixed-width-xs'
        ];

        return parent::renderList();
    }

    public function renderActionsButtons($value, $row, $href, $name)
    {
        // تولید لینک‌ها
        $edit_url = self::$currentIndex . '&update' . $this->table . '&id=' . $row['id'] . '&token=' . $this->token;
        $delete_url = self::$currentIndex . '&delete' . $this->table . '&id=' . $row['id'] . '&token=' . $this->token;
        $manage_url = $this->context->link->getAdminLink('AdminBsVideoSliderVideos') . '&id_slider=' . $row['id'];

        // ساختار HTML دکمه‌ها
        return '
        <div class="btn-group">
            <a href="' . $edit_url . '" class="btn btn-default" title="' . $this->l('Edit') . '">
                <i class="icon-pencil"></i>
            </a>
            
            <a href="' . $manage_url . '" class="btn btn-default" title="' . $this->l('Manage Videos') . '">
                <i class="icon-film"></i>
            </a>

            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <i class="icon-caret-down"></i>
            </button>

            <ul class="dropdown-menu">
                <li>
                    <a href="' . $delete_url . '" onclick="return confirm(\'' . $this->l('Delete item?') . '\')">
                        <i class="icon-trash"></i> ' . $this->l('Delete') . '
                    </a>
                </li>
            </ul>
        </div>';
    }

    public function renderForm()
    {
        $hooks = Hook::getHooks();

        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Slider Settings'),
                'icon' => 'icon-cogs'
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Title'),
                    'name' => 'title',
                    'required' => true
                ],
                [
                    'type' => 'select',
                    'label' => $this->l('Hook'),
                    'name' => 'hook',
                    'options' => [
                        'query' => $hooks,
                        'id' => 'name',
                        'name' => 'name'
                    ]
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Items per slide'),
                    'name' => 'items',
                    'default_value' => 4
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Loop'),
                    'name' => 'loop',
                    'values' => [
                        ['id' => 'loop_on', 'value' => 1, 'label' => $this->l('Yes')],
                        ['id' => 'loop_off', 'value' => 0, 'label' => $this->l('No')]
                    ]
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Active'),
                    'name' => 'active',
                    'values' => [
                        ['id' => 'active_on', 'value' => 1, 'label' => $this->l('Yes')],
                        ['id' => 'active_off', 'value' => 0, 'label' => $this->l('No')]
                    ]
                ]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-primary pull-right'
            ]
        ];

        return parent::renderForm();
    }
}
