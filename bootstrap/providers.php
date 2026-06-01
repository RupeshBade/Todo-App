<?php

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    Intern\JwtConverter\JwtConverterServiceProvider::class,
    Rupes\JetConverter\JetConverterServiceProvider::class,
];