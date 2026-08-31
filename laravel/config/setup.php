<?php

return [
    'enabled' => (bool) env('INSTALL_ENABLED', false),
    'token' => (string) env('INSTALL_TOKEN', ''),
    'env_path' => base_path('.env'),
];
