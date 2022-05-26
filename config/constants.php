<?php

$constant = [];

$constant['DAYNUMBERS_OF_WEEK'] = [
    'sun' => 6,
    'mon' => 0,
    'tue' => 1,
    'wed' => 2,
    'thu' => 3,
    'fri' => 4,
    'sat' => 5,
];

$constant['SORTABLES'] = [
    'id' => 'id', 'email' => 'email', 'phone' => 'phone',
];

$constant['PATIENT_TYPE'] = [
    'BOTH' => 'BOTH', 'MEDICAL' => 'MEDICAL', 'RECREATIONAL' => 'RECREATIONAL'
];

$constant['ExportFilePrefix'] = 'NewDrop_';

$constant['DEFAULT_LEGAL'] = [
    [
        'name' => 'Terms of Service',
        'page_code' => 'terms-service',
        'group' => 'TERM',
        'html_content' => '<p><strong>Terms of Service</strong></p><p>LAST UPDATED: MARCH 13, 2019</p><p>PLEASE READ THIS AGREEMENT CAREFULLY BEFORE USING THIS SERVICE.&nbsp;</p><p>BY USING THE SERVICE OR CLICKING AGREE YOU ARE AGREEING TO BE BOUND BY THIS AGREEMENT. IF YOU ARE AGREEING TO THIS AGREEMENT ON BEHALF OF OR FOR THE BENEFIT OF YOUR EMPLOYER OR AN ENTITY FOR WHOSE BENEFIT THIS SERVICE IS BEING USED, THEN YOU REPRESENT AND WARRANT THAT YOU HAVE THE NECESSARY AUTHORITY TO AGREE TO THIS AGREEMENT ON THEIR BEHALF. IF YOU HAVE A WRITTEN AGREEMENT WITH DROP FOR THESE SERVICES, THEN THAT AGREEMENT WILL GOVERN, AND THE AGREEMENT BELOW WILL NOT APPLY.</p><p>This agreement is between Drop Technologies Inc., a California corporation (Drop), and the customer agreeing to this agreement (Customer).</p>',
        'priority' => 1,
        'sub_id' => 0,
    ],
    [
        'name' => 'Privacy Policy',
        'page_code' => 'privacy-policy',
        'group' => 'POLICY',
        'html_content' => '<p><strong>Privacy Policy</strong></p><p>Effective date: January 1, 2020</p><p>Last updated: January 23, 2020</p><p><strong>Introduction</strong></p><p>Drop Technologies, Inc. (“Drop”) understands and respects our users’ need for privacy. This Privacy Notice (“Notice”) describes the types of information we collect, the purposes for which it is used, and the choices you have with respect to its use.</p><p><strong>About Drop</strong></p><p>Drop is a delivery management software for Cannabis businesses. You can access the Drop service (“Service” or “Services”) via our website dashboard, smartphone applications, SMS messaging feature on mobile devices, APIs, and through third-parties. For more information about our Services, check out our “<a data-cke-saved-href="https://dropdelivery.com/index.html#section-products" href="https://dropdelivery.com/index.html#section-products">Features</a>” section on our website.</p>',
        'priority' => 2,
        'sub_id' => 0,
    ],
];

$constant['REDIRECT_TYPE'] = [
    'category' => 'Category',
    'menu' => 'Menu',
    'deal' => 'Deal',
    'deal-menu' => 'Deal Menu',
    'reward-menu' => 'Reward Menu',
    'brand' => 'Brand',
    'product' => 'Product',
    'refer-friend' => 'Refer a Friend',
    'shop-info' => 'Shop Info',
    'no-redirection' => 'do not redirect (hide shop now button)',
];

return $constant;
