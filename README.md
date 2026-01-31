# OpenCart 3+ Payment Module / Модуль оплаты OpenCart 3+

---

## 🇷🇺 Русский

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

# OpenCart 3+ Payment Module

## Installation
* Create a backup of your store and database
* Upload the module file `opencart-31_assetpayments_advance_1.ocmod.zip` using **Extension → Installer**
* Enable the **AssetPayments** module in payment extensions (**Extensions → Payments**)
* Configure the module settings:
  * **Merchant ID** — merchant public key
  * **Secret Key** — merchant secret key
  * **Template ID** — payment template ID
  * **Method title** — payment method title displayed in the cart
  * **Processing ID** — processing ID
  * **SkipCheckout** — skip test page
  * **Advance payment** — advance payment (amount can be specified as `200` or `20%`)
  * **Advance title** — product name used for advance payment
  * **Total** — minimum order amount required to use this payment method
  * **Order status** — order status after successful payment
  * **GeoZone** — regions from which payment is allowed
  * **Status** — enable/disable the extension
  * **SortOrder** — display order in the cart

## Notes
Developed and tested with **OpenCart v3+**

## Installation Issues
An alternative installation method is to upload the contents of the `upload` folder to the root directory where **OpenCart** is installed.

## Force Language Setup
In the controller `catalog/controller/extension/payment/assetpayments.php`, add the following code to `public function index()`:

```php
$results = $this->model_localisation_language->getLanguages();

print($results);

$this->config->set('config_language_id', 3);

$this->session->data['language'] = 'uk-ua';

### Recalculation to the Current Currency When Using the Base Currency
In the controller catalog/controller/extension/payment/assetpayments.php, add the following code to public function index():

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
