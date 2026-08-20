<?php
/**
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2007-2026 MEG Venture & Consulting Ltd.
 * @license   https://opensource.org/licenses/MIT MIT License
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class Backtotopmeg extends Module
{
    private $html = '';

    private $html2 = '';

    private $post_errors = [];

    public function __construct()
    {
        $this->name = 'backtotopmeg';
        $this->tab = 'administration';
        $this->version = '2.0.0';
        $this->author = 'MEG Venture';
        $this->need_instance = 0;
        $this->module_key = '94f25f128f4703813f076d5e25ca4ac0';
        $this->ps_versions_compliancy = [
            'min' => '1.5.0.0',
            'max' => '9.99.99',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Smart Scroll Buttons - Back to Top and Scroll to Bottom');
        $this->description = $this->l('Add fully customizable, one-click Back to Top and Scroll to Bottom buttons to your shop, with a live preview in the settings page.');
    }

    public function install()
    {
        Configuration::updateValue('front_enable', true);
        Configuration::updateValue('back_enable', true);
        Configuration::updateValue('background', '#5D5D5D');
        Configuration::updateValue('text', '#FFFFFF');
        Configuration::updateValue('effect', 'zoom');
        Configuration::updateValue('height', 40);
        Configuration::updateValue('width', 40);
        Configuration::updateValue('margin_x', 20);
        Configuration::updateValue('margin_y', 20);
        Configuration::updateValue('scrollAnimation', 500);
        Configuration::updateValue('theme', 'fawesome');
        Configuration::updateValue('z_index', 999);
        Configuration::updateValue('front_enable_stb', true);
        Configuration::updateValue('back_enable_stb', true);
        Configuration::updateValue('background_stb', '#5D5D5D');
        Configuration::updateValue('text_stb', '#FFFFFF');
        Configuration::updateValue('height_stb', 40);
        Configuration::updateValue('width_stb', 40);
        Configuration::updateValue('margin_x_stb', 20);
        Configuration::updateValue('margin_y_stb', 20);
        Configuration::updateValue('scrollAnimation_stb', 500);
        Configuration::updateValue('theme_stb', 'fawesome');
        Configuration::updateValue('z_index_stb', 999);

        return parent::install()
        && $this->registerHook('header')
        && $this->registerHook('backOfficeHeader')
        && $this->registerHook('displayBackOfficeHeader')
        && $this->registerHook('displayHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('front_enable');
        Configuration::deleteByName('back_enable');
        Configuration::deleteByName('button_code');
        Configuration::deleteByName('background');
        Configuration::deleteByName('text');
        Configuration::deleteByName('effect');
        Configuration::deleteByName('height');
        Configuration::deleteByName('width');
        Configuration::deleteByName('margin_x');
        Configuration::deleteByName('margin_y');
        Configuration::deleteByName('scrollAnimation');
        Configuration::deleteByName('theme');
        Configuration::deleteByName('z_index');
        Configuration::deleteByName('front_enable_stb');
        Configuration::deleteByName('back_enable_stb');
        Configuration::deleteByName('background_stb');
        Configuration::deleteByName('text_stb');
        Configuration::deleteByName('height_stb');
        Configuration::deleteByName('width_stb');
        Configuration::deleteByName('margin_x_stb');
        Configuration::deleteByName('margin_y_stb');
        Configuration::deleteByName('scrollAnimation_stb');
        Configuration::deleteByName('theme_stb');
        Configuration::deleteByName('z_index_stb');

        return parent::uninstall();
    }

    public function getContent()
    {
        require_once _PS_MODULE_DIR_ . 'backtotopmeg/classes/MegVentureAdsWidget.php';

        $this->html = '';
        $this->html2 = '';

        $this->context->controller->addCSS($this->_path . 'views/css/all.css');
        $this->context->controller->addJS($this->_path . 'views/js/admin/preview.js');

        $this->context->smarty->assign('preview', $this->getConfigFormValues() + $this->getConfigFormValues_stb());
        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        $ads = MegVentureAdsWidget::render('https://megventure.com/index.php?fc=module&module=virtualproductcombination&controller=adswidget');

        if (((bool) Tools::isSubmit('submitBacktotopmegModule')) == true) {
            $this->postProcess();

            return $this->html . $output . $this->renderForm() . $this->renderForm_stb() . $ads;
        } elseif (((bool) Tools::isSubmit('submitBacktotopmegModule_stb')) == true) {
            $this->postProcess2();

            return $this->html2 . $output . $this->renderForm() . $this->renderForm_stb() . $ads;
        } else {
            return $output . $this->renderForm() . $this->renderForm_stb() . $ads;
        }
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->id = 'backtotopmeg';
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitBacktotopmegModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
        . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$this->getConfigForm()]);
    }

    protected function renderForm_stb()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->id = 'backtotopmeg_stb';
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitBacktotopmegModule_stb';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
        . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFormValues_stb(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$this->getConfigForm_stb()]);
    }

    protected function getConfigForm()
    {
        return [
            'form' => [
                'legend' => [
                    'title' => $this->l('1. Back to Top button'),
                    'icon' => 'icon-arrow-up',
                ],
                'description' => $this->l('Shown once a visitor scrolls down the page. Clicking it smoothly scrolls back to the top.'),
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('Show on the shop (front office)'),
                        'name' => 'front_enable',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ],
                        ],
                        'desc' => $this->l('Display the button to your customers on the front office.'),
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Show in the back office'),
                        'name' => 'back_enable',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'back_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ],
                            [
                                'id' => 'back_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ],
                        ],
                        'desc' => $this->l('Display the button for employees inside your admin panel.'),
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Background color'),
                        'name' => 'background',
                        'class' => 'traditionb',
                        'size' => 20,
                        'hint' => $this->l('Ex: #5D5D5D'),
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Icon color'),
                        'name' => 'text',
                        'class' => 'traditionb',
                        'size' => 20,
                        'hint' => $this->l('Ex: #FFFFFF'),
                    ],
                    [
                        'type' => 'radio',
                        'label' => $this->l('Appear animation'),
                        'name' => 'effect',
                        'class' => 't',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'none',
                                'value' => 'none',
                                'label' => $this->l('None'),
                            ],
                            [
                                'id' => 'spin',
                                'value' => 'spin',
                                'label' => $this->l('Spin'),
                            ],
                            [
                                'id' => 'fade',
                                'value' => 'fade',
                                'label' => $this->l('Fade'),
                            ],
                            [
                                'id' => 'zoom',
                                'value' => 'zoom',
                                'label' => $this->l('Zoom'),
                            ],
                            [
                                'id' => 'spin-inverse',
                                'value' => 'spin-inverse',
                                'label' => $this->l('Spin-Inverse'),
                            ],
                        ],
                        'desc' => $this->l('How the button animates in and out as visitors scroll.'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Ex: 40'),
                        'name' => 'height',
                        'label' => $this->l('Height'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Ex: 40'),
                        'name' => 'width',
                        'label' => $this->l('Width'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Distance from the right edge of the screen.'),
                        'name' => 'margin_x',
                        'label' => $this->l('Right margin'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Distance from the bottom edge of the screen.'),
                        'name' => 'margin_y',
                        'label' => $this->l('Bottom margin'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'ms',
                        'desc' => $this->l('Ex: 500. Higher is a slower, smoother scroll.'),
                        'name' => 'scrollAnimation',
                        'label' => $this->l('Scroll speed'),
                    ],
                    [
                        'type' => 'radio',
                        'label' => $this->l('Shape'),
                        'name' => 'theme',
                        'values' => [
                            [
                                'id' => 'fawesome',
                                'value' => 'fawesome',
                                'label' => $this->l('Rounded square'),
                            ],
                            [
                                'id' => 'default',
                                'value' => 'default',
                                'label' => $this->l('Circle'),
                            ],
                        ],
                        'desc' => $this->l('The overall shape of the button.'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Stacking layer. Higher values stay on top of other elements. Ex: 999'),
                        'name' => 'z_index',
                        'label' => $this->l('Z-index'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }

    protected function getConfigForm_stb()
    {
        return [
            'form' => [
                'legend' => [
                    'title' => $this->l('2. Scroll to Bottom button'),
                    'icon' => 'icon-arrow-down',
                ],
                'description' => $this->l('Shown while there is still more page to scroll through. Clicking it jumps to the bottom of the page.'),
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('Show on the shop (front office)'),
                        'name' => 'front_enable_stb',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ],
                        ],
                        'desc' => $this->l('Display the button to your customers on the front office.'),
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Show in the back office'),
                        'name' => 'back_enable_stb',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'back_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ],
                            [
                                'id' => 'back_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ],
                        ],
                        'desc' => $this->l('Display the button for employees inside your admin panel.'),
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Background color'),
                        'name' => 'background_stb',
                        'class' => 'traditionb',
                        'size' => 20,
                        'hint' => $this->l('Ex: #5D5D5D'),
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Icon color'),
                        'name' => 'text_stb',
                        'class' => 'traditionb',
                        'size' => 20,
                        'hint' => $this->l('Ex: #FFFFFF'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Ex: 40'),
                        'name' => 'height_stb',
                        'label' => $this->l('Height'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Ex: 40'),
                        'name' => 'width_stb',
                        'label' => $this->l('Width'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Distance from the right edge of the screen.'),
                        'name' => 'margin_x_stb',
                        'label' => $this->l('Right margin'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Distance from the top edge of the screen.'),
                        'name' => 'margin_y_stb',
                        'label' => $this->l('Top margin'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'ms',
                        'desc' => $this->l('Ex: 500. Higher is a slower, smoother scroll.'),
                        'name' => 'scrollAnimation_stb',
                        'label' => $this->l('Scroll speed'),
                    ],
                    [
                        'type' => 'radio',
                        'label' => $this->l('Shape'),
                        'name' => 'theme_stb',
                        'values' => [
                            [
                                'id' => 'fawesome',
                                'value' => 'fawesome',
                                'label' => $this->l('Rounded square'),
                            ],
                            [
                                'id' => 'default',
                                'value' => 'default',
                                'label' => $this->l('Circle'),
                            ],
                        ],
                        'desc' => $this->l('The overall shape of the button.'),
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Stacking layer. Higher values stay on top of other elements. Ex: 999'),
                        'name' => 'z_index_stb',
                        'label' => $this->l('Z-index'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }

    protected function getConfigFormValues()
    {
        return [
            'front_enable' => Configuration::get('front_enable'),
            'back_enable' => Configuration::get('back_enable'),
            'background' => Configuration::get('background'),
            'text' => Configuration::get('text'),
            'effect' => Configuration::get('effect'),
            'height' => Configuration::get('height'),
            'width' => Configuration::get('width'),
            'margin_x' => Configuration::get('margin_x'),
            'margin_y' => Configuration::get('margin_y'),
            'theme' => Configuration::get('theme'),
            'scrollAnimation' => Configuration::get('scrollAnimation'),
            'z_index' => Configuration::get('z_index'),
        ];
    }

    protected function getConfigFormValues_stb()
    {
        return [
            'front_enable_stb' => Configuration::get('front_enable_stb'),
            'back_enable_stb' => Configuration::get('back_enable_stb'),
            'background_stb' => Configuration::get('background_stb'),
            'text_stb' => Configuration::get('text_stb'),
            'height_stb' => Configuration::get('height_stb'),
            'width_stb' => Configuration::get('width_stb'),
            'margin_x_stb' => Configuration::get('margin_x_stb'),
            'margin_y_stb' => Configuration::get('margin_y_stb'),
            'theme_stb' => Configuration::get('theme_stb'),
            'scrollAnimation_stb' => Configuration::get('scrollAnimation_stb'),
            'z_index_stb' => Configuration::get('z_index_stb'),
        ];
    }

    /**
     * Validate a set of posted fields against simple rules and return the list of error messages.
     */
    protected function validateFields(array $rules)
    {
        $errors = [];

        foreach ($rules as $key => $rule) {
            $value = Tools::getValue($key);

            if (!empty($rule['required']) && $value === '') {
                $errors[] = sprintf($this->l('%s must be set.'), $rule['label']);
                continue;
            }

            if ($rule['type'] === 'color' && !Validate::isColor($value)) {
                $errors[] = sprintf($this->l('%1$s: "%2$s" is not a valid color.'), $rule['label'], $value);
            } elseif ($rule['type'] === 'int' && (!Validate::isInt($value) || (int) $value < 0)) {
                $errors[] = sprintf($this->l('%1$s: "%2$s" is not valid. Enter a whole number of 0 or more.'), $rule['label'], $value);
            } elseif ($rule['type'] === 'choice' && !empty($rule['choices']) && !in_array($value, $rule['choices'], true)) {
                $errors[] = sprintf($this->l('%1$s: "%2$s" is not a valid option.'), $rule['label'], $value);
            }
        }

        return $errors;
    }

    protected function postProcess()
    {
        $this->post_errors = $this->validateFields([
            'background' => ['required' => true, 'type' => 'color', 'label' => $this->l('Back to Top background color')],
            'text' => ['required' => true, 'type' => 'color', 'label' => $this->l('Back to Top icon color')],
            'effect' => ['required' => true, 'type' => 'choice', 'choices' => ['none', 'spin', 'fade', 'zoom', 'spin-inverse'], 'label' => $this->l('Back to Top animation')],
            'theme' => ['required' => true, 'type' => 'choice', 'choices' => ['fawesome', 'default'], 'label' => $this->l('Back to Top shape')],
            'scrollAnimation' => ['required' => true, 'type' => 'int', 'label' => $this->l('Back to Top scroll speed')],
            'height' => ['type' => 'int', 'label' => $this->l('Back to Top height')],
            'width' => ['type' => 'int', 'label' => $this->l('Back to Top width')],
            'margin_x' => ['type' => 'int', 'label' => $this->l('Back to Top right margin')],
            'margin_y' => ['type' => 'int', 'label' => $this->l('Back to Top bottom margin')],
            'z_index' => ['type' => 'int', 'label' => $this->l('Back to Top z-index')],
        ]);

        if (!count($this->post_errors)) {
            foreach (array_keys($this->getConfigFormValues()) as $key) {
                Configuration::updateValue($key, Tools::getValue($key));
            }
            $this->html .= $this->displayConfirmation($this->l('Settings updated successfully.'));
        } else {
            foreach ($this->post_errors as $err) {
                $this->html .= $this->displayError($err);
            }
        }
    }

    protected function postProcess2()
    {
        $this->post_errors = $this->validateFields([
            'background_stb' => ['required' => true, 'type' => 'color', 'label' => $this->l('Scroll to Bottom background color')],
            'text_stb' => ['required' => true, 'type' => 'color', 'label' => $this->l('Scroll to Bottom icon color')],
            'theme_stb' => ['required' => true, 'type' => 'choice', 'choices' => ['fawesome', 'default'], 'label' => $this->l('Scroll to Bottom shape')],
            'scrollAnimation_stb' => ['required' => true, 'type' => 'int', 'label' => $this->l('Scroll to Bottom scroll speed')],
            'height_stb' => ['type' => 'int', 'label' => $this->l('Scroll to Bottom height')],
            'width_stb' => ['type' => 'int', 'label' => $this->l('Scroll to Bottom width')],
            'margin_x_stb' => ['type' => 'int', 'label' => $this->l('Scroll to Bottom right margin')],
            'margin_y_stb' => ['type' => 'int', 'label' => $this->l('Scroll to Bottom top margin')],
            'z_index_stb' => ['type' => 'int', 'label' => $this->l('Scroll to Bottom z-index')],
        ]);

        if (!count($this->post_errors)) {
            foreach (array_keys($this->getConfigFormValues_stb()) as $key) {
                Configuration::updateValue($key, Tools::getValue($key));
            }
            $this->html2 .= $this->displayConfirmation($this->l('Settings updated successfully.'));
        } else {
            foreach ($this->post_errors as $err) {
                $this->html2 .= $this->displayError($err);
            }
        }
    }

    public function hookDisplayBackOfficeHeader()
    {
        return $this->hookBackOfficeHeader();
    }

    public function hookBackOfficeHeader()
    {
        return $this->renderScrollButtons(true);
    }

    public function hookDisplayHeader()
    {
        return $this->hookHeader();
    }

    /**
     * Add the CSS & JavaScript files for the front office and render the buttons.
     */
    public function hookHeader()
    {
        return $this->renderScrollButtons(false);
    }

    /**
     * Shared asset loading + rendering for both the front office and back office hooks.
     * Both buttons use the exact same CSS/JS, only the enable flags differ.
     */
    protected function renderScrollButtons($isBackOffice)
    {
        $showTop = (bool) Configuration::get($isBackOffice ? 'back_enable' : 'front_enable');
        $showBottom = (bool) Configuration::get($isBackOffice ? 'back_enable_stb' : 'front_enable_stb');

        if (!$showTop && !$showBottom) {
            return '';
        }

        $this->context->controller->addCSS($this->_path . 'views/css/all.css');
        $this->context->controller->addCSS($this->_path . 'views/css/scrollbuttons.css');
        $this->context->controller->addJS($this->_path . 'views/js/scrollbuttons.js');

        Media::addJsDef([
            'backToTopSettings' => [
                'enabled' => $showTop,
                'isAdmin' => $isBackOffice,
                'background' => Configuration::get('background'),
                'color' => Configuration::get('text'),
                'effect' => Configuration::get('effect'),
                'height' => (int) Configuration::get('height'),
                'width' => (int) Configuration::get('width'),
                'marginX' => (int) Configuration::get('margin_x'),
                'marginY' => (int) Configuration::get('margin_y'),
                'scrollAnimation' => (int) Configuration::get('scrollAnimation'),
                'theme' => Configuration::get('theme'),
                'zIndex' => (int) Configuration::get('z_index'),
            ],
            'scrollToBottomSettings' => [
                'enabled' => $showBottom,
                'isAdmin' => $isBackOffice,
                'background' => Configuration::get('background_stb'),
                'color' => Configuration::get('text_stb'),
                'height' => (int) Configuration::get('height_stb'),
                'width' => (int) Configuration::get('width_stb'),
                'marginX' => (int) Configuration::get('margin_x_stb'),
                'marginY' => (int) Configuration::get('margin_y_stb'),
                'scrollAnimation' => (int) Configuration::get('scrollAnimation_stb'),
                'theme' => Configuration::get('theme_stb'),
                'zIndex' => (int) Configuration::get('z_index_stb'),
            ],
        ]);

        $this->context->smarty->assign([
            'show_top' => $showTop,
            'show_bottom' => $showBottom,
        ]);

        return $this->display(__FILE__, 'views/templates/front/scroll_buttons.tpl');
    }
}
