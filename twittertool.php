<?php if (!defined('_PS_VERSION_')) exit;

/**
 * Class TwitterTool
 */
class TwitterTool extends Module
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var array
     */
    protected $config = [
        'TWITTERTOOL_USERNAME' => '',
        'TWITTERTOOL_TWEET_COUNT' => 3,
        'TWITTERTOOL_WIDGET_WIDTH' => '',
        'TWITTERTOOL_WIDGET_HEIGHT' => '',
        'TWITTERTOOL_THEME' => '',
        'TWITTERTOOL_NO_HEADER' => 0,
        'TWITTERTOOL_NO_FOOTER' => 0,
        'TWITTERTOOL_NO_BORDERS' => 0,
        'TWITTERTOOL_NO_SCROLLBAR' => 0,
        'TWITTERTOOL_BG_TRANSPARENCY' => 0,
        'TWITTERTOOL_LINK_COLOR' => '',
        'TWITTERTOOL_BORDER_COLOR' => '',
        'TWITTERTOOL_ASSERTIVE_POLITENESS' => 0,
    ];

    /**
     * TwitterTool constructor.
     */
    public function __construct() {
        $this->name = 'twittertool';
        $this->tab = 'front_office_features';
        $this->version = '1.1.2';
        $this->author = 'Andre Matthies';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [ 'min' => '1.5', 'max' => _PS_VERSION_ ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TwitterTool');
        $this->description = $this->l('Adds a block to display tweets in a timeline.');

        $this->__moduleDir = dirname(__FILE__);
    }

    /**
     * @return bool
     */
    public function install() {
        if (Shop::isFeatureActive()) Shop::setContext(Shop::CONTEXT_ALL);
        return parent::install()
        && $this->installConfig()
        && $this->registerHook('actionAdminControllerSetMedia')
        && $this->registerHook('actionFrontControllerSetMedia')
        && $this->registerHook('displayFooter');
    }

    /**
     * @return bool
     */
    public function uninstall() {
        return parent::uninstall() && $this->removeConfig();
    }

    /**
     * @return bool
     */
    private function installConfig() {
        foreach ($this->config as $k => $v) Configuration::updateValue(strtoupper($k), $v);
        return true;
    }

    /**
     * @return bool
     */
    private function removeConfig() {
        foreach ($this->config as $k => $v) Configuration::deleteByName($k);
        return true;
    }

    /**
     * @return mixed
     */
    public function getConfig() {
        return Configuration::getMultiple(array_keys($this->config));
    }

    /**
     * @return string
     */
    public function getContent() {
        require_once $this->__moduleDir . '/backendhelperform.php';
        $output = null;
        if (Tools::isSubmit('submit' . $this->name)) {
            foreach (Tools::getValue('config') as $k => $v) Configuration::updateValue($k, $v);
            if ($this->errors) $output .= $this->displayError(implode($this->errors, '<br/>'));
            else $output .= $this->displayConfirmation($this->l('Settings updated'));
        }
        return $output . (new BackendHelperForm($this->name))->generate();
    }

    /**
     * @return mixed
     */
    public function hookDisplayFooter() {
        $this->context->smarty->assign($this->getConfig());
        return $this->display(__FILE__, 'twittertool.tpl');
    }

    /**
     * @return mixed
     */
    public function hookDisplayLeftColumn() {
        return $this->hookDisplayFooter();
    }

    /**
     * @return mixed
     */
    public function hookDisplayRightColumn() {
        return $this->hookDisplayFooter();
    }

    /**
     * @return mixed
     */
    public function hookDisplayTop() {
        return $this->hookDisplayFooter();
    }

    /**
     * @return mixed
     */
    public function hookDisplayHome() {
        return $this->hookDisplayFooter();
    }

    /**
     *
     */
    public function hookActionAdminControllerSetMedia() {
        $this->context->controller->addJqueryPlugin('validate');
        $this->context->controller->addJS($this->__moduleDir . '/views/js/backend.js');
    }

    /**
     *
     */
    public function hookActionFrontControllerSetMedia() {
        $this->context->controller->addJS($this->__moduleDir . '/views/js/frontend.js');
    }
}