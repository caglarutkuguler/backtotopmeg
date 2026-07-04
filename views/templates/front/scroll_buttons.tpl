{*
* 2007-2026 MEG Venture
*
* Smart Scroll Buttons module - Back to Top / Scroll to Bottom markup.
* Shared by the front office and back office hooks.
*}
{if $show_top}
<a href="#" class="ps-scrollbtn" id="backToTop" aria-label="{l s='Back to top' mod='backtotopmeg'}">
    <i class="fas fa-chevron-up"></i>
</a>
{/if}
{if $show_bottom}
<a href="#" class="ps-scrollbtn" id="scrollToBottom" aria-label="{l s='Scroll to bottom' mod='backtotopmeg'}">
    <i class="fas fa-chevron-down"></i>
</a>
{/if}
