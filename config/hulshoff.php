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
    'copy_of_order_confirmation' => ['planning2@hulshoff.nl'],
];