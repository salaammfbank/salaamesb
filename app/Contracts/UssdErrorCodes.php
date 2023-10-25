<?php
namespace Noorfarooqy\SalaamEsb\Contracts;
enum UssdErrorCodes :string
{
    case ussd_e_000 = 'Fatal error! Please contact admin for support.';
    case ussd_e_001 = 'Invalid Access Token';
    case ussd_e_002 = 'Access token expired';
    case ussd_e_003 = 'Missing field in the request';
    case ussd_e_005 = 'Invalid request body';
    case ussd_e_006 = 'Account is not active';
    case ussd_e_007 = 'System verification has failed. Ensure account system status is set correctly';
    case ussd_e_008 = 'Request cannot be carried out now. Try again after few minutes';
    case ussd_e_009 = 'Transaction failed';
    case ussd_e_010 = 'Parsed xml empty';
    case ussd_e_011 = 'Transaction verification and update exception';
    case ussd_e_012 = 'Transaction request has failed.';

    case ussd_c_004 = 'CBS Related Error ';
}
