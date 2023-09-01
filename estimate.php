<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__) . '/Cestimate.php';

class estimate extends Module
{
    private $html = '';

    public function __construct()
    {
        $this->name = 'estimate';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Ali Developer';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Solicitud de presupuestos formulario');
        $this->description = $this->l('Muestre formulario en popup para solicitar presupuestos');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        return  parent::install() &&
            $this->registerHooks() &&
            $this->setDefaults();
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');

        return  Configuration::deleteByName('CLIENTS_EMAIL') &&
            Configuration::deleteByName('CLIENTS_MSG') &&
            parent::uninstall();
    }

    protected function registerHooks()
    {
        return  $this->registerHook('header') &&
            $this->registerHook('displayProductAdditionalInfo') &&
            $this->registerHook('displayEstimate');
    }

    public function setDefaults()
    {
        Configuration::updateValue('CLIENTS_EMAIL', Configuration::get('PS_SHOP_EMAIL'));
        $languages = Language::getLanguages(false);
        $values = array();

        foreach ($languages as $lang)
            $values['CLIENTS_MSG'][$lang['id_lang']] = 'Tu presupuesto ha sido solicitado correctamente.';

        Configuration::updateValue('CLIENTS_MSG', $values['CLIENTS_MSG']);
    }

    private function postValidation()
    {
        if (Tools::isSubmit('btnSubmit')) {
            $languages = Language::getLanguages(false);

            foreach ($languages as $lang) {
                if (!Tools::getValue('CLIENTS_MSG_' . $lang['id_lang'])) {
                    $this->_postErrors[] = $this->trans('Configure this message for every active language in the shop.', array(), 'Modules.estimate.Admin');
                    return;
                }
            }

            if (!Tools::getValue('CLIENTS_EMAIL')) {
                $this->_postErrors[] = $this->trans('Shop email is required.', array(), 'Modules.estimate.Admin');
            }
        }
    }

    private function postProcess()
    {
        if (Tools::isSubmit('btnSubmit')) {
            Configuration::updateValue('CLIENTS_EMAIL', Tools::getValue('CLIENTS_EMAIL'));

            $languages = Language::getLanguages(false);
            $values = array();

            foreach ($languages as $lang) {
                $values['CLIENTS_MSG'][$lang['id_lang']] = Tools::getValue('CLIENTS_MSG_' . $lang['id_lang']);
            }

            Configuration::updateValue('CLIENTS_MSG', $values['CLIENTS_MSG']);
        }

        $this->html .= $this->displayConfirmation(
            $this->trans('Settings updated', array(), 'Admin.Notifications.Success')
        );
    }

    public function getContent()
    {
        $this->html = '';

        if (Tools::isSubmit('btnSubmit')) {
            $this->postValidation();
            if (!count($this->_postErrors))
                $this->postProcess();
            else {
                foreach ($this->_postErrors as $err) {
                    $this->html .= $this->displayError($err);
                }
            }
        } else if (Tools::isSubmit('delete_id')) {
            $estimate = new Estimate((int)Tools::getValue('delete_id'));
            $res = $estimate->delete();

            if (!$res)
                $this->_html .= $this->displayError('Could not delete.');
            else
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=1&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
        }
        if (Tools::isSubmit('view_id')) {
            $view_id = Tools::getValue('view_id');
            return $this->html . $this->renderView($view_id);
        }

        $this->html .= $this->renderForm() . $this->renderList();

        return $this->html;
    }

    public function renderView($view_id){
        $estimate = new Estimate((int)$view_id);
        $product = new Product((int)$estimate->id_product);
        $context = Context::getContext();
        $id_lang = $context->language->id;
        $this->context->smarty->assign(array(
            'link' => $this->context->link,
            'estimate' => $estimate,
            'product' => $product,
            'id_lang' => $id_lang,
        ));
        return $this->display(__FILE__, 'estimate.tpl');
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans('Configuration', array(), 'Modules.estimate.Admin'),
                    'icon' => 'icon-envelope'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Email', array(), 'Modules.estimate.Admin'),
                        'col' => 3,
                        'name' => 'CLIENTS_EMAIL',
                        'required' => true,
                        'desc' => $this->trans('Set the email address that will receive all form submissions.', array(), 'Modules.estimate.Admin'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Message', array(), 'Modules.estimate.Admin'),
                        'col' => 6,
                        'name' => 'CLIENTS_MSG',
                        'required' => true,
                        'desc' => $this->trans('This message will be displayed after a customer submits the form.', array(), 'Modules.estimate.Admin'),
                        'lang' => true
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans('Save', array(), 'Admin.Actions'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->identifier = $this->identifier;
        $helper->default_form_language = $this->context->language->id;
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false);
        $helper->currentIndex .= '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $this->fields_form = array();

        return $helper->generateForm(array($fields_form));
    }

    public function renderList()
    {
        $estimates = Estimate::getAllwithProducts();

        $this->context->smarty->assign(
            array(
                'link' => $this->context->link,
                'estimates' => $estimates
            )
        );

        return $this->display(__FILE__, 'list.tpl');
    }

    public function getConfigFieldsValues()
    {
        $languages = Language::getLanguages(false);
        $fields = array();

        $fields['CLIENTS_EMAIL'] = Tools::getValue('CLIENTS_EMAIL', Configuration::get('CLIENTS_EMAIL'));

        foreach ($languages as $lang) {
            $fields['CLIENTS_MSG'][$lang['id_lang']] = Tools::getValue('CLIENTS_MSG_' . $lang['id_lang'], Configuration::get('CLIENTS_MSG', $lang['id_lang']));
        }

        return $fields;
    }

    public function hookHeader()
    {
        Media::addJsDef(array($this->name => array('link' => Context::getContext()->link->getModuleLink($this->name, 'ajax', array('ajax' => true)))));
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
        $this->context->controller->addCSS($this->_path . '/views/js/toastr/toastr.min.css');
        $this->context->controller->addJS($this->_path . '/views/js/toastr/toastr.min.js');
    }

    public function hookDisplayEstimate($params)
    {
        return $this->renderEstimateForm($params);
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        return $this->renderEstimateForm($params);
    }

    public function renderEstimateForm($params)
    {

        $context = Context::getContext();
        $url = $context->link->getModuleLink($this->name, 'submitestimate');
        $product = new Product($params['product']['id']);
        $id_lang = $context->language->id;

        $this->context->smarty->assign(array(
            'url'           => $url,
            'product'      => $product,
            'id_lang'      => $id_lang,
        ));

        return $this->display(__FILE__, 'button.tpl');
    }
}
