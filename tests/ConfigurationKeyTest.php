<?php
/**
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2007-2026 MEG Venture & Consulting Ltd.
 * @license   https://opensource.org/licenses/MIT MIT License
 *
 * Guards the 2.1.0 configuration-key change.
 *
 * Runs without PrestaShop: Configuration, Tools and Module are stubbed, so the
 * whole file is `php tests/ConfigurationKeyTest.php` and nothing else.
 *
 * What it is here to catch: this module used to read and write bare
 * configuration names - 'background', 'theme', 'width' - which another module
 * could own. Reading someone else's value was the visible half of that bug;
 * uninstalling and deleting someone else's row was the worse half.
 */
define('_PS_VERSION_', '8.1.0');

class Configuration
{
    public static $store = [];
    public static function get($k, $l = null, $s = null, $sh = null, $default = false)
    {
        return array_key_exists($k, self::$store) ? self::$store[$k] : false;
    }
    public static function updateValue($k, $v)
    {
        self::$store[$k] = is_bool($v) ? ($v ? '1' : '0') : (string) $v;
        return true;
    }
    public static function updateGlobalValue($k, $v)
    {
        return self::updateValue($k, $v);
    }
    public static function deleteByName($k)
    {
        unset(self::$store[$k]);
        return true;
    }
}

class Tools
{
    public static function strtoupper($s) { return strtoupper($s); }
    public static function getValue($k, $d = false) { return $d; }
    public static function isSubmit($k) { return false; }
}

class Validate { public static function isColor($v) { return true; } public static function isInt($v) { return true; } }
class Media { public static function addJsDef($a) {} }

abstract class Module
{
    public $name, $tab, $version, $author, $need_instance, $module_key,
           $ps_versions_compliancy, $bootstrap, $displayName, $description, $_path, $context;
    public function __construct() {}
    public function l($s, $c = null) { return $s; }
    public function registerHook($h) { return true; }
    public function unregisterHook($h) { return true; }
    public function display($f, $t) { return ''; }
    public function displayConfirmation($s) { return ''; }
    public function displayError($s) { return ''; }
    public function install() { return true; }
    public function uninstall() { return true; }
}

require dirname(__DIR__) . '/backtotopmeg.php';

$fail = 0;
function ok($cond, $label) {
    global $fail;
    if ($cond) { echo "  ok   $label\n"; } else { echo "  FAIL $label\n"; $fail++; }
}

echo "1) Prefix ve anahtar esleme\n";
$m = new Backtotopmeg();
ok(Backtotopmeg::CONF_PREFIX === 'BTTM_', 'CONF_PREFIX = BTTM_');
$r = new ReflectionMethod('Backtotopmeg', 'cfgKey');
$r->setAccessible(true);
ok($r->invoke(null, 'background') === 'BTTM_BACKGROUND', "background -> BTTM_BACKGROUND");
ok($r->invoke(null, 'margin_x') === 'BTTM_MARGIN_X', "margin_x -> BTTM_MARGIN_X");
ok($r->invoke(null, 'scrollAnimation_stb') === 'BTTM_SCROLLANIMATION_STB', "scrollAnimation_stb -> BTTM_SCROLLANIMATION_STB");

echo "\n2) Kurulum sadece onekli anahtar yaziyor mu\n";
Configuration::$store = ['theme' => 'baska-modulun-degeri', 'background' => '#AABBCC'];
$m->install();
$bare = array_filter(array_keys(Configuration::$store), function ($k) { return strpos($k, 'BTTM_') !== 0; });
ok($bare === array_keys(['theme' => 1, 'background' => 1]) || (in_array('theme', $bare) && in_array('background', $bare) && count($bare) === 2),
   'kurulum onneksiz hicbir anahtara dokunmadi (' . implode(', ', $bare) . ')');
ok(Configuration::$store['theme'] === 'baska-modulun-degeri', "baska modulun 'theme' degeri bozulmadi");
ok(Configuration::$store['background'] === '#AABBCC', "baska modulun 'background' degeri bozulmadi");
ok(Configuration::$store['BTTM_BACKGROUND'] === '#5D5D5D', 'BTTM_BACKGROUND varsayilani yazildi');
ok(count(array_filter(array_keys(Configuration::$store), function ($k) { return strpos($k, 'BTTM_') === 0; })) === 24,
   '24 onekli ayar yazildi (23 ayar + review nudge zaman damgasi)');

echo "\n3) Kaldirma baska modulun anahtarina dokunuyor mu\n";
$m->uninstall();
ok(Configuration::$store['theme'] === 'baska-modulun-degeri', "uninstall baska modulun 'theme' satirini silmedi");
ok(Configuration::$store['background'] === '#AABBCC', "uninstall baska modulun 'background' satirini silmedi");
ok(count(array_filter(array_keys(Configuration::$store), function ($k) { return strpos($k, 'BTTM_') === 0; })) === 0,
   'uninstall butun BTTM_ anahtarlarini sildi');

echo "\n4) 2.0.0 -> 2.1.0 gecisi (gercek yol: install calismaz, sadece upgrade betigi)\n";
require dirname(__DIR__) . '/upgrade/upgrade-2.1.0.php';
Configuration::$store = [
    'background' => '#112233', 'text' => '#FFFFFF', 'theme' => 'default',
    'width' => '55', 'margin_x' => '12', 'scrollAnimation' => '250',
    'background_stb' => '#445566', 'z_index_stb' => '1200',
];
upgrade_module_2_1_0($m);
ok(Configuration::$store['BTTM_WIDTH'] === '55', 'eski width degeri tasindi (55)');
ok(Configuration::$store['BTTM_MARGIN_X'] === '12', 'eski margin_x degeri tasindi (12)');
ok(Configuration::$store['BTTM_SCROLLANIMATION'] === '250', 'eski scrollAnimation degeri tasindi (250)');
ok(Configuration::$store['BTTM_BACKGROUND_STB'] === '#445566', 'eski background_stb degeri tasindi');
ok(Configuration::$store['BTTM_Z_INDEX_STB'] === '1200', 'eski z_index_stb degeri tasindi');
ok(Configuration::$store['BTTM_THEME'] === 'default', 'eski theme degeri tasindi');
ok(!isset(Configuration::$store['BTTM_EFFECT']), 'hic yazilmamis ayar icin bos satir uretilmedi');
ok(Configuration::$store['background'] === '#112233',
   'eski onneksiz satir SILINMEDI (baska modulun olabilir)');

echo "\n5) Upgrade ikinci kez calisirsa merchant ayarini ezmiyor mu\n";
Configuration::$store['BTTM_WIDTH'] = '90';   // merchant 2.1.0 sonrasi degistirdi
upgrade_module_2_1_0($m);
ok(Configuration::$store['BTTM_WIDTH'] === '90', 'yeni deger korundu, eski 55 geri gelmedi');

echo "\n6) Kurulum sonrasi okuma/yazma dogru anahtardan gidiyor mu\n";
Configuration::$store = [];
$m->install();
$m2 = new Backtotopmeg();
$rg = new ReflectionMethod('Backtotopmeg', 'getConfigFormValues');
$rg->setAccessible(true);
$vals = $rg->invoke($m2);
ok($vals['background'] === '#5D5D5D', 'form BTTM_BACKGROUND degerini okuyor');
ok($vals['theme'] === 'fawesome', 'form BTTM_THEME degerini okuyor');
Configuration::$store['background'] = '#000000';  // baska modul yaziyor
$vals = $rg->invoke($m2);
ok($vals['background'] === '#5D5D5D', 'baska modulun onneksiz degeri artik okunmuyor');

echo "\n" . ($fail === 0 ? "OK - hepsi gecti\n" : "$fail test BASARISIZ\n");
exit($fail === 0 ? 0 : 1);
