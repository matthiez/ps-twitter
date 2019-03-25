<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    André Matthies
 * @copyright 2018-present André Matthies
 * @license   LICENSE
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Eoo_Twitter extends Module
{
    protected $errors = array();

    protected $config = array(
        'EOO_TWITTER_USERNAME' => '',
        'EOO_TWITTER_TWEET_COUNT' => 3,
        'EOO_TWITTER_WIDGET_WIDTH' => '',
        'EOO_TWITTER_WIDGET_HEIGHT' => '',
        'EOO_TWITTER_THEME' => '',
        'EOO_TWITTER_NO_HEADER' => 0,
        'EOO_TWITTER_NO_FOOTER' => 0,
        'EOO_TWITTER_NO_BORDERS' => 0,
        'EOO_TWITTER_NO_SCROLLBAR' => 0,
        'EOO_TWITTER_BG_TRANSPARENCY' => 0,
        'EOO_TWITTER_LINK_COLOR' => '',
        'EOO_TWITTER_BORDER_COLOR' => '',
        'EOO_TWITTER_ASSERTIVE_POLITENESS' => 0,
    );

    public function __construct()
    {
        $this->__moduleDir = dirname(__FILE__);
        $this->author = 'André Matthies';
        $this->bootstrap = true;
        $this->description = $this->l('Adds a block to display tweets in a timeline.');
        $this->displayName = $this->l('Twitter Module');
        $this->name = 'eoo_twitter';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);
        $this->tab = 'front_office_features';
        $this->version = '1.1.4';

        parent::__construct();
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install()) {
            return false;
        }

        foreach ($this->config as $k => $v) {
            Configuration::updateValue($k, $v);
        }

        return $this->registerHook('actionAdminControllerSetMedia')
            && $this->registerHook('actionFrontControllerSetMedia')
            && $this->registerHook('displayFooter');
    }

    public function uninstall()
    {
        parent::uninstall();

        foreach ($this->config as $k) {
            Configuration::deleteByName($k);
        }

        return true;
    }

    public function getContent()
    {
        require_once $this->__moduleDir . '/backendhelperform.php';

        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            foreach (Tools::getValue('config') as $k => $v) {
                Configuration::updateValue($k, $v);
            }

            $output .= $this->errors
                ? $this->displayError(implode($this->errors, '<br>'))
                : $this->displayConfirmation($this->l('Settings updated'));
        }

        return $output . (new BackendHelperForm($this->name))->generate();
    }

    public function hookDisplayFooter()
    {
        $this->context->smarty->assign(Configuration::getMultiple(array_keys($this->config)));

        return $this->display(__FILE__, "$this->name.tpl");
    }

    public function hookDisplayLeftColumn()
    {
        return $this->hookDisplayFooter();
    }

    public function hookDisplayRightColumn()
    {
        return $this->hookDisplayFooter();
    }

    public function hookDisplayTop()
    {
        return $this->hookDisplayFooter();
    }

    public function hookDisplayHome()
    {
        return $this->hookDisplayFooter();
    }

    public function hookActionAdminControllerSetMedia()
    {
        $this->context->controller->addJqueryPlugin('validate');

        $this->context->controller->addJS($this->__moduleDir . '/views/js/backend.js');
    }

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerJavascript(
            'eoo-twitter-widgets',
            "https://platform.twitter.com/widgets.js",
            array(
                'server' => 'remote',
                'position' => 'bottom',
                'priority' => 10,
                'attribute' => 'async defer'
            )
        );
//        $this->context->controller->addJS($this->__moduleDir . '/views/js/frontend.js'); TODO remove frontend.js
    }
}
