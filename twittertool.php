<?php if (!defined('_PS_VERSION_')) exit;

class TwitterTool extends Module
{
    protected $errors = [];

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

    public function __construct() {
        $this->name = 'twittertool';
        $this->tab = 'front_office_features';
        $this->version = '1.1.0';
        $this->author = 'Andre Matthies';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '1.5', 'max' => _PS_VERSION_];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TwitterTool');
        $this->description = $this->l('Adds a block to display tweets in a timeline.');
        $this->confirmUninstall = $this->l('Are you sure you want to delete TwitterTool?');
    }

    public function install() {
        if (Shop::isFeatureActive()) Shop::setContext(Shop::CONTEXT_ALL);
        return parent::install()
        && $this->installConfig()
        && $this->registerHook('actionAdminControllerSetMedia')
        && $this->registerHook('actionFrontControllerSetMedia')
        && $this->registerHook('displayFooter');
    }

    public function uninstall() {
        return parent::uninstall() && $this->removeConfig();
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
        if (Tools::isSubmit('submittwittertool')) {
            foreach (Tools::getValue('config') as $key => $value) Configuration::updateValue($key, $value);
            if ($this->errors) $output .= $this->displayError(implode($this->errors, '<br/>'));
            else $output .= $this->displayConfirmation($this->l('Settings updated'));
        }
        return $output . $this->displayForm();
    }

    public function displayForm() {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $twtfdForm[0]['form'] =  [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'name' => 'config[TWITTERTOOL_USERNAME]',
                    'label' => $this->l('Twitter Username'),
                    'hint' => 'The Twitter username you want to display tweets from.',
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'name' => 'config[TWITTERTOOL_TWEET_COUNT]',
                    'label' => $this->l('Number of Tweets'),
                    'hint' => 'Display a specific number of Tweets from 1 to 20.',
                    'desc' => 'Will not have any effect when specifying a custom widget height.',
                    'required' => false
                ],
                [
                    'type' => 'text',
                    'name' => 'config[TWITTERTOOL_WIDGET_WIDTH]',
                    'label' => $this->l('Widget Width'),
                    'hint' => 'Specify a custom widget width.',
                    'suffix' => 'px',
                    'required' => false
                ],
                [
                    'type' => 'text',
                    'name' => 'config[TWITTERTOOL_WIDGET_HEIGHT]',
                    'label' => $this->l('Widget Height'),
                    'hint' => 'Specify a custom widget height.',
                    'desc' => 'Will not work if you change the default amount of tweets being shown.',
                    'suffix' => 'px',
                    'required' => false
                ],
                [
                    'type' => 'switch',
                    'name' => 'config[TWITTERTOOL_NO_HEADER]',
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
                    'name' => 'config[TWITTERTOOL_NO_FOOTER]',
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
                    'name' => 'config[TWITTERTOOL_NO_BORDERS]',
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
                    'name' => 'config[TWITTERTOOL_NO_SCROLLBAR]',
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
                    'name' => 'config[TWITTERTOOL_BG_TRANSPARENCY]',
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
                    'name' => 'config[TWITTERTOOL_THEME]',
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
                    'name' => 'config[TWITTERTOOL_LINK_COLOR]',
                    'label' => $this->l('Link Color'),
                    'data-hex' => true,
                    'class' => 'mColorPicker',
                    'required' => 'false'
                ],
                [
                    'type' => 'color',
                    'name' => 'config[TWITTERTOOL_BORDER_COLOR]',
                    'label' => $this->l('Border Color'),
                    'data-hex' => true,
                    'class' => 'mColorPicker',
                    'required' => 'false'
                ],
                [
                    'type' => 'switch',
                    'name' => 'config[TWITTERTOOL_ASSERTIVE_POLITENESS]',
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
        $helper->fields_value['config[TWITTERTOOL_USERNAME]'] = Configuration::get('TWITTERTOOL_USERNAME');
        $helper->fields_value['config[TWITTERTOOL_TWEET_COUNT]'] = Configuration::get('TWITTERTOOL_TWEET_COUNT');
        $helper->fields_value['config[TWITTERTOOL_WIDGET_WIDTH]'] = Configuration::get('TWITTERTOOL_WIDGET_WIDTH');
        $helper->fields_value['config[TWITTERTOOL_WIDGET_HEIGHT]'] = Configuration::get('TWITTERTOOL_WIDGET_HEIGHT');
        $helper->fields_value['config[TWITTERTOOL_NO_HEADER]'] = Configuration::get('TWITTERTOOL_NO_HEADER');
        $helper->fields_value['config[TWITTERTOOL_NO_FOOTER]'] = Configuration::get('TWITTERTOOL_NO_FOOTER');
        $helper->fields_value['config[TWITTERTOOL_NO_BORDERS]'] = Configuration::get('TWITTERTOOL_NO_BORDERS');
        $helper->fields_value['config[TWITTERTOOL_NO_SCROLLBAR]'] = Configuration::get('TWITTERTOOL_NO_SCROLLBAR');
        $helper->fields_value['config[TWITTERTOOL_BG_TRANSPARENCY]'] = Configuration::get('TWITTERTOOL_BG_TRANSPARENCY');
        $helper->fields_value['config[TWITTERTOOL_THEME]'] = Configuration::get('TWITTERTOOL_THEME');
        $helper->fields_value['config[TWITTERTOOL_LINK_COLOR]'] = Configuration::get('TWITTERTOOL_LINK_COLOR');
        $helper->fields_value['config[TWITTERTOOL_BORDER_COLOR]'] = Configuration::get('TWITTERTOOL_BORDER_COLOR');
        $helper->fields_value['config[TWITTERTOOL_ASSERTIVE_POLITENESS]'] = Configuration::get('TWITTERTOOL_ASSERTIVE_POLITENESS');
        return $helper->generateForm([['form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'name' => 'config[TWITTERTOOL_USERNAME]',
                        'label' => $this->l('Twitter Username'),
                        'hint' => 'The Twitter username you want to display tweets from.',
                        'required' => true
                    ],
                    [
                        'type' => 'text',
                        'name' => 'config[TWITTERTOOL_TWEET_COUNT]',
                        'label' => $this->l('Number of Tweets'),
                        'hint' => 'Display a specific number of Tweets from 1 to 20.',
                        'desc' => 'Will not have any effect when specifying a custom widget height.',
                        'required' => false
                    ],
                    [
                        'type' => 'text',
                        'name' => 'config[TWITTERTOOL_WIDGET_WIDTH]',
                        'label' => $this->l('Widget Width'),
                        'hint' => 'Specify a custom widget width.',
                        'suffix' => 'px',
                        'required' => false
                    ],
                    [
                        'type' => 'text',
                        'name' => 'config[TWITTERTOOL_WIDGET_HEIGHT]',
                        'label' => $this->l('Widget Height'),
                        'hint' => 'Specify a custom widget height.',
                        'desc' => 'Will not work if you change the default amount of tweets being shown.',
                        'suffix' => 'px',
                        'required' => false
                    ],
                    [
                        'type' => 'switch',
                        'name' => 'config[TWITTERTOOL_NO_HEADER]',
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
                        'name' => 'config[TWITTERTOOL_NO_FOOTER]',
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
                        'name' => 'config[TWITTERTOOL_NO_BORDERS]',
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
                        'name' => 'config[TWITTERTOOL_NO_SCROLLBAR]',
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
                        'name' => 'config[TWITTERTOOL_BG_TRANSPARENCY]',
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
                        'name' => 'config[TWITTERTOOL_THEME]',
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
                        'name' => 'config[TWITTERTOOL_LINK_COLOR]',
                        'label' => $this->l('Link Color'),
                        'data-hex' => true,
                        'class' => 'mColorPicker',
                        'required' => 'false'
                    ],
                    [
                        'type' => 'color',
                        'name' => 'config[TWITTERTOOL_BORDER_COLOR]',
                        'label' => $this->l('Border Color'),
                        'data-hex' => true,
                        'class' => 'mColorPicker',
                        'required' => 'false'
                    ],
                    [
                        'type' => 'switch',
                        'name' => 'config[TWITTERTOOL_ASSERTIVE_POLITENESS]',
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
            ]]]);
    }

    public function hookDisplayFooter() {
        $config = $this->getConfig();
        return $config['TWITTERTOOL_USERNAME'] && $this->context->smarty->assign($config) ? $this->display(__FILE__, 'twittertool.tpl') : false;
    }

    public function hookDisplayLeftColumn() {
        return $this->hookDisplayFooter();
    }

    public function hookDisplayRightColumn() {
        return $this->hookDisplayFooter();
    }

    public function hookDisplayTop() {
        return $this->hookDisplayFooter();
    }

    public function hookDisplayHome() {
        return $this->hookDisplayFooter();
    }

    public function hookActionAdminControllerSetMedia() {
        $this->context->controller->addJqueryPlugin('validate');
        $this->context->controller->addJS(_MODULE_DIR_ . $this->name . '/views/js/backend.js');
    }

    public function hookActionFrontControllerSetMedia() {
        $this->context->controller->addJS(_MODULE_DIR_ . $this->name . '/views/js/frontend.js');
    }
}