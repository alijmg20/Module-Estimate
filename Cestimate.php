
<?php

class Cestimate extends ObjectModel
{
	public $fullname;
	public $phone;
	public $email;
	public $description;
	public $id_product;

	public static $definition = array(
		'table' => 'estimate',
		'primary' => 'id_estimate',
		'multilang' => false,
		'fields' => array(
			'fullname'    			=> array('type' => self::TYPE_STRING, 'validate' => 'isName', 'required' => true),
			'phone'  			    => array('type' => self::TYPE_STRING, 'validate' => 'isPhoneNumber', 'required' => true),
			'email'     		  	=> array('type' => self::TYPE_STRING, 'validate' => 'isEmail', 'required' => true),
			'description'     		=> array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'required' => true),
			'id_product'     		=> array('type' => self::TYPE_INT, 'required' => true),
		)
	);

	public	function __construct($id_estimate = null, $id_lang = null, $id_shop = null, Context $context = null)
	{
		parent::__construct($id_estimate, $id_lang, $id_shop);
	}

	public static function getAll()
	{
		return Db::getInstance()->executeS(
			"SELECT E.* FROM " . _DB_PREFIX_ . "estimate AS E"
		);
	}

	public static function getAllwithProducts()
	{
		$context = Context::getContext();
		$id_lang = $context->language->id;
	
		$sql = "
			SELECT 
				E.*,
				PL.name AS product_name
			FROM " . _DB_PREFIX_ . "estimate AS E
			LEFT JOIN " . _DB_PREFIX_ . "product AS P ON E.id_product = P.id_product
			LEFT JOIN " . _DB_PREFIX_ . "product_lang AS PL ON P.id_product = PL.id_product AND PL.id_lang = " . (int)$id_lang . "
		";
	
		return Db::getInstance()->executeS($sql);
	}	
	

	public static function getOne($id_estimate)
	{
		return Db::getInstance()->getRow(
			"SELECT E.* FROM " . _DB_PREFIX_ . "estimate AS E WHERE id_estimate = " . $id_estimate
		);
	}

	public static function isDescription($value)
	{
		// Verifica que el valor no esté vacío
		if (empty($value)) {
			return false;
		}

		return true;
	}
	public static function isPhoneNumber($value)
	{
		// Elimina todos los caracteres que no sean dígitos
		$value = preg_replace('/[^0-9]/', '', $value);

		// Verifica que el valor contenga solo dígitos y tenga una longitud válida
		if (strlen($value) < 7 || strlen($value) > 15 || !ctype_digit($value)) {
			return false;
		}

		return true;
	}
}
