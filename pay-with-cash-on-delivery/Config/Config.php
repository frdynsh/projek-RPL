<?php

namespace Foodboard;

class Config
{

    const APP_ROOT = 'https://ultimatewebsolutions.net';

    const WORK_ROOT = '/projek-umkm/';

    const THANKYOU_URL = Config::WORK_ROOT . '/pay-with-cash-on-delivery/thank-you.php';

    const CANCEL_URL = Config::WORK_ROOT . '/pay-with-cash-on-delivery/index.php';

    const ORDER_EMAIL_SUBJECT = 'Order Confirmation';

    const CURRENCY = 'USD';

    const CURRENCY_SYMBOL = '$';
}
