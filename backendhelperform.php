<?php

class BackendHelperForm extends HelperForm {
    public function __construct($name) {
        parent::__construct();

        $default_lang = Configuration::get('PS_LANG_DEFAULT');

        $this->name = $name;

        $this->module = $this;

        $this->name_controller = $this->name;

        $this->token = Tools::getAdminTokenLite('AdminModules');

        $this->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        $this->default_form_language = $default_lang;

        $this->allow_employee_form_lang = $default_lang;

        $this->title = $this->displayName;

        $this->show_toolbar = true;

        $this->toolbar_scroll = true;

        $this->submit_action = 'submit' . $this->name;

        $this->toolbar_btn = [
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

        $this->fields_value['config[TWITTERTOOL_USERNAME]'] = Configuration::get('TWITTERTOOL_USERNAME');
        $this->fields_value['config[TWITTERTOOL_TWEET_COUNT]'] = Configuration::get('TWITTERTOOL_TWEET_COUNT');
        $this->fields_value['config[TWITTERTOOL_WIDGET_WIDTH]'] = Configuration::get('TWITTERTOOL_WIDGET_WIDTH');
        $this->fields_value['config[TWITTERTOOL_WIDGET_HEIGHT]'] = Configuration::get('TWITTERTOOL_WIDGET_HEIGHT');
        $this->fields_value['config[TWITTERTOOL_NO_HEADER]'] = Configuration::get('TWITTERTOOL_NO_HEADER');
        $this->fields_value['config[TWITTERTOOL_NO_FOOTER]'] = Configuration::get('TWITTERTOOL_NO_FOOTER');
        $this->fields_value['config[TWITTERTOOL_NO_BORDERS]'] = Configuration::get('TWITTERTOOL_NO_BORDERS');
        $this->fields_value['config[TWITTERTOOL_NO_SCROLLBAR]'] = Configuration::get('TWITTERTOOL_NO_SCROLLBAR');
        $this->fields_value['config[TWITTERTOOL_BG_TRANSPARENCY]'] = Configuration::get('TWITTERTOOL_BG_TRANSPARENCY');
        $this->fields_value['config[TWITTERTOOL_THEME]'] = Configuration::get('TWITTERTOOL_THEME');
        $this->fields_value['config[TWITTERTOOL_LINK_COLOR]'] = Configuration::get('TWITTERTOOL_LINK_COLOR');
        $this->fields_value['config[TWITTERTOOL_BORDER_COLOR]'] = Configuration::get('TWITTERTOOL_BORDER_COLOR');
        $this->fields_value['config[TWITTERTOOL_ASSERTIVE_POLITENESS]'] = Configuration::get('TWITTERTOOL_ASSERTIVE_POLITENESS');

        $this->fields_form = [ [ 'form' => [
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
        ] ] ];
    }
}