<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * El mapa de políticas para las clases del modelo de la aplicación.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Comment::class => \App\Policies\CommentPolicy::class, // Mapea el modelo con su política
    ];

    /**
     * Registra cualquier servicio de autenticación o autorización.
     */
    public function boot(): void
    {
        $this->registerPolicies(); // Registra las políticas automáticamente

        // Puedes definir gates personalizados aquí, si lo necesitas.
    }
}
