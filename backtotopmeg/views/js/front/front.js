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
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

jQuery(document).ready(function ($) {
var _effect = effect;
var _front = front_enable;
var _theme = theme;
// if (theme == true)
//     var _theme = 'fawesome';
// else var _theme = 'default';

var $button = $.backToTop({

    // background color
    backgroundColor: background,

    // text color
    color: text,

    // container element
    container: this._body, 

    // 'none', 'spin', 'fade', 'zoom', or 'spin-inverse'
    effect: _effect,

    // enable the back to top button
    enabled: _front, 

    // width/height of the back to top button
    height: height,  
    width: width,

    // icon
    icon: 'fas fa-chevron-up',

    // margins 
    marginX: margin_x,
    marginY: margin_y, 
    bottom: margin_y,
    top: margin_y,
    left: margin_x,
    right: margin_x,

    // bottom/top left/right
    position: 'bottom right',           

    // trigger position
    pxToTrigger: 600,
    
    // scroll animation
    scrollAnimation: scrollAnimation, 

    // or 'fawesome'
    theme: _theme,

    // z-index
    zIndex: z_index
});
});