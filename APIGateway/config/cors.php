<?php 
// Ref: https://galib-rabib.medium.com/how-to-fix-cors-error-in-lumen-api-services-ba5a62d23507
return [    
   'paths' => ['*'],
   'allowed_methods' => ['*'],
   'allowed_origins' => ['*'],
   'allowed_origins_patterns' => [],
   'allowed_headers' => ['*'],
   'exposed_headers' => [],
   'max_age' => 0,
   'supports_credentials' => false,
];
