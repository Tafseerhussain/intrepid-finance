<?php

class Lexicon
{
    public static function admins_status()
        : array
    {
        return [
            'Active'   => 'Active',
            'Disabled' => 'Disabled',
        ];
    }

    public static function admins_access_level()
        : array
    {
        return [
            '1' => 'Level 1: View, Add',
            '2' => 'Level 2: View, Add, Edit, Delete',
            '3' => 'Level 3: View, Add, Edit, Delete, Admins',
        ];
    }

    public static function users_form_type()
        : array
    {
        return [
            'commercial_capital' => 'Commercial Capital',
            'venture_capital'    => 'Growth & Venture Capital',
        ];
    }

    public static function users_request_type(?string $formType)
        : array
    {
        if ('commercial_capital' === $formType) {
            return [
                'equipment'           => 'Equipment Financing',
                'invoice_factoring'   => 'Invoice Factoring',
                'accounts_receivable' => 'Accounts Receivable Financing',
                'lines_of_credit'     => 'Lines of Credit',
            ];
        }

        if ('venture_capital' === $formType) {
            return [
                'growth_capital'  => 'Growth Capital',
                'venture_capital' => 'Venture Capital',
            ];
        }

        return [
            'equipment'           => 'Equipment Financing',
            'invoice_factoring'   => 'Invoice Factoring',
            'accounts_receivable' => 'Accounts Receivable Financing',
            'lines_of_credit'     => 'Lines of Credit',
            'growth_capital'      => 'Growth Capital',
            'venture_capital'     => 'Venture Capital',
        ];
    }

    public static function users_corp_type()
        : array
    {
        return [
            'llc'    => 'LLC',
            's_corp' => 'S Corp',
            'c_corp' => 'C Corp',
        ];
    }

    public static function users_credit_score()
        : array
    {
        return [
            '720+'    => '720+',
            '720-625' => '720-625',
            '625-550' => '625-550',
            '550-'    => '550 and below',
            'unknown' => 'Unknown',
        ];
    }

    public static function users_status()
        : array
    {
        return [
            'Abandoned'  => 'Abandoned',
            'Prospect'   => 'Prospect',
            'Started'    => 'Started',
            'Completed'  => 'Completed',
            'Working'    => 'Working',
            'Outsourced' => 'Outsourced',
            'Funded'     => 'Funded',
            'Declined'   => 'Declined',
            'Archive'    => 'Archive',
            'Inactive'   => 'Inactive',
        ];
    }
}
