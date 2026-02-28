<?php

return [
    App\Providers\AppServiceProvider::class,
    // App\Providers\AuthServiceProvider::class, pour éviter les problèmes de cache lors du développement
   App\Providers\TenancyServiceProvider::class, // <-- here

];
