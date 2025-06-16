<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Board; // seu model
use App\Policies\BoardPolicy; // sua policy

class AuthServiceProvider extends ServiceProvider
{
    /**
     * As políticas que devem ser registradas.
     *
     * @var array
     */
    protected $policies = [
        Board::class => BoardPolicy::class,
        // adicione outras policies aqui
    ];

    /**
     * Registrar quaisquer gates adicionais.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Você pode definir gates adicionais aqui se necessário
    }
}
