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

    /**
     * Every setting this module stores lives under this prefix.
     *
     * Up to 2.0.0 the settings were written under bare names - 'background',
     * 'text', 'theme', 'width', 'height', 'effect' and so on - into the
     * shop-wide ps_configuration table. Those names are generic enough that
     * another module could be using the same row, so this module could read a
     * value it never wrote, and uninstalling it deleted rows that may have
     * belonged to something else. upgrade-2.1.0.php copies the old values
     * across.
     */
    const CONF_PREFIX = 'BTTM_';

    /**
     * Map a short setting name onto the configuration key it is stored under.
     */
    protected static function cfgKey($key)
    {
        return self::CONF_PREFIX . Tools::strtoupper($key);
    }

    protected static function cfgGet($key)
    {
        return Configuration::get(self::cfgKey($key));
    }

    protected static function cfgSet($key, $value)
    {
        return Configuration::updateValue(self::cfgKey($key), $value);
    }

    protected static function cfgDelete($key)
    {
        return Configuration::deleteByName(self::cfgKey($key));
    }

    public function __construct()
    {
        $this->name = 'backtotopmeg';
        $this->tab = 'administration';
        $this->version = '2.2.0';
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
        require_once dirname(__FILE__) . '/classes/MegVentureReviewNudge.php';

        self::cfgSet('front_enable', true);
        self::cfgSet('back_enable', true);
        self::cfgSet('background', '#5D5D5D');
        self::cfgSet('text', '#FFFFFF');
        self::cfgSet('effect', 'zoom');
        self::cfgSet('height', 40);
        self::cfgSet('width', 40);
        self::cfgSet('margin_x', 20);
        self::cfgSet('margin_y', 20);
        self::cfgSet('scrollAnimation', 500);
        self::cfgSet('theme', 'fawesome');
        self::cfgSet('z_index', 999);
        self::cfgSet('front_enable_stb', true);
        self::cfgSet('back_enable_stb', true);
        self::cfgSet('background_stb', '#5D5D5D');
        self::cfgSet('text_stb', '#FFFFFF');
        self::cfgSet('height_stb', 40);
        self::cfgSet('width_stb', 40);
        self::cfgSet('margin_x_stb', 20);
        self::cfgSet('margin_y_stb', 20);
        self::cfgSet('scrollAnimation_stb', 500);
        self::cfgSet('theme_stb', 'fawesome');
        self::cfgSet('z_index_stb', 999);

        return parent::install()
        && $this->registerHook('header')
        && $this->registerHook('backOfficeHeader')
        && $this->registerHook('displayBackOfficeHeader')
        && $this->registerHook('displayHeader')
        && MegVentureReviewNudge::onInstall();
    }

    public function uninstall()
    {
        require_once dirname(__FILE__) . '/classes/MegVentureReviewNudge.php';
        MegVentureReviewNudge::onUninstall();

        self::cfgDelete('front_enable');
        self::cfgDelete('back_enable');
        self::cfgDelete('button_code');
        self::cfgDelete('background');
        self::cfgDelete('text');
        self::cfgDelete('effect');
        self::cfgDelete('height');
        self::cfgDelete('width');
        self::cfgDelete('margin_x');
        self::cfgDelete('margin_y');
        self::cfgDelete('scrollAnimation');
        self::cfgDelete('theme');
        self::cfgDelete('z_index');
        self::cfgDelete('front_enable_stb');
        self::cfgDelete('back_enable_stb');
        self::cfgDelete('background_stb');
        self::cfgDelete('text_stb');
        self::cfgDelete('height_stb');
        self::cfgDelete('width_stb');
        self::cfgDelete('margin_x_stb');
        self::cfgDelete('margin_y_stb');
        self::cfgDelete('scrollAnimation_stb');
        self::cfgDelete('theme_stb');
        self::cfgDelete('z_index_stb');

        return parent::uninstall();
    }

    public function getContent()
    {
        require_once _PS_MODULE_DIR_ . 'backtotopmeg/classes/MegVentureAdsWidget.php';
        require_once _PS_MODULE_DIR_ . 'backtotopmeg/classes/MegVentureReviewNudge.php';

        // May redirect (review click) — before anything renders on purpose.
        // Concatenated configure URL on purpose: getAdminLink()'s $params
        // argument does not exist on the oldest supported cores.
        $nudge = MegVentureReviewNudge::handleRequest($this)
            . MegVentureReviewNudge::render(
                $this,
                $this->context->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name
            );

        $this->html = '';
        $this->html2 = '';

        $this->context->controller->addCSS($this->_path . 'views/css/all.css');
        $this->context->controller->addJS($this->_path . 'views/js/admin/preview.js');

        $this->context->smarty->assign('preview', $this->getConfigFormValues() + $this->getConfigFormValues_stb());
        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        $ads = MegVentureAdsWidget::render('https://megventure.com/index.php?fc=module&module=virtualproductcombination&controller=adswidget');

        if (((bool) Tools::isSubmit('submitBacktotopmegModule')) == true) {
            $this->postProcess();

            return $nudge . $this->html . $output . $this->renderForm() . $this->renderForm_stb() . $ads;
        } elseif (((bool) Tools::isSubmit('submitBacktotopmegModule_stb')) == true) {
            $this->postProcess2();

            return $nudge . $this->html2 . $output . $this->renderForm() . $this->renderForm_stb() . $ads;
        } else {
            return $nudge . $output . $this->renderForm() . $this->renderForm_stb() . $ads;
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
            'front_enable' => self::cfgGet('front_enable'),
            'back_enable' => self::cfgGet('back_enable'),
            'background' => self::cfgGet('background'),
            'text' => self::cfgGet('text'),
            'effect' => self::cfgGet('effect'),
            'height' => self::cfgGet('height'),
            'width' => self::cfgGet('width'),
            'margin_x' => self::cfgGet('margin_x'),
            'margin_y' => self::cfgGet('margin_y'),
            'theme' => self::cfgGet('theme'),
            'scrollAnimation' => self::cfgGet('scrollAnimation'),
            'z_index' => self::cfgGet('z_index'),
        ];
    }

    protected function getConfigFormValues_stb()
    {
        return [
            'front_enable_stb' => self::cfgGet('front_enable_stb'),
            'back_enable_stb' => self::cfgGet('back_enable_stb'),
            'background_stb' => self::cfgGet('background_stb'),
            'text_stb' => self::cfgGet('text_stb'),
            'height_stb' => self::cfgGet('height_stb'),
            'width_stb' => self::cfgGet('width_stb'),
            'margin_x_stb' => self::cfgGet('margin_x_stb'),
            'margin_y_stb' => self::cfgGet('margin_y_stb'),
            'theme_stb' => self::cfgGet('theme_stb'),
            'scrollAnimation_stb' => self::cfgGet('scrollAnimation_stb'),
            'z_index_stb' => self::cfgGet('z_index_stb'),
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
                self::cfgSet($key, Tools::getValue($key));
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
                self::cfgSet($key, Tools::getValue($key));
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
        $showTop = (bool) self::cfgGet($isBackOffice ? 'back_enable' : 'front_enable');
        $showBottom = (bool) self::cfgGet($isBackOffice ? 'back_enable_stb' : 'front_enable_stb');

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
                'background' => self::cfgGet('background'),
                'color' => self::cfgGet('text'),
                'effect' => self::cfgGet('effect'),
                'height' => (int) self::cfgGet('height'),
                'width' => (int) self::cfgGet('width'),
                'marginX' => (int) self::cfgGet('margin_x'),
                'marginY' => (int) self::cfgGet('margin_y'),
                'scrollAnimation' => (int) self::cfgGet('scrollAnimation'),
                'theme' => self::cfgGet('theme'),
                'zIndex' => (int) self::cfgGet('z_index'),
            ],
            'scrollToBottomSettings' => [
                'enabled' => $showBottom,
                'isAdmin' => $isBackOffice,
                'background' => self::cfgGet('background_stb'),
                'color' => self::cfgGet('text_stb'),
                'height' => (int) self::cfgGet('height_stb'),
                'width' => (int) self::cfgGet('width_stb'),
                'marginX' => (int) self::cfgGet('margin_x_stb'),
                'marginY' => (int) self::cfgGet('margin_y_stb'),
                'scrollAnimation' => (int) self::cfgGet('scrollAnimation_stb'),
                'theme' => self::cfgGet('theme_stb'),
                'zIndex' => (int) self::cfgGet('z_index_stb'),
            ],
        ]);

        $this->context->smarty->assign([
            'show_top' => $showTop,
            'show_bottom' => $showBottom,
        ]);

        return $this->display(__FILE__, 'views/templates/front/scroll_buttons.tpl');
    }
}
