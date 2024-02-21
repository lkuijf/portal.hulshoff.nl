<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hulshoff webportal specific configurations
    |--------------------------------------------------------------------------
    |
    */

    // 'privileges' => ['show_tiles', 'filter_on_top', 'filter_at_side', 'free_search', 'lotcode_search'],
    'privileges' => ['show_tiles', 'filter_on_top', 'filter_at_side'],
    'archiveXmlWhenOlderThanXDays' => 7,
    'OrdersOutEndpoint' => 'https://edi.hulshoff.nl/api/accept/interface-wti/BerichtOrders',
    // 'copy_of_order_confirmation' => ['planning2@hulshoff.nl'],
    'copy_of_order_confirmation' => ['leon@wtmedia-events.nl', 'planning2@hulshoff.nl'],

    // Dutch is leading, so translating via localization file was not an option.
    'productgroup_translations' => [
        "AANKLEDING" => "Decoration",
        "ALGEMEEN" => "General",
        "APPARATUUR" => "Devices",
        "BOUWMATERIALEN" => "Construction material",
        "KANTOORARTIKELEN" => "Office Supplies",
        "KAST" => "Cabinet",
        "KUNST" => "Art",
        "MEUBILAIR" => "Furniture",
        "MUSEUMGOEDEREN" => "Museum Supplies",
        "ONBEKEND" => "Unknown",
        "ONDERDELEN" => "Components",
        "OVERIG" => "Miscellaneous",
        "PRESENTATIE ARTIKELEN" => "Presentation material",
        "RELATIEGESCHENKEN" => "Promotion gifts",
        "TAFEL" => "Tables",
        "TELEFONIE" => "Telephony",
        "VERGADERMEUBILAIR" => "Meetingroom furniture",
        "VERLICHTING" => "Lighting",
        "VLOERBEDEKKING" => "Flooring",
        "WERKPLEK" => "Work Stations",
        "ZITMEUBILAIR" => "Seating",

        "BEELDSCHERM" => "Monitor",


    ],
];