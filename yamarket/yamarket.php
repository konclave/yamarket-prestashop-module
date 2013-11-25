<?php
class yamarket extends Module
{
    private $_html = '';
    private $_settings = array();

    function __construct()
    {
        $this->name = 'yamarket';
        parent::__construct();

        $this->tab = 'export';
        $this->version = '0.2';
        $this->displayName = $this->l('Yandex Маркет Lite');
        $this->description = $this->l('Экспорт товаров в формате YML');
        $this->_settings = unserialize(Configuration::get($this->name . '_s'));
    }

    public function install()
    {
        parent::install();

        Configuration::updateValue($this->name . '_s', 'a:10:{s:4:"y_sn";s:0:"";s:4:"y_fn";s:0:"";s:4:"y_gz";b:0;s:4:"y_tv";s:1:"0";s:4:"y_dl";s:1:"1";s:4:"y_av";s:1:"2";s:4:"y_cu";b:0;s:4:"y_co";b:0;s:5:"y_ldc";s:0:"";s:4:"y_sl";s:0:"";}');
    }

    public function getContent()
    {
        if (Tools::isSubmit('submit')) {
            $this->_settings['y_sn'] = Tools::getValue('y_sn');
            $this->_settings['y_fn'] = Tools::getValue('y_fn');
            $this->_settings['y_gz'] = Tools::getValue('y_gz');
            $this->_settings['y_tv'] = Tools::getValue('y_tv');
            $this->_settings['y_dl'] = Tools::getValue('y_dl');
            $this->_settings['y_av'] = Tools::getValue('y_av');
            $this->_settings['y_cu'] = Tools::getValue('y_cu');
            $this->_settings['y_co'] = Tools::getValue('y_co');
            $this->_settings['y_ldc'] = Tools::getValue('y_ldc');
            $this->_settings['y_sl'] = Tools::getValue('y_sl');
            Configuration::updateValue($this->name . '_s', serialize($this->_settings));
            $this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="' . $this->l('Confirmation') . '" />' . $this->l('Настройки обновлены') . '</div>';
        } elseif (Tools::isSubmit('generate')) {
            $this->generate();
            $this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="' . $this->l('Confirmation') . '" />' . $this->l('Файл создан') . '</div>';
        }

        $this->_displayForm();

        return $this->_html;
    }

    private function _displayForm()
    {
        $this->_html .= '
		<form style="float:right; width:200px; margin:15px; text-align:center;">
			<fieldset>
				<a href="http://prestalab.ru/"><img src="http://prestalab.ru/upload/banner.png" alt="Модули и шаблоны для PrestaShop" width="174px" height="100px" /></a>
			</fieldset>
		</form>
		<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
			<fieldset>
				<legend><img src="' . $this->_path . 'logo.gif" alt="" title="" />' . $this->l('Настройки') . '</legend>
				<label>' . $this->l('Название магазина') . '</label>
				<div class="margin-form">
					<input type="text" name="y_sn" value="' . $this->_settings['y_sn'] . '" />
					<p class="clear"> </p>
				</div>
				<label>' . $this->l('Название фирмы') . '</label>
				<div class="margin-form">
					<input type="text" name="y_fn" value="' . $this->_settings['y_fn'] . '" />
					<p class="clear"> </p>
				</div>
				<label>' . $this->l('Стоимость доставки для своего региона') . '</label>
				<div class="margin-form">
					<input type="text" name="y_ldc" value="' . $this->_settings['y_ldc'] . '" />
					<p class="clear"> </p>
				</div>
				<label>' . $this->l('Информация') . '</label>
				<div class="margin-form">
					<input type="text" name="y_sl" value="' . $this->_settings['y_sl'] . '" />
					<p class="clear">' . $this->l('Информации о минимальной сумме заказа, минимальной партии товара или необходимости предоплаты') . '</p>
				</div>
				<label>' . $this->l('Сжимать файл алгоритмом gz') . '</label>
				<div class="margin-form">
					<input type="checkbox" name="y_gz" value="1" ' . (Tools::getValue('y_gz', $this->_settings['y_gz']) ? 'checked="checked" ' : '') . '/>
					<p class="clear"> </p>
				</div>
				<label>' . $this->l('Выгружать товары') . '</label>
				<div class="margin-form">
					<input type="radio" name="y_tv" value="0" ' . (Tools::getValue('y_tv', $this->_settings['y_tv']) == 0 ? 'checked="checked" ' : '') . ' disabled="disabled" />
					<label class="t">' . $this->l('Все') . '</label>
					<input type="radio" name="y_tv" value="1" ' . (Tools::getValue('y_tv', $this->_settings['y_tv']) == 1 ? 'checked="checked" ' : '') . ' disabled="disabled" />
					<label class="t">' . $this->l('Выделенные') . '</label>
					<input type="radio" name="y_tv" value="2" ' . (Tools::getValue('y_tv', $this->_settings['y_tv']) == 2 ? 'checked="checked" ' : '') . ' disabled="disabled" />
					<label class="t">' . $this->l('За искл. выделенных') . '</label>
					<p class="clear"> </p>
				</div>
				<label>' . $this->l('Комбинации') . '</label>
				<div class="margin-form">
					<input type="checkbox" name="y_co" value="1" ' . (Tools::getValue('y_co', $this->_settings['y_co']) ? 'checked="checked" ' : '') . ' disabled="disabled" />
					<label class="t">' . $this->l('Выгружать комбинации товаров') . '</label>
					<p class="clear"> </p>
				</div>
				<label>' . $this->l('Доставка') . '</label>
				<div class="margin-form">
					<input type="checkbox" name="y_dl" value="1" ' . (Tools::getValue('y_dl', $this->_settings['y_dl']) ? 'checked="checked" ' : '') . '/>
					<label class="t">' . $this->l('Если не отмечено, то самовывоз') . '</label>
					<p class="clear"> </p>
				</div>
				<label>' . $this->l('Валюты') . '</label>
				<div class="margin-form">
					<input type="checkbox" name="y_cu" value="1" ' . (Tools::getValue('y_cu', $this->_settings['y_cu']) ? 'checked="checked" ' : '') . '/>
					<label class="t">' . $this->l('Выгружать все валюты') . '</label>
					<p class="clear">Иначе будет выгружена только валюта по умолчанию</p>
				</div>
				<label>' . $this->l('Доступность товара') . '</label>
				<div class="margin-form">
          <p><input type="radio" name="y_av" value="0" ' . (Tools::getValue('y_av', $this->_settings['y_av']) == 0 ? 'checked="checked" ' : '') . '/>
					<label class="t">' . $this->l('По умолчанию все товары в наличии') . '</label></p>
					<p><input type="radio" name="y_av" value="1" ' . (Tools::getValue('y_av', $this->_settings['y_av']) == 1 ? 'checked="checked" ' : '') . '/>
					<label class="t">' . $this->l('При количестве больше 0 - в наличии, остальные под заказ') . '</label></p>
					<p><input type="radio" name="y_av" value="2" ' . (Tools::getValue('y_av', $this->_settings['y_av']) == 2 ? 'checked="checked" ' : '') . '/>
					<label class="t">' . $this->l('При количестве равном 0 не выгружать') . '</label></p>
					<p><input type="radio" name="y_av" value="3" ' . (Tools::getValue('y_av', $this->_settings['y_av']) == 3 ? 'checked="checked" ' : '') . '/>
					<label class="t">' . $this->l('Все под заказ') . '</label></p>
					<p class="clear"> </p>
				</div>
				<center><input type="submit" name="submit" value="' . $this->l('Обновить') . '" class="button" /></center>
			</fieldset>
		</form>
		<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
			<fieldset>
				<legend><img src="' . $this->_path . 'logo.gif" alt="" title="" />' . $this->l('Генерация') . '</legend>
				<label>' . $this->l('Ссылка на статический файл:') . '</label>
				<div class="margin-form">
					<p>http://' . Tools::getHttpHost(false, true) . __PS_BASE_URI__ . 'upload/yml.xml' . (Tools::getValue('y_gz', $this->_settings['y_gz']) ? '.gz' : '') . '</p>
					<p class="clear"> </p>
				</div>
				<label>' . $this->l('Ссылка на динамический файл:') . '</label>
				<div class="margin-form">
					<p>http://' . Tools::getHttpHost(false, true) . __PS_BASE_URI__ . 'modules/yamarket/yml.php</p>
					<p class="clear"> </p>
				</div>
				<label>' . $this->l('Ссылка для запуска через cron:') . '</label>
				<div class="margin-form">
					<p>http://' . Tools::getHttpHost(false, true) . __PS_BASE_URI__ . 'modules/yamarket/yml.php?cron=1</p>
					<p class="clear"> </p>
				</div>
				<center><input type="submit" name="generate" value="' . $this->l('Генерировать вручную') . '" class="button" /></center>
			</fieldset>
		</form>';
    }

    public static function getCover($id_product, $id_product_attribute = false)
    {
        if ($id_product_attribute)
            $result = Db::getInstance()->getValue('
      SELECT `id_image`
      FROM `' . _DB_PREFIX_ . 'product_attribute_image`
      WHERE `id_product_attribute` = ' . intval($id_product_attribute));
        else
            $result = false;

        if (!$result)
            if (!$result = Db::getInstance()->getValue('
      SELECT `id_image`
      FROM `' . _DB_PREFIX_ . 'image`
      WHERE `id_product` = ' . intval($id_product) . '
      AND `cover` = 1')
            )
                return false;

        return $id_product . '-' . $result;
    }

    public static function getProducts($id_lang)
    {
        //return Product::getProducts($id_lang, 0, 100000, 'name' , 'asc');
        return Db::getInstance()->query('
    SELECT p.id_product,p.id_category_default, pl.name, pl.description, p.price, p.quantity, pl.link_rewrite
    FROM `' . _DB_PREFIX_ . 'product` p, `' . _DB_PREFIX_ . 'product_lang` pl
    WHERE p.id_product=pl.id_product AND pl.id_lang=' . $id_lang . ' AND p.active=1'
        );
    }

    public function generate($to_file = true)
    {
        $link = new Link();
        include_once 'YMarket.class.php';

//Язык по умолчанию
        $id_lang = intval(Configuration::get('PS_LANG_DEFAULT'));
//Валюта по умолчанию
        $curr_def = new Currency(intval(Configuration::get('PS_CURRENCY_DEFAULT')));

//создаем новый магазин
        $market = new YMarket(($this->_settings['y_sn']), ($this->_settings['y_fn']), 'http://' . Tools::getHttpHost(false, true), $this->_settings['y_ldc']);

//Валюты
        if ($this->_settings['y_cu']) {
            $currencies = Currency::getCurrencies();

            foreach ($currencies as $currency) {
                $market->add(new yCurrency(($currency['iso_code']), floatval($currency['conversion_rate'])));
            }
            unset($currencies);
        } else {
            $market->add(new yCurrency($curr_def->iso_code, floatval($curr_def->conversion_rate)));
        }

//Категории

        $categories = Category::getCategories($id_lang, false, false);
        foreach ($categories as $category) {
            $catdesc = $category['meta_title'] ? $category['meta_title'] : ($category['name']);
            $market->add(new yCategory($category['id_category'], $catdesc, $category['id_parent']));
        }
        unset($categories);
//Продукты

        $products = self::getProducts($id_lang);

        while ($product = Db::getInstance()->nextRow($products)) {

            $tmp = new yOffer($product['id_product'], ($product['name']), Product::getPriceStatic($product['id_product'], $usetax = true, NULL, $decimals = 2, $divisor = NULL, $only_reduc = false, $usereduc = true, $quantity = 1, $forceAssociatedTax = true));
            $tmp->id = $product['id_product'];
            $tmp->type = '';
            $tmp->sales_notes = $this->_settings['y_sl'];
            $tmp->url = $link->getProductLink((int)$product['id_product'], $product['link_rewrite']);
            //Картинка
            if ($cover = self::getCover($product['id_product']))
                $tmp->picture = $link->getImageLink($product['link_rewrite'], $cover);
            $tmp->currencyId = ($curr_def->iso_code);
            $tmp->categoryId = $product['id_category_default'];
            //$tmp->vendorCode = $product['reference'];
            $tmp->description = ($product['description']);
            if ($this->_settings['y_dl'])
                $tmp->delivery = 'true';
            else
                $tmp->delivery = 'false';

            switch ($this->_settings['y_av']) {
                case 1:
                    $tmp->available = ($product['quantity'] == 0 ? 'false' : 'true');
                    break;
                case 3:
                    $tmp->available = 'false';
                    break;
                default:
                    $tmp->available = 'true';
            }
            //$tmp->barcode = $product['ean13'];
            if (ProductDownload::getIdFromIdProduct($product['id_product']))
                $tmp->downloadable = 'true';
            if (!($this->_settings['y_av'] == 2 AND $product['quantity'] == 0))
                $market->add($tmp);
        }


        if ($to_file) {
            $fp = fopen(dirname(__FILE__) . '/../../upload/yml.xml' . ($this->_settings['y_gz'] ? '.gz' : ''), 'w');
            fwrite($fp, $market->generate(false, $this->_settings['y_gz']));
            fclose($fp);
        } else {
            $market->generate(true, $this->_settings['y_gz']);
        }
    }

}

?>