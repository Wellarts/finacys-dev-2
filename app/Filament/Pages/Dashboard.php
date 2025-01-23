<?php

namespace App\Filament\Pages;

use App\Models\Cartao;
use App\Models\ContasPagar;
use App\Models\ContasReceber;
use App\Models\DataFatura;
use App\Models\Fatura;
use App\Models\Veiculo;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Facades\FilamentIcon;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $routePath = '/';

    protected static ?int $navigationSort = -2;

    /**
     * @var view-string
     */
    protected static string $view = 'filament-panels::pages.dashboard';



    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ??
            static::$title ??
            __('filament-panels::pages/dashboard.title');
    }

    public static function getNavigationIcon(): ?string
    {
        return static::$navigationIcon
            ?? FilamentIcon::resolve('panels::pages.dashboard.navigation-item')
            ?? (Filament::hasTopNavigation() ? 'heroicon-m-home' : 'heroicon-o-home');
    }

    public static function routes(Panel $panel): void
    {
        Route::get(static::getRoutePath(), static::class)
            ->middleware(static::getRouteMiddleware($panel))
            ->withoutMiddleware(static::getWithoutRouteMiddleware($panel))
            ->name(static::getSlug());
    }

    public static function getRoutePath(): string
    {
        return static::$routePath;
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return Filament::getWidgets();
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    /**
     * @return int | string | array<string, int | string | null>
     */
    public function getColumns(): int | string | array
    {
        return 2;
    }

    public function getTitle(): string | Htmlable
    {
        return static::$title ?? __('filament-panels::pages/dashboard.title');
    }

    public function mount(): void
    {

        $cartoes = Cartao::all();

        foreach ($cartoes as $cartao) {
            if ($cartao->fechamento_fatura == Carbon::now()->format('d')) {
                Notification::make()
                    ->title('ATENÇÃO: Fechamento de Fatura')
                    ->body('O cartão: ' . $cartao->nome . 'fechou hoje a fatura.')
                    ->danger()
                    ->persistent()
                    ->send();
                for ($cont = 0; $cont <= Fatura::where('cartao_id', $cartao->id)->count(); $cont++) {
                    $valorTotalFatura = Fatura::where('cartao_id', $cartao->id)->sum('valor_parcela');
                }
                $fechamentoFatura = [
                    'cartao_id' => $cartao->id,
                    'valor_fatura' => $valorTotalFatura,
                    'vencimento_fatura' => Fatura::where('cartao_id', $cartao->id)->first()->data_vencimento,
                    'team_id' => $cartao->team_id,
                ];

                DataFatura::create($fechamentoFatura);
            }
        }
    }
}
