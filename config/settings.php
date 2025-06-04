<?php

use BrucePull\SettingsPackage\Enums\SettingLevel;
use BrucePull\SettingsPackage\Enums\SettingType;

return [
    /*
    |--------------------------------------------------------------------------
    | Settings Cache Duration
    |--------------------------------------------------------------------------
    |
    | This value determines how long (in seconds) the application should cache
    | the settings values. You may override this value by setting the
    | SETTINGS_CACHE_DURATION environment variable in your .env file.
    |
    | A value of 0 will disable caching completely.
    |
    */
    'cache_duration' => env('SETTINGS_CACHE_DURATION', 3600),

    /*
    |--------------------------------------------------------------------------
    | Default Application Settings
    |--------------------------------------------------------------------------
    |
    | Here you may define all default settings for your application. Each
    | setting is defined as an array with its configuration including:
    | - Key: The unique identifier for the setting
    | - Name: Human-readable display name
    | - Type: Data type (using SettingType enum)
    | - Default value: Initial value if not set
    | - Levels: Where this setting can be configured (using SettingLevel enum)
    | - Validation: Laravel validation rules
    | - Group: Hierarchical organization path
    |
    */
    'default_settings' => [
        /* 
            Company -> Company settings
        */

        // Company name
        'company-name' => [
            'key' => 'company-name',
            'name' => 'Company name',
            'description' => 'Company name',
            'type' => SettingType::STRING,
            'default_value' => 'Company name',
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'required|string|max:255',
            'group_path' => [
                'Company',
                'Company settings'
            ],
        ],

        // Logo
        'logo' => [
            'key' => 'logo',
            'name' => 'Logo',
            'description' => 'Logo',
            'type' => SettingType::STRING,
            'default_value' => '',
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'required|string|max:255',
            'group_path' => [
                'Company',
                'Company settings'
            ],
        ],

        // Address I
        'address-1' => [
            'key' => 'address-1',
            'name' => 'Address 1',
            'description' => 'Address 1',
            'type' => SettingType::STRING,
            'default_value' => null,
            'levels' => [
                SettingLevel::SYSTEM,
            ],
            'validation' => 'nullable|string|max:255',
            'group_path' => [
                'Company',
                'Company settings'
            ],
        ],

        // Address II
        'address-2' => [
            'key' => 'address-2',
            'name' => 'Address 2',
            'description' => 'Address 2',
            'type' => SettingType::STRING,
            'default_value' => null,
            'levels' => [
                SettingLevel::SYSTEM,
            ],
            'validation' => 'nullable|string|max:255',
            'group_path' => [
                'Company',
                'Company settings'
            ],
        ],

        // Phone number 
        'phone_number' => [
            'key' => 'phone_number',
            'name' => 'Phone number',
            'description' => 'Phone number',
            'type' => SettingType::STRING,
            'default_value' => null,
            'levels' => [
                SettingLevel::SYSTEM,
            ],
            'validation' => 'nullable|string|max:255',
            'group_path' => [
                'Company',
                'Company settings'
            ],
        ],

        // Email
        'email' => [
            'key' => 'email',
            'name' => 'Email',
            'description' => 'Email',
            'type' => SettingType::STRING,
            'default_value' => null,
            'levels' => [
                SettingLevel::SYSTEM,
            ],
            'validation' => 'nullable|email|max:255',
            'group_path' => [
                'Company',
                'Company settings'
            ],
        ],

        // Primary color 
        'primary-color' => [
            'key' => 'primary-color',
            'name' => 'Primary color',
            'description' => 'Primary color',
            'type' => SettingType::STRING,
            'default_value' => '#6366f1',
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'required|string|max:255',
            'group_path' => [
                'Company',
                'Company settings'
            ],
        ],

        // Secondary color 
        'secondary-color' => [
            'key' => 'secondary-color',
            'name' => 'Secondary color',
            'description' => 'Secondary color',
            'type' => SettingType::STRING,
            'default_value' => '#0A0B17',
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'required|string|max:255',
            'group_path' => [
                'Company',
                'Company settings'
            ],
        ],

        /** 
         * Login -> Login settings
         */

        // Enabled captcha security
        'enabled-captcha-security' => [
            'key' => 'enabled-captcha-security',
            'name' => 'Enabled Captcha Security',
            'description' => 'Enabled Captcha Security',
            'type' => SettingType::BOOLEAN,
            'default_value' => false,
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'nullable|boolean',
            'group_path' => [
                'Login',
                'Login settings'
            ],
        ],

        // Forze secure password
        'forze-secure-password' => [
            'key' => 'forze-secure-password',
            'name' => 'Forze secure password',
            'description' => 'Forze secure password',
            'type' => SettingType::BOOLEAN,
            'default_value' => false,
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'nullable|boolean',
            'group_path' => [
                'Login',
                'Login settings'
            ],
        ],

        /** 
         * User -> System
         */

        // Language
        'language' => [
            'key' => 'language',
            'name' => 'Idioma',
            'description' => 'Idioma de la aplicación',
            'type' => SettingType::STRING,
            'default_value' => 'en',
            'is_resettable' => true,
            'levels' => [
                SettingLevel::SYSTEM,
                SettingLevel::FACILITY,
                SettingLevel::USER
            ],
            'validation' => 'in:en,es,fr,de,pt',
            'group_path' => [
                'User',
                'System'
            ],
            "collection_values" => [
                [
                    "id" => "EN",
                    "value" => "English"
                ],
                [
                    "id" => "ES",
                    "value" => "Spanish"
                ]
            ]
        ],

        // Timezone
        'timezone' => [
            'key' => 'timezone',
            'name' => 'Time zone',
            'description' => 'Zona horaria por defecto',
            'type' => SettingType::STRING,
            'default_value' => 'UTC',
            'levels' => [
                SettingLevel::SYSTEM,
                SettingLevel::FACILITY,
                SettingLevel::USER
            ],
            'validation' => 'timezone',
            'group_path' => [
                'User',
                'System'
            ],
            "collection_values" => [
                [
                    "id" => "UTC",
                    "value" => "UTC"
                ],
                [
                    "id" => "America\/La_Paz",
                    "value" => "America\/La_Paz"
                ],
                [
                    "id" => "US\/Arizona",
                    "value" => "US\/Arizona"
                ]
            ],
        ],

        /**
         * User -> Number format
         */

        // Thousands separator
        'thousands-separator' => [
            'key' => 'thousands-separator',
            'name' => 'Thousands separator',
            'description' => 'Thousands separator',
            'type' => SettingType::STRING,
            'default_value' => '.',
            'levels' => [
                SettingLevel::SYSTEM,
                SettingLevel::FACILITY,
                SettingLevel::USER
            ],
            'validation' => 'nullable|string,max:1',
            'group_path' => [
                'User',
                'Number format'
            ]
        ],

        // Decimal separator
        'decimal-separator' => [
            'key' => 'decimal-separator',
            'name' => 'Decimal separator',
            'description' => 'Decimal separator',
            'type' => SettingType::STRING,
            'default_value' => ',',
            'levels' => [
                SettingLevel::SYSTEM,
                SettingLevel::FACILITY,
                SettingLevel::USER
            ],
            'validation' => 'nullable|string,max:1',
            'group_path' => [
                'User',
                'Number format'
            ]
        ],

        // Number of decimals
        'number-of-decimals' => [
            'key' => 'number-of-decimals',
            'name' => 'Number of decimals',
            'description' => 'Number of decimals',
            'type' => SettingType::INTEGER,
            'default_value' => 2,
            'levels' => [
                SettingLevel::SYSTEM,
                SettingLevel::FACILITY,
                SettingLevel::USER
            ],
            'validation' => 'integer|min:0',
            'group_path' => [
                'User',
                'Number format'
            ]
        ],

        /**
         * User -> Calendar
         */

        // Start of week
        'start_of_week' => [
            'key' => 'start_of_week',
            'name' => 'Start of the week',
            'description' => 'Start of the week',
            'type' => SettingType::STRING,
            'default_value' => 'monday',
            'levels' => [
                SettingLevel::SYSTEM,
                SettingLevel::FACILITY,
                SettingLevel::USER
            ],
            'validation' => 'in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'group_path' => [
                'User',
                'Calendar'
            ],
            "collection_values" => [
                [
                    "id" => "sunday",
                    "value" => "Sunday"
                ],
                [
                    "id" => "monday",
                    "value" => "Monday"
                ],
                [
                    "id" => "tuesday",
                    "value" => "Tuesday"
                ],
                [
                    "id" => "wednesday",
                    "value" => "Wednesday"
                ],
                [
                    "id" => "thursday",
                    "value" => "Thursday"
                ],
                [
                    "id" => "friday",
                    "value" => "Friday"
                ],
                [
                    "id" => "saturday",
                    "value" => "Saturday"
                ]
            ],
        ],

        /**
         * User -> Tables
         */

        // Rows per page
        'rows_per_page' => [
            'key' => 'rows_per_page',
            'name' => 'Rows per page',
            'description' => 'Rows per page',
            'type' => SettingType::INTEGER,
            'default_value' => 10,
            'levels' => [
                SettingLevel::SYSTEM,
                SettingLevel::FACILITY,
                SettingLevel::USER
            ],
            'validation' => 'integer|min:1',
            'group_path' => [
                'User',
                'Tables'
            ],
        ],

        /**
         * User -> Layout
         */

        // Sidebar width
        'sidebar_width' => [
            'key' => 'sidebar_width',
            'name' => 'Sidebar width',
            'description' => null,
            'type' => SettingType::INTEGER,
            'default_value' => 280,
            'levels' => [
                SettingLevel::SYSTEM,
                SettingLevel::FACILITY,
                SettingLevel::USER
            ],
            'validation' => 'integer|min:1',
            'group_path' => [
                'User',
                'Layout'
            ],
        ],

        /**
         * Notifications -> Notification settings
         */

        // New user is created
        'new-user-is-created' => [
            'key' => 'new-user-is-created',
            'name' => 'New user is created',
            'description' => 'New user is created',
            'type' => SettingType::BOOLEAN,
            'default_value' => false,
            'levels' => [
                SettingLevel::SYSTEM,
                SettingLevel::USER
            ],
            'validation' => 'nullable|boolean',
            'group_path' => [
                'Notifications',
                'Notification settings'
            ],
        ],

        // New item is created
        'new-item-is-created' => [
            'key' => 'new-item-is-created',
            'name' => 'New item is created',
            'description' => 'New item is created',
            'type' => SettingType::BOOLEAN,
            'default_value' => false,
            'levels' => [
                SettingLevel::SYSTEM,
                SettingLevel::USER
            ],
            'validation' => 'nullable|boolean',
            'group_path' => [
                'Notifications',
                'Notification settings'
            ],
        ],

        /**
         * Module options -> Inventory
         */

        // Required bill of materials
        'required-bill-of-materials' => [
            'key' => 'required-bill-of-materials',
            'name' => 'Required bill of materials',
            'description' => 'Required bill of materials',
            'type' => SettingType::BOOLEAN,
            'default_value' => false,
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'nullable|boolean',
            'group_path' => [
                'Module options',
                'Inventory'
            ],
        ],

        // Invoice tax percentage 
        'invoice-tax-percentage' => [
            'key' => 'invoice-tax-percentage',
            'name' => 'Invoice - Tax percentage',
            'description' => 'Invoice - Tax percentage',
            'type' => SettingType::FLOAT,
            'default_value' => 10,
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'nullable|integer|min:0',
            'group_path' => [
                'Module options',
                'Inventory'
            ],
        ],

        // Secondary item max percentage discount 
        'discount-cost-percentage-secondary-item-output' => [
            'key' => 'discount-cost-percentage-secondary-item-output',
            'name' => 'Discount cost percentage - secondary item outputs',
            'description' => 'Discount cost percentage - secondary item outputs',
            'type' => SettingType::FLOAT,
            'default_value' => 10,
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'nullable|decimal:2|min:0',
            'group_path' => [
                'Module options',
                'Inventory'
            ],
        ],

        /**
         * Module options -> Supplies
         */

        // Limit to approve transactions
        'limit-to-approve-transactions' => [
            'key' => 'limit-to-approve-transactions',
            'name' => 'Limit to approve transactions',
            'description' => 'Limit to approve transactions',
            'type' => SettingType::FLOAT,
            'default_value' => 5000,
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'nullable|decimal:2|min:0',
            'group_path' => [
                'Module options',
                'Supplies'
            ],
        ],

        /**
         * Module options -> Transfers
         */

        // Enable google maps 
        'enabled-google-maps' => [
            'key' => 'enabled-google-maps',
            'name' => 'Enabled google maps',
            'description' => 'Enabled google maps',
            'type' => SettingType::BOOLEAN,
            'default_value' => true,
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'nullable|boolean',
            'group_path' => [
                'Module options',
                'Transfers'
            ],
        ],

        /**
         * Module options -> Labor tracker
         */

        // Enable weekends usage
        'enabled-weekend-usage' => [
            'key' => 'enabled-weekend-usage',
            'name' => 'Enabled weekend usage',
            'description' => 'Enabled weekend usage',
            'type' => SettingType::BOOLEAN,
            'default_value' => true,
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'nullable|boolean',
            'group_path' => [
                'Module options',
                'Labor tracker'
            ],
        ],

        // Limit of previous days
        'labor-tracker-limit-of-previous-days' => [
            'key' => 'labor-tracker-limit-of-previous-days',
            'name' => 'Labor tracker - limit of previous days',
            'description' => 'Labor tracker - limit of previous days',
            'type' => SettingType::INTEGER,
            'default_value' => 2,
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'nullable|integer|min:0',
            'group_path' => [
                'Module options',
                'Labor tracker'
            ],
        ],

        // Limit of next days
        'labor-tracker-limit-of-next-days' => [
            'key' => 'labor-tracker-limit-of-next-days',
            'name' => 'Labor tracker - limit of next days',
            'description' => 'Labor tracker - limit of next days',
            'type' => SettingType::INTEGER,
            'default_value' => 2,
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'nullable|integer|min:0',
            'group_path' => [
                'Module options',
                'Labor tracker'
            ],
        ],

        // Holidays
        'holidays' => [
            'key' => 'holidays',
            'name' => 'Holidays',
            'description' => 'Holidays',
            'type' => SettingType::JSON,
            'default_value' => [
                [
                    "description" => "New Year's Day",
                    "day" => "Monday, January 1",
                    "date" => "01/01/2024"
                ],
                [
                    "description" => "Martin Luther King, Jr. Day",
                    "day" => "Monday, January 15",
                    "date" => "01/15/2024"
                ],
                [
                    "description" => "Presidents' Day",
                    "day" => "Monday, February 19",
                    "date" => "02/19/2024"
                ],
                [
                    "description" => "Memorial Day",
                    "day" => "Monday, May 27",
                    "date" => "05/27/2024"
                ],
                [
                    "description" => "Juneteenth National Independence Day",
                    "day" => "Wednesday, June 19",
                    "date" => "06/19/2024"
                ],
                [
                    "description" => "Independence Day - U.S.",
                    "day" => "Thursday, July 4",
                    "date" => "07/04/2024"
                ],
                [
                    "description" => "Labor Day - U.S.",
                    "day" => "Monday, September 2",
                    "date" => "09/02/2024"
                ],
                [
                    "description" => "Columbus Day/Indigenous Peoples' Day",
                    "day" => "Monday, October 14",
                    "date" => "10/14/2024"
                ],
                [
                    "description" => "Thanksgiving Day",
                    "day" => "Thursday, November 28",
                    "date" => "11/28/2024"
                ],
                [
                    "description" => "Thanksgiving Day Break",
                    "day" => "Friday, November 29",
                    "date" => "11/29/2024"
                ],
                [
                    "description" => "Christmas Day",
                    "day" => "Wednesday, December 25",
                    "date" => "12/25/2024"
                ],
                [
                    "day" => "June 23, 2025",
                    "date" => "06/23/2025",
                    "description" => "Test day"
                ]
            ],
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'nullable|array',
            'group_path' => [
                'Module options',
                'Labor tracker'
            ],
        ],

        /**
         * Cost center -> Period reconciliation
         */

        // Period reconciliation close
        'period-reconciliation' => [
            'key' => 'period-reconciliation',
            'name' => 'Period reconciliation',
            'description' => 'Period reconciliation',
            'type' => SettingType::STRING,
            'default_value' => "Monthly",
            'levels' => [
                SettingLevel::SYSTEM
            ],
            'validation' => 'nullable|array',
            'group_path' => [
                'Cost center',
                'Period reconciliation'
            ],
            "collection_values" => [
                [
                    "id" =>  "monthly",
                    "value" => "Monthly"
                ],
                [
                    "id" => "every-other",
                    "value" => "Every other"
                ],
                [
                    "id" => "quarterly",
                    "value" => "Quarterly"
                ],
                [
                    "id" => "every-6-months",
                    "value" => "Every 6 months"
                ],
                [
                    "id" => "yearly",
                    "value" => "Yearly"
                ]
            ],
        ],
    ]
];
