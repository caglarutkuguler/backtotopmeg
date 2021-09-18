<?php
/**
 * 2007-2021 PrestaShop
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
 *  @copyright 2007-2021 PrestaShop SA
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
    private $html2 = '';
    private $post_errors = array();

    public function __construct()
    {
        $this->name = 'backtotopmeg';
        $this->tab = 'administration';
        $this->version = '1.1.0';
        $this->author = 'MEG Venture';
        $this->need_instance = 0;
        $this->module_key = '94f25f128f4703813f076d5e25ca4ac0';

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Back to Top and Scroll to Bottom Buttons');
        $this->description = $this->l('Add a customizable Back to Top and Scroll to Bottom Buttons to your pages.');

        if (version_compare(_PS_VERSION_, '1.5.0.0 ', '<')) {
            require _PS_MODULE_DIR_ . $this->name . '/backward_compatibility/backward.php';
        }
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
        $this->html = '';
        $this->html2 = '';
        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');
        if (((bool) Tools::isSubmit('submitBacktotopmegModule')) == true) {
            $this->postProcess();
            return $this->html . $output . $this->renderForm() . $this->renderForm_stb();
        } elseif (((bool) Tools::isSubmit('submitBacktotopmegModule_stb')) == true) {
            $this->postProcess2();
            return $this->html2 . $output . $this->renderForm() . $this->renderForm_stb();
        } else {
            return $output . $this->renderForm() . $this->renderForm_stb();
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

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
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

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues_stb(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm_stb()));
    }

    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings for the Back to Top Button'),
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
                        'desc' => $this->l('enable the Back to Top Button on the front office'),
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
                        'desc' => $this->l('enable the Back to Top Button on the back office'),
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

    protected function getConfigForm_stb()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings for the Scroll to Bottom Button'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable front office'),
                        'name' => 'front_enable_stb',
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
                        'desc' => $this->l('enable the Scroll to Bottom Button on the front office'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable back office'),
                        'name' => 'back_enable_stb',
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
                        'desc' => $this->l('enable the Scroll to Bottom Button on the back office'),
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Background color of the button'),
                        'name' => 'background_stb',
                        'class' => 'traditionb',
                        'size' => 20,
                        'hint' => $this->l('Background color of the button Ex: #5D5D5D'),
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Arrow (internal) color of the button'),
                        'name' => 'text_stb',
                        'class' => 'traditionb',
                        'size' => 20,
                        'hint' => $this->l('Arrow (internal) color of the button Ex: #FFFFFF'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Enter height of the button. Ex: 70'),
                        'name' => 'height_stb',
                        'label' => $this->l('Height'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Enter width of the button. Ex: 70'),
                        'name' => 'width_stb',
                        'label' => $this->l('Width'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Distance from the side. Ex: 20'),
                        'name' => 'margin_x_stb',
                        'label' => $this->l('Margin x'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'px',
                        'desc' => $this->l('Distance from the top/bottom. Ex: 20'),
                        'name' => 'margin_y_stb',
                        'label' => $this->l('Margin y'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'suffix' => 'ms',
                        'desc' => $this->l('Animation Ex: 500. Higher values, higher animation on scroll'),
                        'name' => 'scrollAnimation_stb',
                        'label' => $this->l('Scroll animation'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Theme'),
                        'name' => 'theme_stb',
                        'values' => array(
                            array(
                                'id' => 'fawesome',
                                'value' => 'fawesome',
                                'label' => $this->l('Square rounded border'),
                            ),
                            array(
                                'id' => 'default',
                                'value' => 'default',
                                'label' => $this->l('Circular'),
                            ),
                        ),
                        'desc' => $this->l('select the theme.'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Z-index: The level of the button layer. The more z-index, the more top layer. Ex: 999'),
                        'name' => 'z_index_stb',
                        'label' => $this->l('z-index'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

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

    protected function getConfigFormValues_stb()
    {
        return array(
            'front_enable_stb' => Configuration::get('front_enable_stb', true),
            'back_enable_stb' => Configuration::get('back_enable_stb', true),
            'background_stb' => Configuration::get('background_stb', true),
            'text_stb' => Configuration::get('text_stb', true),
            'height_stb' => Configuration::get('height_stb', true),
            'width_stb' => Configuration::get('width_stb', true),
            'margin_x_stb' => Configuration::get('margin_x_stb', true),
            'margin_y_stb' => Configuration::get('margin_y_stb', true),
            'theme_stb' => Configuration::get('theme_stb', true),
            'scrollAnimation_stb' => Configuration::get('scrollAnimation_stb', true),
            'z_index_stb' => Configuration::get('z_index_stb', true),
        );
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        foreach (array_keys($form_values) as $key) {
            if ($key == 'background' && Tools::getValue('background') == '') {
                $this->post_errors[] = $this->l('Back to Top button background color should be set.');
            } elseif ($key == 'text' && Tools::getValue('text') == '') {
                $this->post_errors[] = $this->l('Back to Top button text color value should be set.');
            } elseif ($key == 'effect' && Tools::getValue('effect') == '') {
                $this->post_errors[] = $this->l('Back to Top button effect type should be selected.');
            } elseif ($key == 'theme' && Tools::getValue('theme') == '') {
                $this->post_errors[] = $this->l('Back to Top button theme should be selected.');
            } elseif ($key == 'scrollAnimation' && Tools::getValue('scrollAnimation') == '') {
                $this->post_errors[] = $this->l('Back to Top button scroll animation time value should be set.');
            } elseif ($key == 'background' && !Validate::isColor(Tools::getValue('background'))) {
                $this->post_errors[] = Tools::getValue('background') . $this->l(' is invalid for the Back to Top button background color. Please enter a valid value.');
            } elseif ($key == 'text' && !Validate::isColor(Tools::getValue('text'))) {
                $this->post_errors[] = Tools::getValue('text') . $this->l(' is invalid for the Back to Top button text color. Please enter a valid value.');
            } elseif ($key == 'scrollAnimation' && (!Validate::isInt(Tools::getValue('scrollAnimation')) || Tools::getValue('scrollAnimation') < 0)) {
                $this->post_errors[] = Tools::getValue('scrollAnimation') . $this->l(' is invalid for the Back to Top button scroll animation time value. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'height' && (!Validate::isInt(Tools::getValue('height')) || Tools::getValue('height') < 0)) {
                $this->post_errors[] = Tools::getValue('height') . $this->l(' is invalid for the Back to Top button height value. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'width' && (!Validate::isInt(Tools::getValue('width')) || Tools::getValue('width') < 0)) {
                $this->post_errors[] = Tools::getValue('width') . $this->l(' is invalid for the Back to Top button  width value. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'margin_x' && (!Validate::isInt(Tools::getValue('margin_x')) || Tools::getValue('margin_x') < 0)) {
                $this->post_errors[] = Tools::getValue('margin_x') . $this->l(' is invalid for the Back to Top button  x-margin value. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'margin_y' && (!Validate::isInt(Tools::getValue('margin_y')) || Tools::getValue('margin_y') < 0)) {
                $this->post_errors[] = Tools::getValue('margin_y') . $this->l(' is invalid for the Back to Top button  y-margin value. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'z_index' && (!Validate::isInt(Tools::getValue('z_index')) || Tools::getValue('z_index') < 0)) {
                $this->post_errors[] = Tools::getValue('z_index') . $this->l(' is invalid for the Back to Top button  z-index value. Please enter a valid value greater than 0. No decimals.');
            }
        }

        if (!count($this->post_errors)) {
            $form_values = $this->getConfigFormValues();
            foreach (array_keys($form_values) as $key) {
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
        $form_values_stb = $this->getConfigFormValues_stb();
        foreach (array_keys($form_values_stb) as $key) {
            if ($key == 'background_stb' && Tools::getValue('background_stb') == '') {
                $this->post_errors[] = $this->l('Scroll to bottom button background color should be set.');
            } elseif ($key == 'text_stb' && Tools::getValue('text_stb') == '') {
                $this->post_errors[] = $this->l('Scroll to bottom button text color value should be set.');
            } elseif ($key == 'theme_stb' && Tools::getValue('theme_stb') == '') {
                $this->post_errors[] = $this->l('Scroll to bottom button theme should be selected.');
            } elseif ($key == 'scrollAnimation_stb' && Tools::getValue('scrollAnimation_stb') == '') {
                $this->post_errors[] = $this->l('Scroll to bottom button scroll animation time value should be set.');
            } elseif ($key == 'background_stb' && !Validate::isColor(Tools::getValue('background_stb'))) {
                $this->post_errors[] = Tools::getValue('background_stb') . $this->l(' is invalid for the scroll to bottom button background color for the scroll to bottom button. Please enter a valid value.');
            } elseif ($key == 'text_stb' && !Validate::isColor(Tools::getValue('text_stb'))) {
                $this->post_errors[] = Tools::getValue('text_stb') . $this->l(' is invalid for the scroll to bottom text color for the scroll to bottom button . Please enter a valid value.');
            } elseif ($key == 'scrollAnimation_stb' && (!Validate::isInt(Tools::getValue('scrollAnimation_stb')) || Tools::getValue('scrollAnimation_stb') < 0)) {
                $this->post_errors[] = Tools::getValue('scrollAnimation_stb') . $this->l(' is invalid for the scroll to bottom scroll animation time value for the scroll to bottom button. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'height_stb' && (!Validate::isInt(Tools::getValue('height_stb')) || Tools::getValue('height_stb') < 0)) {
                $this->post_errors[] = Tools::getValue('height_stb') . $this->l(' is invalid for the scroll to bottom height value for the scroll to bottom button. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'width_stb' && (!Validate::isInt(Tools::getValue('width_stb')) || Tools::getValue('width_stb') < 0)) {
                $this->post_errors[] = Tools::getValue('width_stb') . $this->l(' is invalid for the scroll to bottom width value for the scroll to bottom button. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'margin_x_stb' && (!Validate::isInt(Tools::getValue('margin_x_stb')) || Tools::getValue('margin_x_stb') < 0)) {
                $this->post_errors[] = Tools::getValue('margin_x_stb') . $this->l(' is invalid for the scroll to bottom x-margin value for the scroll to bottom button. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'margin_y_stb' && (!Validate::isInt(Tools::getValue('margin_y_stb')) || Tools::getValue('margin_y_stb') < 0)) {
                $this->post_errors[] = Tools::getValue('margin_y_stb') . $this->l(' is invalid for the scroll to bottom y-margin value for the scroll to bottom button. Please enter a valid value greater than 0. No decimals.');
            } elseif ($key == 'z_index_stb' && (!Validate::isInt(Tools::getValue('z_index_stb')) || Tools::getValue('z_index_stb') < 0)) {
                $this->post_errors[] = Tools::getValue('z_index_stb') . $this->l(' is invalid for the scroll to bottom z-index value for the scroll to bottom button. Please enter a valid value greater than 0. No decimals.');
            }
        }

        if (!count($this->post_errors)) {
            $form_values_stb = $this->getConfigFormValues_stb();
            foreach (array_keys($form_values_stb) as $key) {
                Configuration::updateValue($key, Tools::getValue($key));
            }
            $this->html2 .= $this->displayConfirmation($this->l('Settings updated successfully.'));
        } else {
            foreach ($this->post_errors as $err) {
                $this->html2 .= $this->displayError($err);
            }
        }
    }

    public function hookBackOfficeHeader()
    {
        if ((Configuration::get('back_enable') == true) ||
            (Configuration::get('back_enable_stb') == true)) {
            if ((_PS_VERSION_ > '1.7.0') && (_PS_VERSION_ < '1.7.7')) {
                $this->context->controller->addJS(($this->_path) . '/views/js/back/jquery-1.11.0.min.js');
            }
            $this->context->controller->addCSS($this->_path . '/views/css/all.css');
        }

        if (Configuration::get('back_enable') == true) {
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

            $this->context->controller->addJS($this->_path . '/views/js/jquery-backToTop.min.js');
            $this->context->controller->addJS($this->_path . '/views/js/back/back.js');
            $this->context->controller->addCSS($this->_path . '/views/css/jquery-backToTop.min.css');
            $this->context->controller->addCSS($this->_path . '/views/css/button_effects.css');
        }

        if (Configuration::get('back_enable_stb') == true) {
            Media::addJsDef(array(
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
            ));

            $this->context->controller->addJS($this->_path . '/views/js/back/back_stb.js');
            $this->context->controller->addCSS($this->_path . '/views/css/scrollToBottom.css');
            $this->context->controller->addCSS($this->_path . '/views/css/button_effects_stb.css');
            return $this->display(__FILE__, 'views/templates/admin/scrollToBottom.tpl');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        if ((Configuration::get('front_enable') == true) ||
            (Configuration::get('front_enable_stb') == true)) {
            if ((_PS_VERSION_ > '1.7.0') && (_PS_VERSION_ < '1.7.7')) {
                $this->context->controller->addJS(($this->_path) . '/views/js/jquery-1.11.0.min.js');
            }
            $this->context->controller->addCSS($this->_path . '/views/css/all.css');
        }

        if (Configuration::get('front_enable') == true) {
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
                $this->context->controller->registerJavascript('modules-backtotopmeg1', 'modules/' . $this->name . '/views/js/jquery-backToTop.min.js', array('position' => 'bottom', 'priority' => 150));
                $this->context->controller->registerJavascript('modules-backtotopmeg2', 'modules/' . $this->name . '/views/js/front/front.js', array('position' => 'bottom', 'priority' => 150));
            } else {
                $this->context->controller->addJS($this->_path . '/views/js/jquery-backToTop.min.js');
                $this->context->controller->addJS($this->_path . '/views/js/front/front.js');
            }

            $this->context->controller->addCSS($this->_path . '/views/css/jquery-backToTop.min.css');
            $this->context->controller->addCSS($this->_path . '/views/css/button_effects.css');
        }

        if (Configuration::get('front_enable_stb') == true) {
            Media::addJsDef(array(
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
            ));

            $this->context->controller->addJS($this->_path . '/views/js/front/front_stb.js');
            $this->context->controller->addCSS($this->_path . '/views/css/scrollToBottom.css');
            $this->context->controller->addCSS($this->_path . '/views/css/button_effects_stb.css');
            return $this->display(__FILE__, 'views/templates/front/scrollToBottom.tpl');
        }
    }

    public function hookDisplayHeader()
    {
        return $this->hookHeader();
    }
}
