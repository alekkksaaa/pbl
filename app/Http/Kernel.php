protected $routeMiddleware = [
// ...
'is_admin' => \App\Http\Middleware\IsAdmin::class,
'no_admin' => \App\Http\Middleware\RedirectIfAdmin::class, // Tambahkan ini
];