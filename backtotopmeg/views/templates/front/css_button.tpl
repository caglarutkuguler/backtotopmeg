{*
* 2007-2024 PrestaShop
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
*  @copyright 2007-2024 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if (($front_enable_stb == true) || ($back_enable_stb == true))}
  <a href="#" class="float" id="scrollToBottom">
    <i class="fas fa-chevron-down"></i>
  </a>
{/if}
{if (($front_enable == true) || ($back_enable == true)) && ($button_code == false)}
  <a href="#" class="float" id="backToTop">
    <i class="material-icons my-float-to-top">keyboard_arrow_up</i>
  </a>
{/if}
{if ($effect == 'spin')}
  <style>
    #backToTop {
      opacity: 0;
      visibility: hidden;
      cursor: pointer;
      transition: opacity 0.5s ease-out;
    }

    #backToTop.show {
      opacity: 1;
      visibility: visible;
      transition: opacity 0.5s ease-in;
      animation: spin 0.5s linear;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>
{/if}
{if ($effect == 'spin-inverse')}
  <style>
    #backToTop {
      opacity: 0;
      visibility: hidden;
      cursor: pointer;
      transition: opacity 0.5s ease-out;
    }

    #backToTop.show {
      opacity: 1;
      visibility: visible;
      transition: opacity 0.5s ease-in;
      animation: spin-reverse 0.5s linear;
    }

    @keyframes spin-reverse {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(-360deg);
      }
    }
  </style>
{/if}
{if ($effect == 'zoom')}
  <style>
    #backToTop {
      opacity: 0;
      visibility: hidden;
      cursor: pointer;
      transition: opacity 0.5s ease-out;
    }

    #backToTop.show {
      opacity: 1;
      visibility: visible;
      transition: all 0.5s ease-in-out;
      transform: scale(1);
    }

    #backToTop.hide {
      opacity: 0;
      visibility: hidden;
      transition: all 0.5s ease-in-out;
      transform: scale(0.5);
    }
  </style>
{/if}