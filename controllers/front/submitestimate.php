<?php

require_once dirname(__FILE__) . '/../../Cestimate.php';

class estimateSubmitestimateModuleFrontController extends ModuleFrontController
{
    public $errors = [];
    public $context;
    public function initContent()
    {
        parent::initContent();
        $this->ajax = true;
    }

    public function postProcess() {
        return parent::postProcess();
    }

    public function displayAjax() {
        $fields['fullname']        = [Tools::getValue('fullname'),$this->module->l("Nombre")];
        $fields['phone']            = [Tools::getValue('phone'),$this->module->l("Telefono")];
        $fields['email']            = [Tools::getValue('email'),$this->module->l("Email")];
        $fields['description']      = [Tools::getValue('description'), $this->module->l("Descripción")];
        $fields['id_product']      = [Tools::getValue('id_product'), $this->module->l("Producto")];
        $this->context = Context::getContext();
        foreach($fields as $input){
            !$input[0] ? $this->errors[] = $input[1] . " " . $this->module->l('es obligatorio'): '';
        }

        if (!Validate::isName($fields['fullname'][0])){
            $this->errors[] = $this->module->l('Formato de nombre no válido');
        }
        
        if (!Estimate::isPhoneNumber($fields['phone'][0])){
            $this->errors[] = $this->module->l('Formato de teléfono no válido');
        }
        if (!Validate::isEmail($fields['email'][0])){
            $this->errors[] = $this->module->l('Formato de email no válido');
        }
        if (!Estimate::isDescription($fields['description'][0])){
            $this->errors[] = $this->module->l('Formato de descripción no válido');
        }

        if (!count($this->errors)) {
            $estimate = new Estimate();

          $estimate->fullname      = $fields['fullname'][0];
          $estimate->phone          = $fields['phone'][0];
          $estimate->email          = $fields['email'][0];
          $estimate->description    = $fields['description'][0];
          $estimate->id_product    = $fields['id_product'][0];
          $product = new Product($estimate->id_product);
          $estimate->save();

        $vars = array(
            '{fullname}'           => $estimate->fullname,
            '{phone}'               => $estimate->phone,
            '{email}'               => $estimate->email,
            '{description}'         => $estimate->description,
            '{product_name}'         => $product->name[$this->context->language->id],
          );

          $this->sendEmail($vars);
          die(json_encode(array('success' => true, 'message' => Configuration::get('CLIENTS_MSG', $this->context->language->id))));
        }else{
            $error = '';
            foreach($this->errors as $err){
                $error.= $err.'<br>';
            }
            die(json_encode(array('success' => false, 'message' => $error)));
        }
    }

    public function sendEmail($vars)
    {
        if(
        Mail::Send(
            $this->context->language->id, // Idioma
            'solicitud_presupuesto', // Nombre de la plantilla de email
            $this->module->l('Administrador: Solicitud de presupuesto'), //asunto
            $vars,  // Variables
            Configuration::get('CLIENTS_EMAIL'), // Destinatario
            NULL,   // Nombre del destinatario
            Configuration::get('PS_SHOP_EMAIL'), // Remitente
            Configuration::get("PS_SHOP_NAME"), // Nombre del remitente
            NULL,   // Adjunto
            NULL,   // SMTP
            _PS_MODULE_DIR_.$this->module->name.'/mails/', // Ruta de la plantilla de email
            false,  // Die
            $this->context->shop->id    // ID tienda
        )){
            Mail::Send(
                $this->context->language->id, // Idioma
                'solicitud_presupuesto_customer', // Nombre de la plantilla de email
                $this->module->l('Cliente: Solicitud de presupuesto') . $vars['{product_name}'], //asunto
                $vars,  // Variables
                $vars['{email}'], // Destinatario
                $var['{fullname}'],   // Nombre del destinatario
                Configuration::get('PS_SHOP_EMAIL'), // Remitente
                Configuration::get("PS_SHOP_NAME"), // Nombre del remitente
                NULL,   // Adjunto
                NULL,   // SMTP
                _PS_MODULE_DIR_.$this->module->name.'/mails/', // Ruta de la plantilla de email
                false,  // Die
                $this->context->shop->id    // ID tienda
            );   
        };
    }
}