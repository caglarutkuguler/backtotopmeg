<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2020 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Backtotopmeg extends Module
{
    protected $config_form = false;
    private $html = '';
    private $post_errors = array();

    public function __construct()
    {
        $this->name = 'backtotopmeg';
        $this->tab = 'administration';
        $this->version = '1.0.3';
        $this->author = 'MEG Venture';
        $this->need_instance = 0;
        $this->module_key = '94f25f128f4703813f076d5e25ca4ac0';

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Back to Top Button');
        $this->description = $this->l('Add a customizable back to top button to your pages.');

        /* Backward compatibility */
        if (version_compare(_PS_VERSION_, '1.5.0.0 ', '<')) {
            require _PS_MODULE_DIR_ . $this->name . '/backward_compatibility/backward.php';
        }
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
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

        return parent::install() &&
        $this->registerHook('header') &&
        $this->registerHook('backOfficeHeader') &&
        $this->registerHook('displayHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('front_enable');
        Configuration::deleteByName('back_enable');
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

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $this->html = '';
        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool) Tools::isSubmit('submitBacktotopmegModule')) == true) {
            $this->postProcess();
            return $this->html . $output . $this->renderForm();
        } else {
            return $output . $this->renderForm();
        }
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
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

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable front office'),
                        'name' => 'front_enable',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ),
                        ),
                        'desc' => $this->l('enable the back to top button on the front office'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable back office'),
                        'name' => 'back_enable',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'back_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'back_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ),
                        ),
                        'desc' => $this->l('enable the back to top button on the back office'),
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Background color of the button'),
                        'name' => 'background',
                        'class' => 'traditionb',
                        'size' => 20,
                        'hint' => $this->l('Background color of the button Ex: #5D5D5D'),
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Arrow (internal) color of the button'),
                        'name' => 'text',
                        'class' => 'traditionb',
                        'size' => 20,
                        'hint' => $this->l('Arrow (internal) color of the button Ex: #FFFFFF'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Effect'),
                        'name' => 'effect',
                        'class' => 't',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'none',
                                'value' => 'none',
                                'label' => $this->l('None'),
                            ),
                            array(
                                'id' => 'spin',
                                'value' => 'spin',
                                'label' => $this->l('Spin'),
                            ),
                            array(
                                'id' => 'fade',
                                'value' => 'fade',
                                'label' => $this->l('Fade'),
                            ),
                            array(
                                'id' => 'zoom',
                                'value' => 'zoom',
                                'label' => $this->l('Zoom'),
                            ),
                            array(
                                'id' => 'spin-inverse',
                                'value' => 'spin-inverse',
                                'label' => $this->l('Spin-Inverse'),
                            ),
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Enter height of the button. Ex: 70'),
                        'name' => 'height',
                        'label' => $this->l('Height'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Enter width of the button. Ex: 70'),
                        'name' => 'width',
                        'label' => $this->l('Width'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Distance from the side. Ex: 20'),
                        'name' => 'margin_x',
                        'label' => $this->l('Margin x'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Distance from the top/bottom. Ex: 20'),
                        'name' => 'margin_y',
                        'label' => $this->l('Margin y'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'ms',
                        'desc' => $this->l('Animation Ex: 500. Higher values, higher animation on scroll'),
                        'name' => 'scrollAnimation',
                        'label' => $this->l('Scroll animation'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Theme'),
                        'name' => 'theme',
                        'values' => array(
                            array(
                                'id' => 'fawesome',
                                'value' => 'fawesome',
                                'label' => $this->l('Fawesome'),
                            ),
                            array(
                                'id' => 'default',
                                'value' => 'default',
                                'label' => $this->l('Default'),
                            ),
                        ),
                        'desc' => $this->l('select the theme. fawesome is square type while the other is circular.'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Z-index: The level of the button layer. The more z-index, the more top layer. Ex: 999'),
                        'name' => 'z_index',
                        'label' => $this->l('z-index'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'front_enable' => Configuration::get('front_enable', true),
            'back_enable' => Configuration::get('back_enable', true),
            'background' => Configuration::get('background', true),
            'text' => Configuration::get('text', true),
            'effect' => Configuration::get('effect', true),
            'height' => Configuration::get('height', true),
            'width' => Configuration::get('width', true),
            'margin_x' => Configuration::get('margin_x', true),
            'margin_y' => Configuration::get('margin_y', true),
            'theme' => Configuration::get('theme', true),
            'scrollAnimation' => Configuration::get('scrollAnimation', true),
            'z_index' => Configuration::get('z_index', true),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        foreach (array_keys($form_values) as $key) {
            if ($key == 'background' && Tools::getValue('background') == '') {
                $this->post_errors[] = $this->l('Background color should be set.');
            } elseif ($key == 'text' && Tools::getValue('text') == '') {
                $this->post_errors[] = $this->l('Text color value should be set.');
            } elseif ($key == 'effect' && Tools::getValue('effect') == '') {
                $this->post_errors[] = $this->l('effect type should be selected.');
            } elseif ($key == 'theme' && Tools::getValue('theme') == '') {
                $this->post_errors[] = $this->l('theme should be selected.');
            } elseif ($key == 'scrollAnimation' && Tools::getValue('scrollAnimation') == '') {
                $this->post_errors[] = $this->l('scroll animation time value should be set.');
            } elseif ($key == 'background' && !Validate::isColor(Tools::getValue('background'))) {
                $this->post_errors[] = Tools::getValue('background') . $this->l(' is invalid for the background color. Please enter a valid value.');
            } elseif ($key == 'text' && !Validate::isColor(Tools::getValue('text'))) {
                $this->post_errors[] = Tools::getValue('text') . $this->l(' is invalid for the text color. Please enter a valid value.');
            } elseif ($key == 'scrollAnimation' && (!Validate::isInt(Tools::getValue('scrollAnimation')) || Tools::getValue('scrollAnimation') < 0)) {
                $this->post_errors[] = Tools::getValue('scrollAnimation') . $this->l(' is invalid for the scroll animation time value. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'height' && (!Validate::isInt(Tools::getValue('height')) || Tools::getValue('height') < 0)) {
                $this->post_errors[] = Tools::getValue('height') . $this->l(' is invalid for the height value. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'width' && (!Validate::isInt(Tools::getValue('width')) || Tools::getValue('width') < 0)) {
                $this->post_errors[] = Tools::getValue('width') . $this->l(' is invalid for the width value. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'margin_x' && (!Validate::isInt(Tools::getValue('margin_x')) || Tools::getValue('margin_x') < 0)) {
                $this->post_errors[] = Tools::getValue('margin_x') . $this->l(' is invalid for the x-margin value. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'margin_y' && (!Validate::isInt(Tools::getValue('margin_y')) || Tools::getValue('margin_y') < 0)) {
                $this->post_errors[] = Tools::getValue('margin_y') . $this->l(' is invalid for the y-margin value. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'z_index' && (!Validate::isInt(Tools::getValue('z_index')) || Tools::getValue('z_index') < 0)) {
                $this->post_errors[] = Tools::getValue('z_index') . $this->l(' is invalid for the z-index value. Please enter a valid value greater than 0. No decimals.');
            }
        }

        if (!count($this->post_errors)) {
            $form_values = $this->getConfigFormValues();
            foreach (array_keys($form_values) as $key) {
                Configuration::updateValue($key, Tools::getValue($key));
            }
            $this->html = $this->displayConfirmation($this->l('Settings updated successfully.'));
        } else {
            foreach ($this->post_errors as $err) {
                $this->html .= $this->displayError($err);
            }
        }
    }
    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        Media::addJsDef(array(
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
        ));

        if (_PS_VERSION_ > '1.7.0') {
            // $this->context->controller->addJquery();
            $this->context->controller->addJS(($this->_path) . '/views/js/back/jquery-1.11.0.min.js');
        }

        $this->context->controller->addJS($this->_path . '/views/js/jquery-backToTop.min.js');
        $this->context->controller->addJS($this->_path . '/views/js/back/back.js');
        $this->context->controller->addCSS($this->_path . '/views/css/all.css');
        $this->context->controller->addCSS($this->_path . '/views/css/jquery-backToTop.min.css');
        $this->context->controller->addCSS($this->_path . '/views/css/button_effects.css');
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        Media::addJsDef(array(
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
        ));

        if (_PS_VERSION_ > '1.7.0') {
            $this->context->controller->addJquery();
            $this->context->controller->registerJavascript('modules-backtotopmeg1', 'modules/' . $this->name . '/views/js/jquery-backToTop.min.js', array('position' => 'bottom', 'priority' => 150));
            $this->context->controller->registerJavascript('modules-backtotopmeg2', 'modules/' . $this->name . '/views/js/front/front.js', array('position' => 'bottom', 'priority' => 150));
        } else {
            $this->context->controller->addJS($this->_path . '/views/js/jquery-backToTop.min.js');
            $this->context->controller->addJS($this->_path . '/views/js/front/front.js');
        }

        $this->context->controller->addCSS($this->_path . '/views/css/all.css');
        $this->context->controller->addCSS($this->_path . '/views/css/jquery-backToTop.min.css');
        $this->context->controller->addCSS($this->_path . '/views/css/button_effects.css');
    }

    public function hookDisplayHeader()
    {
        return $this->hookHeader();
    }
}
