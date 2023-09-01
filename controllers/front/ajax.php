<?php
/**
 * <ModuleClassName> => ht_estimate
 * <FileName> => ajax.php
 * Format expected: <ModuleClassName><FileName>ModuleFrontController
 */
class ht_estimateAjaxModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {   parent::initContent();
        $this->ajax = true;
    }

    public function postProcess()
    {
        return parent::postProcess();
    }

    public function displayAjax() {
        if ($this->errors)
            die(Tools::jsonEncode(array('hasError' => true, 'errors' => $this->errors)));
        else {
            exit;
        }
    }
}