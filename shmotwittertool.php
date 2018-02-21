<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class shmoTwitterTool extends Module
{

    protected $errors = [];

    protected $config = [
        'SHMO_TWITTERTOOL_USERNAME' => '',
        'SHMO_TWITTERTOOL_WIDGETID' => '',
        'SHMO_TWITTERTOOL_TWEET_COUNT' => 3,
        'SHMO_TWITTERTOOL_WIDGET_WIDTH' => '',
        'SHMO_TWITTERTOOL_WIDGET_HEIGHT' => '',
        'SHMO_TWITTERTOOL_THEME' => '',
        'SHMO_TWITTERTOOL_NO_HEADER' => 0,
        'SHMO_TWITTERTOOL_NO_FOOTER' => 0,
        'SHMO_TWITTERTOOL_NO_BORDERS' => 0,
        'SHMO_TWITTERTOOL_NO_SCROLLBAR' => 0,
        'SHMO_TWITTERTOOL_BG_TRANSPARENCY' => 0,
        'SHMO_TWITTERTOOL_LINK_COLOR' => '',
        'SHMO_TWITTERTOOL_BORDER_COLOR' => '',
        'SHMO_TWITTERTOOL_ASSERTIVE_POLITENESS' => 0,
    ];

    public function __construct() {
        parent::__construct();
        $this->name = 'shmotwittertool';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Andre Matthies';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->displayName = $this->l('TwitterTool');
        $this->description = $this->l('Adds a block to display tweets in a timeline.');
        $this->confirmUninstall = $this->l('Are you sure you want to delete TwitterTool?');
    }

    public function install() {
        if (Shop::isFeatureActive()) Shop::setContext(Shop::CONTEXT_ALL);
        if (!parent::install()
            OR !$this->installConfig()
            OR !$this->registerHook('displayHeader')
            OR !$this->registerHook('displayTop')
            OR !$this->registerHook('displayHome')
            OR !$this->registerHook('displayLeftColumn')
            OR !$this->registerHook('displayRightColumn')
            OR !$this->registerHook('displayFooter')
            OR !$this->registerHook('backOfficeHeader')) {
            return false;
        }
        return true;
    }

    public function uninstall() {
        return !parent::uninstall() OR !$this->removeConfig() ? false : true;
    }

    private function installConfig() {
        foreach ($this->config as $keyname => $value) Configuration::updateValue(strtoupper($keyname), $value);
        return true;
    }

    private function removeConfig() {
        foreach ($this->config as $keyname => $value) Configuration::deleteByName(strtoupper($keyname));
        return true;
    }

    public function getConfig() {
        return Configuration::getMultiple(array_keys($this->config));
    }

    public function getContent() {
        $output = null;
        if (Tools::isSubmit('submitshmotwittertool')) {
            foreach (Tools::getValue('config') as $key => $value) Configuration::updateValue($key, $value);
            if ($this->errors) $output .= $this->displayError(implode($this->errors, '<br/>'));
            else $output .= $this->displayConfirmation($this->l('Settings updated'));
        }
        $vars = [];
        $vars['config'] = $this->getConfig();
        return $output . $this->displayForm($vars);
    }

    public function displayForm($vars) {
        extract($vars);
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $twtfdForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'name' => 'config[SHMO_TWITTERTOOL_WIDGETID]',
                    'label' => $this->l('Twitter Widget ID'),
                    'hint' => 'The Twitter widget ID that gets generated when creating a widget at Twitter.',
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'name' => 'config[SHMO_TWITTERTOOL_USERNAME]',
                    'label' => $this->l('Twitter Username'),
                    'hint' => 'The Twitter username you want to display tweets from.',
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'name' => 'config[SHMO_TWITTERTOOL_TWEET_COUNT]',
                    'label' => $this->l('Number of Tweets'),
                    'hint' => 'Display a specific number of Tweets from 1 to 20.',
                    'desc' => 'Will not have any effect when specifying a custom widget height.',
                    'required' => false
                ],
                [
                    'type' => 'text',
                    'name' => 'config[SHMO_TWITTERTOOL_WIDGET_WIDTH]',
                    'label' => $this->l('Widget Width'),
                    'hint' => 'Specify a custom widget width.',
                    'suffix' => 'px',
                    'required' => false
                ],
                [
                    'type' => 'text',
                    'name' => 'config[SHMO_TWITTERTOOL_WIDGET_HEIGHT]',
                    'label' => $this->l('Widget Height'),
                    'hint' => 'Specify a custom widget height.',
                    'desc' => 'Will not work if you change the default amount of tweets being shown.',
                    'suffix' => 'px',
                    'required' => false
                ],
                [
                    'type' => 'switch',
                    'name' => 'config[SHMO_TWITTERTOOL_NO_HEADER]',
                    'label' => $this->l('No Header'),
                    'hint' => 'Hides the timeline header.',
                    'is_bool' => true,
                    'required' => false,
                    'values' => [
                        [
                            'id' => 'header_off',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id' => 'header_on',
                            'value' => 0,
                            'label' => $this->l('No')
                        ]
                    ]
                ],
                [
                    'type' => 'switch',
                    'name' => 'config[SHMO_TWITTERTOOL_NO_FOOTER]',
                    'label' => $this->l('No Footer'),
                    'hint' => 'Hides the timeline footer and tweet composer link, if included in the timeline widget type.',
                    'is_bool' => true,
                    'required' => false,
                    'values' => [
                        [
                            'id' => 'footer_off',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id' => 'footer_on',
                            'value' => 0,
                            'label' => $this->l('No')
                        ]
                    ]
                ],
                [
                    'type' => 'switch',
                    'name' => 'config[SHMO_TWITTERTOOL_NO_BORDERS]',
                    'label' => $this->l('No Borders'),
                    'hint' => 'Removes all borders within the widget including borders surrounding the widget area and separating tweets.',
                    'is_bool' => true,
                    'required' => false,
                    'values' => [
                        [
                            'id' => 'borders_off',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id' => 'borders_on',
                            'value' => 0,
                            'label' => $this->l('No')
                        ]
                    ]
                ],
                [
                    'type' => 'switch',
                    'name' => 'config[SHMO_TWITTERTOOL_NO_SCROLLBAR]',
                    'label' => $this->l('No Scrollbar'),
                    'hint' => $this->l('Crops and hides the main timeline scrollbar, if visible. Can affect accessibility.'),
                    'is_bool' => true,
                    'required' => false,
                    'values' => [
                        [
                            'id' => 'scrollbar_off',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id' => 'scrollbar_on',
                            'value' => 0,
                            'label' => $this->l('No')
                        ]
                    ]
                ],
                [
                    'type' => 'switch',
                    'name' => 'config[SHMO_TWITTERTOOL_BG_TRANSPARENCY]',
                    'label' => $this->l('Transparent Background'),
                    'hint' => $this->l('Removes the widget’s background color.'),
                    'is_bool' => true,
                    'required' => false,
                    'values' => [
                        [
                            'id' => 'bg_transparency_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id' => 'bg_transparency_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ]
                    ]
                ],
                [
                    'type' => 'radio',
                    'name' => 'config[SHMO_TWITTERTOOL_THEME]',
                    'label' => $this->l('Theme'),
                    'required' => false,
                    'values' => [
                        [
                            'id' => 'theme_light',
                            'value' => 0,
                            'label' => $this->l('Light')
                        ],
                        [
                            'id' => 'theme_dark',
                            'value' => 1,
                            'label' => $this->l('Dark')
                        ]
                    ]
                ],
                [
                    'type' => 'color',
                    'name' => 'config[SHMO_TWITTERTOOL_LINK_COLOR]',
                    'label' => $this->l('Link Color'),
                    'data-hex' => true,
                    'class' => 'mColorPicker',
                    'required' => 'false'
                ],
                [
                    'type' => 'color',
                    'name' => 'config[SHMO_TWITTERTOOL_BORDER_COLOR]',
                    'label' => $this->l('Border Color'),
                    'data-hex' => true,
                    'class' => 'mColorPicker',
                    'required' => 'false'
                ],
                [
                    'type' => 'switch',
                    'name' => 'config[SHMOTWEETFEED_ASSERTIVE_POLITENESS]',
                    'label' => $this->l('Assertive Politeness'),
                    'hint' => $this->l('Set the embedded timeline live region politeness to assertive.'),
                    'is_bool' => true,
                    'required' => false,
                    'values' => [
                        [
                            'id' => 'asservice_politeness_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id' => 'asservice_politeness_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ]
                    ]
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ];
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = [
            'save' =>
                [
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                        '&token=' . Tools::getAdminTokenLite('AdminModules'),
                ],
            'back' => [
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];
        $helper->fields_value['config[SHMO_TWITTERTOOL_WIDGETID]'] = Configuration::get('SHMO_TWITTERTOOL_WIDGETID');
        $helper->fields_value['config[SHMO_TWITTERTOOL_USERNAME]'] = Configuration::get('SHMO_TWITTERTOOL_USERNAME');
        $helper->fields_value['config[SHMO_TWITTERTOOL_TWEET_COUNT]'] = Configuration::get('SHMO_TWITTERTOOL_TWEET_COUNT');
        $helper->fields_value['config[SHMO_TWITTERTOOL_WIDGET_WIDTH]'] = Configuration::get('SHMO_TWITTERTOOL_WIDGET_WIDTH');
        $helper->fields_value['config[SHMO_TWITTERTOOL_WIDGET_HEIGHT]'] = Configuration::get('SHMO_TWITTERTOOL_WIDGET_HEIGHT');
        $helper->fields_value['config[SHMO_TWITTERTOOL_NO_HEADER]'] = Configuration::get('SHMO_TWITTERTOOL_NO_HEADER');
        $helper->fields_value['config[SHMO_TWITTERTOOL_NO_FOOTER]'] = Configuration::get('SHMO_TWITTERTOOL_NO_FOOTER');
        $helper->fields_value['config[SHMO_TWITTERTOOL_NO_BORDERS]'] = Configuration::get('SHMO_TWITTERTOOL_NO_BORDERS');
        $helper->fields_value['config[SHMO_TWITTERTOOL_NO_SCROLLBAR]'] = Configuration::get('SHMO_TWITTERTOOL_NO_SCROLLBAR');
        $helper->fields_value['config[SHMO_TWITTERTOOL_BG_TRANSPARENCY]'] = Configuration::get('SHMO_TWITTERTOOL_BG_TRANSPARENCY');
        $helper->fields_value['config[SHMO_TWITTERTOOL_THEME]'] = Configuration::get('SHMO_TWITTERTOOL_THEME');
        $helper->fields_value['config[SHMO_TWITTERTOOL_LINK_COLOR]'] = Configuration::get('SHMO_TWITTERTOOL_LINK_COLOR');
        $helper->fields_value['config[SHMO_TWITTERTOOL_BORDER_COLOR]'] = Configuration::get('SHMO_TWITTERTOOL_BORDER_COLOR');
        $helper->fields_value['config[SHMO_TWITTERTOOL_ASSERTIVE_POLITENESS]'] = Configuration::get('SHMO_TWITTERTOOL_ASSERTIVE_POLITENESS');
        return $helper->generateForm($twtfdForm);
    }

    public function hookDisplayLeftColumn() {
        $config = $this->getConfig();
        if (!$config['SHMO_TWITTERTOOL_USERNAME'] || !$config['SHMO_TWITTERTOOL_WIDGETID']) return false;
        $this->context->smarty->assign([
            'shmotwttrtl' => $config
        ]);
        return $this->display(__FILE__, 'shmotwittertool.tpl');
    }

    public function hookDisplayRightColumn($params) {
        return $this->hookDisplayLeftColumn($params);
    }

    public function hookDisplayTop($params) {
        return $this->hookDisplayLeftColumn($params);
    }

    public function hookDisplayHome($params) {
        return $this->hookDisplayLeftColumn($params);
    }

    public function hookDisplayFooter($params) {
        return $this->hookDisplayLeftColumn($params);
    }

    public function hookBackOfficeHeader() {
        $this->context->controller->addJS('/js/jquery/plugins/jquery.validate.js');
        $this->context->controller->addJS(_MODULE_DIR_ . $this->name . '/js/backend.js');
    }
}