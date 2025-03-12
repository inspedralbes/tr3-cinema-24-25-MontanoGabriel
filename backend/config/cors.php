<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    | Aquí se especifican las rutas a las cuales se aplicarán las cabeceras CORS.
    |
    */
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    |
    | Métodos HTTP permitidos. Puedes poner un arreglo con los métodos específicos
    | o usar ['*'] para permitir todos.
    |
    */
    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | Orígenes permitidos para realizar solicitudes a tu API. Por ejemplo, si tu
    | frontend se ejecuta en localhost:3000, deberías permitirlo.
    |
    */
    'allowed_origins' => ['http://localhost:3000'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins Patterns
    |--------------------------------------------------------------------------
    |
    | Puedes definir patrones para permitir orígenes dinámicos. Deja vacío si no lo necesitas.
    |
    */
    'allowed_origins_patterns' => [],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    |
    | Cabeceras permitidas en las solicitudes.
    |
    */
    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    |
    | Cabeceras que pueden ser expuestas al navegador.
    |
    */
    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    |
    | Tiempo (en segundos) que la respuesta preflight se puede almacenar en caché.
    |
    */
    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    |
    | Indica si las solicitudes pueden incluir credenciales (cookies, encabezados de autenticación, etc.).
    |
    */
    'supports_credentials' => false,

];
