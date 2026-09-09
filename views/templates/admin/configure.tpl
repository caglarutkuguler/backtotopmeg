{*
* Smart Scroll Buttons - MEG Venture
* Admin settings intro + live preview panel.
*}
{* PS 1.5/1.6 back offices carry FontAwesome but no Material Icons; 1.7 and up carry
   Material, and 9 dropped FontAwesome. mv_ms picks the set that is actually there. *}
{assign var='mv_psv' value=$smarty.const._PS_VERSION_}
{assign var='mv_ms' value=true}
{if $mv_psv[0] == '1' && $mv_psv[1] == '.' && ($mv_psv[2] == '5' || $mv_psv[2] == '6')}{assign var='mv_ms' value=false}{/if}
<style>
{literal}
/* Back-office icons. These used to be FontAwesome 4, which the back office shipped up to
   PrestaShop 8; PrestaShop 9 replaced it with Material Symbols Outlined. An icon-font class
   selects a private-use code point, so the moment the font is not there the browser has
   nothing to fall back to and draws an empty box. These now come from the set the core loads
   for its own interface, which is ligature-based: the icon name is the element's text.
   Sized down from the 24px default and set back to inheriting the text colour, so they sit
   where the FontAwesome ones did. */
.material-icons.mv-ico{font-size:18px;line-height:1;vertical-align:middle;margin-right:4px;}
.material-icons.mv-ico,.material-icons.mv-ico:hover{color:inherit;}
h3 .material-icons.mv-ico,
.panel-heading .material-icons.mv-ico{font-size:20px;}
.btn .material-icons.mv-ico{font-size:16px;margin-right:3px;}
{/literal}
</style>
<div class="panel">
    <h3>{if $mv_ms}<i class="material-icons mv-ico">arrow_upward</i>{else}<i class="icon icon-arrow-up"></i>{/if} {l s='Smart Scroll Buttons' mod='backtotopmeg'}</h3>
    <p>
        <strong>{l s='Add a Back to Top button and a Scroll to Bottom button to your shop.' mod='backtotopmeg'}</strong><br />
        {l s='Back to Top appears once a visitor has scrolled down the page and, when clicked, smoothly scrolls back up.' mod='backtotopmeg'}<br />
        {l s='Scroll to Bottom appears while there is still more page below and, when clicked, jumps straight to the bottom.' mod='backtotopmeg'}
    </p>
    <p>{l s='Both buttons are independent: enable either one, both, or none, and customize their color, size, shape and position below.' mod='backtotopmeg'}</p>
</div>

<div class="panel backtotopmeg-preview-panel">
    <h3>{if $mv_ms}<i class="material-icons mv-ico">visibility</i>{else}<i class="icon icon-eye"></i>{/if} {l s='Live preview' mod='backtotopmeg'}</h3>
    <p>{l s='This preview updates automatically as you change the settings below, before you save.' mod='backtotopmeg'}</p>
    <div class="backtotopmeg-preview-stage">
        <span class="backtotopmeg-preview-stage-label">{l s='Your shop page' mod='backtotopmeg'}</span>
        <div id="backtotopmeg-preview-bottom" class="backtotopmeg-preview-btn backtotopmeg-preview-btn--top"
             style="background-color:{$preview.background_stb|escape:'html':'UTF-8'};color:{$preview.text_stb|escape:'html':'UTF-8'};height:{$preview.height_stb|intval}px;width:{$preview.height_stb|intval}px;line-height:{$preview.height_stb|intval}px;border-radius:{if $preview.theme_stb == 'default'}50%{else}25%{/if};">
            <i class="fas fa-chevron-down"></i>
        </div>
        <div id="backtotopmeg-preview-top" class="backtotopmeg-preview-btn backtotopmeg-preview-btn--bottom"
             style="background-color:{$preview.background|escape:'html':'UTF-8'};color:{$preview.text|escape:'html':'UTF-8'};height:{$preview.height|intval}px;width:{$preview.height|intval}px;line-height:{$preview.height|intval}px;border-radius:{if $preview.theme == 'default'}50%{else}25%{/if};">
            <i class="fas fa-chevron-up"></i>
        </div>
    </div>
</div>

<style>
    .backtotopmeg-preview-stage {
        position: relative;
        height: 200px;
        border: 1px dashed #ccc;
        border-radius: 4px;
        background: #fafafa;
        overflow: hidden;
    }
    .backtotopmeg-preview-stage-label {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #aaa;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .backtotopmeg-preview-btn {
        position: absolute;
        right: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 2px 2px 3px #999;
        transition: background-color 0.2s ease, color 0.2s ease, height 0.2s ease, width 0.2s ease, border-radius 0.2s ease;
    }
    .backtotopmeg-preview-btn--top {
        top: 16px;
    }
    .backtotopmeg-preview-btn--bottom {
        bottom: 16px;
    }
</style>
