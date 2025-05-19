<?php

namespace Foodboard;

class Config
{

    const APP_ROOT = 'localhost';

    const WORK_ROOT = '/projek-umkm/';

    const THANKYOU_URL = Config::WORK_ROOT . '/pay-with-cash-on-delivery/thank-you.php';

    const CANCEL_URL = Config::WORK_ROOT . '/pay-with-cash-on-delivery/index.php';

    const ORDER_EMAIL_SUBJECT = 'Order Confirmation';

    const CURRENCY = 'IDR';

    const CURRENCY_SYMBOL = 'Rp';
}
