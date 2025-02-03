<?php

namespace App\Constants;

class Status
{

    const ENABLE  = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO  = 0;

    const VERIFIED   = 1;
    const UNVERIFIED = 0;

    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS  = 1;
    const PAYMENT_PENDING  = 2;
    const PAYMENT_REJECT   = 3;

    const TICKET_OPEN   = 0;
    const TICKET_ANSWER = 1;
    const TICKET_REPLY  = 2;
    const TICKET_CLOSE  = 3;

    const PRIORITY_LOW    = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH   = 3;

    const USER_ACTIVE = 1;
    const USER_BAN    = 0;

    const KYC_UNVERIFIED = 0;
    const KYC_PENDING    = 2;
    const KYC_VERIFIED   = 1;

    const GOOGLE_PAY = 5001;

    const CUR_BOTH = 1;
    const CUR_TEXT = 2;
    const CUR_SYM  = 3;

    const CRYPTO_CURRENCY = 1;
    const FIAT_CURRENCY   = 2;

    const BUY_SIDE_ORDER  = 1;
    const SELL_SIDE_ORDER = 2;

    const BUY_SIDE_TRADE  = 1;
    const SELL_SIDE_TRADE = 2;

    const ORDER_TYPE_LIMIT  = 1;
    const ORDER_TYPE_MARKET = 2;

    const ORDER_OPEN      = 0;
    const ORDER_COMPLETED = 1;
    const ORDER_CANCELED  = 9;

    const PLAN_HISTORY_RUNNING   = 1;
    const PLAN_HISTORY_FAILED    = 2;
    const PLAN_HISTORY_COMPLETED = 3;
    const PLAN_HISTORY_EXPIRED   = 4;

    const LOGIC_EQUAL              = 1;
    const LOGIC_GREATER_THAN       = 2;
    const LOGIC_GREATER_THAN_EQUAL = 3;
    const LOGIC_LESS_THAN          = 4;
    const LOGIC_LESS_THAN_EQUAL    = 5;
    const LOGIC_IN_RANGE           = 6;

    const LOGIC_BOX_TYPE_PROFIT = 1;
    const LOGIC_BOX_TYPE_LOSS   = 2;

    const USER_PHASE_SUCCESS = 1;
    const USER_PHASE_FAILED  = 2;

}
