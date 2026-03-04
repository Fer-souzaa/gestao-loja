<?php

namespace App\Providers;

use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use DB;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        if (config('logging.query.enabled')) {
            // Variável de controle para armazenar o caminho do arquivo do request atual
            $currentLogPath = null;

            // 1. Identifica a rota e define/limpa o arquivo
            Route::matched(function (RouteMatched $event) use (&$currentLogPath) {
                $div = "----------------------------------------------------------------------------------------------------------";
                $uri = $event->route->uri();

                // 1. Remove "api/v1/" ou qualquer "api/vX/" do início
                $cleanUri = preg_replace('/^api\/v\d+\//', '', $uri);

                // Se preferir algo mais simples e fixo:
                // $cleanUri = str_replace('api/v1/', '', $uri);

                // 2. Transforma o restante em slug (trocando / por _)
                // Removi o { e } do replace para manter como está no seu print
                $slug = str_replace(['/', ':', '?'], ['_', '', ''], $cleanUri);
                $slug = trim($slug, '_') ?: 'root';

                $currentLogPath = storage_path("logs/endpoints/{$slug}.log");

                File::ensureDirectoryExists(dirname($currentLogPath));

                // Mantemos a URI completa no cabeçalho do arquivo para contexto
                File::append($currentLogPath, "\n\n" . $div . "\n\n--- INÍCIO: [" . $event->request->method() . "] /{$uri} | " . now() . " ---\n");
            });

            // 2. Escuta as queries
            DB::listen(function ($query) use (&$currentLogPath) {
                // Só loga se a rota já tiver sido identificada
                if ($currentLogPath) {
                    File::append(
                        $currentLogPath,
                        sprintf("[%s] %s | Binds: [%s] | %sms\n",
                            now()->format('H:i:s'),
                            $query->sql,
                            json_encode($query->bindings),
                            $query->time
                        )
                    );
                }
            });
        }
    }
}
