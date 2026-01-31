# OpenCart 3+ Payment Module / Модуль оплаты OpenCart 3+

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

