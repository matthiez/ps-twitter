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

class BackendHelperForm extends HelperForm
{
    public function __construct($name)
    {
        parent::__construct();

        $defaultLang = Configuration::get('PS_LANG_DEFAULT');

        $this->allow_employee_form_lang = $defaultLang;

        $this->currentIndex = AdminController::$currentIndex . "&configure=$name";

        $this->default_form_language = $defaultLang;

        $this->fields_form = array(array('form' => array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'name' => 'config[EOO_TWITTER_USERNAME]',
                    'label' => $this->l('Twitter Username'),
                    'hint' => 'The Twitter username you want to display tweets from.',
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'name' => 'config[EOO_TWITTER_TWEET_COUNT]',
                    'label' => $this->l('Number of Tweets'),
                    'hint' => 'Display a specific number of Tweets from 1 to 20.',
                    'desc' => 'Will not have any effect when specifying a custom widget height.',
                    'required' => false
                ),
                array(
                    'type' => 'text',
                    'name' => 'config[EOO_TWITTER_WIDGET_WIDTH]',
                    'label' => $this->l('Widget Width'),
                    'hint' => 'Specify a custom widget width.',
                    'suffix' => 'px',
                    'required' => false
                ),
                array(
                    'type' => 'text',
                    'name' => 'config[EOO_TWITTER_WIDGET_HEIGHT]',
                    'label' => $this->l('Widget Height'),
                    'hint' => 'Specify a custom widget height.',
                    'desc' => 'Will not work if you change the default amount of tweets being shown.',
                    'suffix' => 'px',
                    'required' => false
                ),
                array(
                    'type' => 'switch',
                    'name' => 'config[EOO_TWITTER_NO_HEADER]',
                    'label' => $this->l('No Header'),
                    'hint' => 'Hides the timeline header.',
                    'is_bool' => true,
                    'required' => false,
                    'values' => array(
                        array(
                            'id' => 'header_off',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'header_on',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'switch',
                    'name' => 'config[EOO_TWITTER_NO_FOOTER]',
                    'label' => $this->l('No Footer'),
                    'hint' =>
                        'Hides the timeline footer and tweet composer link, if included in the timeline widget type.',
                    'is_bool' => true,
                    'required' => false,
                    'values' => array(
                        array(
                            'id' => 'footer_off',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'footer_on',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'switch',
                    'name' => 'config[EOO_TWITTER_NO_BORDERS]',
                    'label' => $this->l('No Borders'),
                    'hint' =>
                        'Removes all borders within the widget
                         including borders surrounding the widget area and separating tweets.',
                    'is_bool' => true,
                    'required' => false,
                    'values' => array(
                        array(
                            'id' => 'borders_off',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'borders_on',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'switch',
                    'name' => 'config[EOO_TWITTER_NO_SCROLLBAR]',
                    'label' => $this->l('No Scrollbar'),
                    'hint' =>
                        $this->l('Crops and hides the main timeline scrollbar, if visible. Can affect accessibility.'),
                    'is_bool' => true,
                    'required' => false,
                    'values' => array(
                        array(
                            'id' => 'scrollbar_off',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'scrollbar_on',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'switch',
                    'name' => 'config[EOO_TWITTER_BG_TRANSPARENCY]',
                    'label' => $this->l('Transparent Background'),
                    'hint' => $this->l('Removes the widget’s background color.'),
                    'is_bool' => true,
                    'required' => false,
                    'values' => array(
                        array(
                            'id' => 'bg_transparency_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'bg_transparency_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'radio',
                    'name' => 'config[EOO_TWITTER_THEME]',
                    'label' => $this->l('Theme'),
                    'required' => false,
                    'values' => array(
                        array(
                            'id' => 'theme_light',
                            'value' => 0,
                            'label' => $this->l('Light')
                        ),
                        array(
                            'id' => 'theme_dark',
                            'value' => 1,
                            'label' => $this->l('Dark')
                        )
                    )
                ),
                array(
                    'type' => 'color',
                    'name' => 'config[EOO_TWITTER_LINK_COLOR]',
                    'label' => $this->l('Link Color'),
                    'data-hex' => true,
                    'class' => 'mColorPicker',
                    'required' => 'false'
                ),
                array(
                    'type' => 'color',
                    'name' => 'config[EOO_TWITTER_BORDER_COLOR]',
                    'label' => $this->l('Border Color'),
                    'data-hex' => true,
                    'class' => 'mColorPicker',
                    'required' => 'false'
                ),
                array(
                    'type' => 'switch',
                    'name' => 'config[EOO_TWITTER_ASSERTIVE_POLITENESS]',
                    'label' => $this->l('Assertive Politeness'),
                    'hint' => $this->l('Set the embedded timeline live region politeness to assertive.'),
                    'is_bool' => true,
                    'required' => false,
                    'values' => array(
                        array(
                            'id' => 'asservice_politeness_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'asservice_politeness_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        )));

        $this->fields_value['config[EOO_TWITTER_USERNAME]']
            = Configuration::get('EOO_TWITTER_USERNAME');
        $this->fields_value['config[EOO_TWITTER_TWEET_COUNT]']
            = Configuration::get('EOO_TWITTER_TWEET_COUNT');
        $this->fields_value['config[EOO_TWITTER_WIDGET_WIDTH]']
            = Configuration::get('EOO_TWITTER_WIDGET_WIDTH');
        $this->fields_value['config[EOO_TWITTER_WIDGET_HEIGHT]']
            = Configuration::get('EOO_TWITTER_WIDGET_HEIGHT');
        $this->fields_value['config[EOO_TWITTER_NO_HEADER]']
            = Configuration::get('EOO_TWITTER_NO_HEADER');
        $this->fields_value['config[EOO_TWITTER_NO_FOOTER]']
            = Configuration::get('EOO_TWITTER_NO_FOOTER');
        $this->fields_value['config[EOO_TWITTER_NO_BORDERS]']
            = Configuration::get('EOO_TWITTER_NO_BORDERS');
        $this->fields_value['config[EOO_TWITTER_NO_SCROLLBAR]']
            = Configuration::get('EOO_TWITTER_NO_SCROLLBAR');
        $this->fields_value['config[EOO_TWITTER_BG_TRANSPARENCY]']
            = Configuration::get('EOO_TWITTER_BG_TRANSPARENCY');
        $this->fields_value['config[EOO_TWITTER_THEME]']
            = Configuration::get('EOO_TWITTER_THEME');
        $this->fields_value['config[EOO_TWITTER_LINK_COLOR]']
            = Configuration::get('EOO_TWITTER_LINK_COLOR');
        $this->fields_value['config[EOO_TWITTER_BORDER_COLOR]']
            = Configuration::get('EOO_TWITTER_BORDER_COLOR');
        $this->fields_value['config[EOO_TWITTER_ASSERTIVE_POLITENESS]']
            = Configuration::get('EOO_TWITTER_ASSERTIVE_POLITENESS');

        $this->module = $this;

        $this->name = $name;

        $this->name_controller = $name;

        $this->title = $name;

        $this->token = Tools::getAdminTokenLite('AdminModules');

        $this->show_toolbar = true;

        $this->submit_action = 'submit' . $name;

        $this->toolbar_btn = array(
            'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex . "&configure=$name&save$name&token=" . Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        $this->toolbar_scroll = true;
    }
}
