<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class bs_videoslider extends Module
{
    const IMAGE_DIR = _PS_MODULE_DIR_ . 'bs_videoslider/views/img/videos/';

    public function __construct()
    {
        $this->name = 'bs_videoslider';
        $this->tab = 'front_office_features';
        $this->version = '1.0.2';
        $this->author = 'bizosiz';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->module_key = '';

        parent::__construct();

        $this->displayName = $this->l('Video Slider');
        $this->description = $this->l('Responsive video slider with Owl Carousel');
    }

    public function install()
    {
        return parent::install() &&
            $this->createTables() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('display') &&
            $this->installTab() &&
            $this->createImageDir();
    }

    private function createTables()
    {
        $success = true;

        $sqlSliders = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'bs_videoslider` (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NOT NULL,
            `hook` VARCHAR(255) NOT NULL,
            `items` INT(11) NOT NULL DEFAULT 4,
            `loop` TINYINT(1) NOT NULL DEFAULT 1,
            `nav` TINYINT(1) NOT NULL DEFAULT 1,
            `dots` TINYINT(1) NOT NULL DEFAULT 0,
            `autoplay_timeout` INT(11) DEFAULT NULL,
            `active` TINYINT(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        $sqlVideos = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'bs_videoslider_video` (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_slider` INT(11) UNSIGNED NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `video_content` TEXT NOT NULL,
            `thumbnail` VARCHAR(255),
            `position` INT(10) UNSIGNED NOT NULL,
            PRIMARY KEY (`id`),
            INDEX (`id_slider`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        $success &= Db::getInstance()->execute($sqlSliders);
        $success &= Db::getInstance()->execute($sqlVideos);

        return $success;
    }

    private function createImageDir()
    {
        if (!file_exists(self::IMAGE_DIR)) {
            return @mkdir(self::IMAGE_DIR, 0755, true);
        }
        return true;
    }

    private function installTab()
    {
        $tab = new Tab();
        $tab->class_name = 'AdminBsVideoSlider';
        $tab->module = $this->name;
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminCatalog');
        $tab->position = 1;
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->l('Video Sliders');
        }
        return $tab->add();
    }

    public function uninstall()
    {
        return parent::uninstall() &&
            $this->uninstallTab() &&
            $this->deleteTables() &&
            $this->removeImageDir();
    }

    private function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminBsVideoSlider');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }
        return true;
    }

    private function deleteTables()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'bs_videoslider`') &&
            Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'bs_videoslider_video`');
    }

    private function removeImageDir()
    {
        if (file_exists(self::IMAGE_DIR)) {
            $files = glob(self::IMAGE_DIR . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
            return @rmdir(self::IMAGE_DIR);
        }
        return true;
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->registerStylesheet(
            'bs-owl-carousel',
            'modules/' . $this->name . '/vendor/owlcarousel/owl.carousel.min.css'
        );

        $this->context->controller->registerJavascript(
            'bs-owl-carousel',
            'modules/' . $this->name . '/vendor/owlcarousel/owl.carousel.min.js',
            ['position' => 'bottom', 'priority' => 150]
        );

        $this->context->controller->registerStylesheet(
            'bs-videoslider-front',
            'modules/' . $this->name . '/views/css/front.css'
        );

        $this->context->controller->registerJavascript(
            'bs-videoslider-front',
            'modules/' . $this->name . '/views/js/front.js',
            ['position' => 'bottom', 'priority' => 200]
        );
    }

    public function hookDisplay($hookName)
    {
        $slider = $this->getSliderByHook($hookName);
        if ($slider && $slider['active']) {
            $videos = $this->getSliderVideos($slider['id']);
            $this->context->smarty->assign([
                'videos' => $videos,
                'slider_settings' => [
                    'items' => $slider['items'],
                    'loop' => $slider['loop'],
                    'nav' => $slider['nav'],
                    'dots' => $slider['dots'],
                    'autoplay_timeout' => $slider['autoplay_timeout']
                ],
                'image_path' => _MODULE_DIR_ . $this->name . '/views/img/videos/'
            ]);
            return $this->fetch('module:bs_videoslider/views/templates/front/slider.tpl');
        }
        return false;
    }

    private function getSliderByHook($hookName)
    {
        $sql = new DbQuery();
        $sql->select('*')
            ->from('bs_videoslider')
            ->where('hook = "' . pSQL($hookName) . '"')
            ->where('active = 1');

        return Db::getInstance()->getRow($sql);
    }

    private function getSliderVideos($idSlider)
    {
        $sql = new DbQuery();
        $sql->select('*')
            ->from('bs_videoslider_video')
            ->where('id_slider = ' . (int)$idSlider)
            ->orderBy('position ASC');

        return Db::getInstance()->executeS($sql);
    }

    public function getThumbnailPath($filename)
    {
        return _MODULE_DIR_ . $this->name . '/views/img/videos/' . $filename;
    }
}
