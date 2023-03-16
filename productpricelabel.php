<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Productpricelabel extends Module
{
    public function __construct()
    {
        $this->name = 'productpricelabel';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Kamil';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Product price label');
        $this->description = $this->l('Displays label if product price is under 100 PLN.');
        $this->ps_versions_compliancy = ['min' => '1.7.8.0', 'max' => _PS_VERSION_];
    }

    public function install()
    {
        return parent::install() && $this->registerHook('actionProductFlagsModifier');
    }

    public function hookActionProductFlagsModifier(array $params)
    {
        if (!isset($params['flags']) || !isset($params['product']['id_product'])) {
            return;
        }

        $idProduct = (int)$params['product']['id_product'];
        $productObject = new Product($idProduct);

        if (!Validate::isLoadedObject($productObject)) {
            return;
        }

        $productPrice = $productObject->getPrice();

        if ($productPrice < 100) {
            $params['flags']['under_100'] = [
                'type' => 'new',
                'label' => $this->l('Best online price'),
            ];
        }

        return $params;
    }
}