# OpenCart 3+ Payment Extension With Advance

---

### Установка
* Создайте резервную копию вашего магазина и базы данных
* Загрузите файл модуля `opencart-31_assetpayments_advance_1.ocmod.zip` с помощью **Extension → Installer**
* Активируйте модуль **AssetPayments** в модулях оплаты (**Extensions → Payments**)
* Задайте в настройках модуля:
  * **Merchant ID** — публичный ключ мерчанта
  * **Secret Key** — секретный ключ мерчанта
  * **Template ID** — ID платёжного шаблона
  * **Method title** — название метода в корзине
  * **Processing ID** — ID процессинга
  * **SkipCheckout** — пропуск тестовой страницы
  * **Advance payment** — оплата аванса (сумма указывается как `200` или `20%`)
  * **Advance title** — наименование товара при авансовом платеже
  * **Total** — минимальная сумма, при которой можно использовать метод
  * **Order status** — статус заказа после оплаты
  * **GeoZone** — регионы, из которых можно выполнять оплату
  * **Status** — вкл./выкл. расширение
  * **SortOrder** — порядок отображения в корзине

### Примечания
Разработано и протестировано с **OpenCart v3+**

### Проблемы при установке
Альтернативный вариант — загрузить на сервер содержимое папки `upload` в корневую директорию, где установлена **OpenCart**

### Принудительная установка языка
В контроллере `catalog/controller/extension/payment/assetpayments.php` добавить в `public function index()`:

```php
$results = $this->model_localisation_language->getLanguages();

print($results);

$this->config->set('config_language_id', 3);

$this->session->data['language'] = 'uk-ua';

### Пересчёт в текущую валюту при использовании базовой
В контроллере catalog/controller/extension/payment/assetpayments.php добавить в public function index():

$updatedPrice = $this->currency->format(
    $product['price'],
    $order_info['currency_code'],
    $order_info['currency_value'],
    false
);

'Amount' => $this->currency->format(
    $order_info['total'],
    $order_info['currency_code'],
    $order_info['currency_value'],
    false
),
---

### Принудительный выбор UAH если цены указаны в USD/EUR
В контроллере `catalog/controller/extension/payment/assetpayments.php` добавить в private function prepare_payload

$uah_rate = $this->currency->getValue('UAH');

В массив товаров
$unit_price = ($product['price'] + $product['tax']);
$unit_price_uah = $unit_price * $uah_rate;

'ProductPrice'    => number_format((float)$unit_price_uah, 2, '.', '')

В массив доставки
$shipping_uah = $shipping_total * $uah_rate;

'ProductPrice'    => number_format((float)$shipping_uah, 2, '.', '')

В переменные запроса
'Amount'         => number_format((float)($order_info['total'] * $uah_rate), 2, '.', ''),
'Currency'       => 'UAH',
'Discount'       => number_format((float)($discount_total * $uah_rate), 2, '.', ''),

