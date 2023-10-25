<?php

namespace Noorfarooqy\SalaamEsb\Contracts;

enum ErrorCodes: string
{
    case request_error = 'Can confirm request parameters exist and are valid';
    case duplicate_sacc = 'Duplicate settlement accounts';
    case sacc_missing = 'The settlement accounts are not specified';
    case daily_limit = 'Transaction daily limit rule';
    case i365_request_failed = 'Request failed for i365';

    case incident_validation_failed = 'Incident validation failed';
    case incident_creation_failed = 'Incident creation failed';
    case incident_update_failed = 'Incident update failed';

    case incident_category_validation_failed = 'Incident category validation failed';
    case incident_category_creation_failed = 'Incident category creation failed';

    case customer_acccount_validation_failed = 'Customer account validation failed';

    case failed_http_request = 'HTTP request failed';

    case sch_bank_account_required = 'Bank account required';
    case sch_bank_account_not_found = 'Bank account not found';
    case sch_bank_account_not_active = 'Bank account not active';
    case sch_bank_account_deposit_failed = 'Bank account deposit failed';
    case sch_transaction_not_found = 'Transaction not found';
    case sch_bank_deposit_data_entry_error = 'Bank deposit data entry error';
    case sch_transfer_currency_rate_error = 'SCH transfer currency rate error';
}
