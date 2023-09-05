/**
* 2007-2023 PrestaShop
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
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
$(document).ready(function(){
    if (theme == 'fawesome')
        radius = '25%';
    else
        radius = '50%';
    $('#backToTop').css({
        'color':text,
        'background-color':background,
        'height': height,
        'width': width,
        'z-index': z_index,
        '-webkit-border-radius': radius,
        'border-radius': radius
    });
    $('#backToTop').css({
        'right': margin_x+'px',
        'bottom': margin_y+'px',
    });
    window.addEventListener('scroll', () => {
        if (effect == 'fade') {
            if (window.pageYOffset > 0) {
                $('#backToTop').fadeIn(500);
            } else {
                $('#backToTop').fadeOut(500);
            }
        }
        if ((effect == 'spin') || (effect == 'spin-inverse')) {
            var element = document.getElementById('backToTop');
            if (window.pageYOffset > 0) {
              element.classList.add('show');
            } else {
              element.classList.remove('show');
            }
        }
        if (effect == 'zoom') {
            var element = document.getElementById('backToTop');
            if (window.pageYOffset > 0) {
              element.classList.add('show');
              element.classList.remove('hide');
            } else {
              element.classList.add('hide');
              element.classList.remove('show');
            }
        }
    });
});

  $(function () {
    $('#backToTop').bind("click", function () {
        $('html, body').animate({ scrollTop: 0 }, scrollAnimation);
        return false;
    });
});