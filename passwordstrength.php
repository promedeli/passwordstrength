<?php
if (!defined('_PS_VERSION_')) {
    exit;
}
require_once 'Promedeli/bootstrap.php';

use Promedeli\Model\Config;
use Promedeli\Service\CheckIf;
use Promedeli\Service\ConfigManager;
use Promedeli\Service\FrontendResourceManager;
use Promedeli\Service\ConfigStoragePSDefault;


class Passwordstrength extends Module
{
    private $adminContent;
    private $config;
    private $frontendResourceManager;

    public function __construct()
    {
        $this->name = 'passwordstrength';
        $this->adminContent = '';

        $this->tab = 'front_office_features';
        $this->version = '2.0.1';
        $this->author = 'Promedeli';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => _PS_VERSION_];
        $this->bootstrap = true;
        $this->module_key = 'e872b87e9824388917933e8a66e51e30';

        parent::__construct();

        /**
         * Create config of the module
         */
        $this->config = new Config($this->name);
        $this->config->setPrestashopVersion(_PS_VERSION_);
        $this->config->setContext($this->context);
        $this->config->setStorage(new ConfigStoragePSDefault(Configuration::class, $this->name));

        /**
         * Create Frontend resource manager
         */
        $this->frontendResourceManager = new FrontendResourceManager($this->config);

        $this->displayName = $this->l('Password strength meter');
        $this->description = $this->l('This simple module adds a meter under the password field giving your customers instant feedback on the strength of their passwords, thus giving your customers a more secure shopping experience.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall Password strength meter?');
    }

    public function install()
    {
        $this->_clearCache('*');

        $status = parent::install()
            && $this->registerHook('displayCustomerAccountForm')
            && $this->registerHook('header');

        $configuration = $this->getDefaultConfiguration();
        $this->config->setParams($configuration);

        $configManager = new ConfigManager(Configuration::class);
        $configManager->storeAllParams($this->config);


        return $status;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return true;
    }

    public function hookHeader()
    {
        $this->frontendResourceManager->addJs($this->config->getModuleName());
        $this->frontendResourceManager->addCss($this->config->getModuleName());

    }

    public function hookDisplayCustomerAccountForm($params)
    {
        foreach (array_keys($this->getDefaultConfiguration()) as $key) {
            $this->context->smarty->assign([$key => $this->config->$key]);
        };

        return $this->display(__FILE__, 'passwordstrength.tpl');
    }

    public function getDefaultConfiguration()
    {
        return [
            's0' => 'too easy',
            's1' => 'still easy',
            's2' => 'weak',
            's3' => 'good',
            's4' => 'strong',


            'color1' => 'red',
            'color2' => 'yellow',
            'color3' => 'orange',
            'color4' => 'green',

            'text' => 'Password is %s',
            'display_text' => true
        ];
    }

    public function getContent()
    {
        if (!empty($_POST['submit' . $this->name]) && $this->_postValidation()) {
            $currentConfiguration = $this->getDefaultConfiguration();
            foreach ($currentConfiguration as $key => $value) {
                $this->config->getStorage()->saveParam($key, Tools::getValue($key));
            }

            $this->adminContent .= $this->displayConfirmation($this->l('Settings updated'));
        }

        return $this->adminContent . $this->displayForm();
    }


    public function displayForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $formFields = [];
        require 'config/config_form.php';


        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = [
            'save' =>
                [
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                ],
            'back' => [
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];

        // Load current value
        $helper->fields_value['text'] = Tools::getValue('text', $this->config->getParam('text'));
        $helper->fields_value['display_text'] = Tools::getValue('display_text',
            $this->config->getParam('display_text'));
        $helper->fields_value['s0'] = Tools::getValue('s0', $this->config->getParam('s0'));
        $helper->fields_value['s1'] = Tools::getValue('s1', $this->config->getParam('s1'));
        $helper->fields_value['s2'] = Tools::getValue('s2', $this->config->getParam('s2'));
        $helper->fields_value['s3'] = Tools::getValue('s3', $this->config->getParam('s3'));
        $helper->fields_value['s4'] = Tools::getValue('s4', $this->config->getParam('s4'));

        $helper->fields_value['color1'] = Tools::getValue('color1', $this->config->getParam('color1'));
        $helper->fields_value['color2'] = Tools::getValue('color2', $this->config->getParam('color2'));
        $helper->fields_value['color3'] = Tools::getValue('color3', $this->config->getParam('color3'));
        $helper->fields_value['color4'] = Tools::getValue('color4', $this->config->getParam('color4'));

        return $helper->generateForm($formFields);
    }

    protected function _postValidation()
    {
        $errors = [];

        if (Tools::isSubmit('submit' . $this->name)) {
            if (!Validate::isString(Tools::getValue('display_text'))) {
                $errors[] = $this->getTranslator()->trans('Invalid value for "Display text" field', [],
                    'Modules.Passwordstrength.Admin');
            }

            if (!Tools::getValue('text') || !Validate::isString(Tools::getValue('text'))) {
                $errors[] = $this->getTranslator()->trans('Invalid text displayed under meter.', [],
                    'Modules.Passwordstrength.Admin');
            }

            if (!Tools::getValue('s0') || !Validate::isString(Tools::getValue('s0'))) {
                $errors[] = $this->getTranslator()->trans('Invalid the "Level 0 word" field.', [],
                    'Modules.Passwordstrength.Admin');
            }

            if (!Tools::getValue('s1') || !Validate::isString(Tools::getValue('s1'))) {
                $errors[] = $this->getTranslator()->trans('Invalid the "Level 1 word" field.', [],
                    'Modules.Passwordstrength.Admin');
            }

            if (!Tools::getValue('s2') || !Validate::isString(Tools::getValue('s2'))) {
                $errors[] = $this->getTranslator()->trans('Invalid the "Level 2 word" field.', [],
                    'Modules.Passwordstrength.Admin');
            }

            if (!Tools::getValue('s3') || !Validate::isString(Tools::getValue('s3'))) {
                $errors[] = $this->getTranslator()->trans('Invalid the "Level 3 word" field.', [],
                    'Modules.Passwordstrength.Admin');
            }

            if (!Tools::getValue('s4') || !Validate::isString(Tools::getValue('s4'))) {
                $errors[] = $this->getTranslator()->trans('Invalid the "Level 4 word" field.', [],
                    'Modules.Passwordstrength.Admin');
            }

            if (!CheckIf::isHtmlColor(Tools::getValue('color1'))) {
                $errors[] = $this->getTranslator()->trans('Invalid value for color1', [],
                    'Modules.Passwordstrength.Admin');
            }

            if (!CheckIf::isHtmlColor(Tools::getValue('color2'))) {
                $errors[] = $this->getTranslator()->trans('Invalid value for color2', [],
                    'Modules.Passwordstrength.Admin');
            }

            if (!CheckIf::isHtmlColor(Tools::getValue('color3'))) {
                $errors[] = $this->getTranslator()->trans('Invalid value for color3', [],
                    'Modules.Passwordstrength.Admin');
            }

            if (!CheckIf::isHtmlColor(Tools::getValue('color4'))) {
                $errors[] = $this->getTranslator()->trans('Invalid value for color4', [],
                    'Modules.Passwordstrength.Admin');
            }
        }

        if (count($errors)) {
            $this->adminContent .= $this->displayError(implode('<br />', $errors));
            return false;
        }

        return true;
    }
}
