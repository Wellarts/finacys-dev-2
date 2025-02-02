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
            //lançamentos do próximo vencimento

            //    // $cartao = Cartao::where('id', $cartao->id)->first();
            //     $vencimentoFatura = $cartao->vencimento_fatura;
            //     $fechamentoFatura = $cartao->fechamento_fatura;
            //     $dataVencimento = Carbon::createFromFormat('d/m/Y', $vencimentoFatura . '/' . Carbon::now()->format('m/Y'));
            //     $dataFechamento = Carbon::createFromFormat('d/m/Y', $fechamentoFatura . '/' . Carbon::now()->format('m/Y'));

            //     if ($dataFechamento <= Carbon::now()) {
            //         if ($vencimentoFatura < $fechamentoFatura) {
            //             $dataVencimento->addMonths(2);
            //         } else {
            //             $dataVencimento->addMonths(1);
            //         }
            //     }

            //     $dataVencimento->format('Y-m-d');

            if ($cartao->fechamento_fatura <= Carbon::now()->format('d')) {
                //   dd($cartao->fechamento_fatura);
                if (Fatura::where('cartao_id', $cartao->id)->count() != 0) {

                    //  $valorTotalFatura = Fatura::where('cartao_id', $cartao->id)->sum('valor_parcela');
                    $lancamentosCartao = Fatura::where('cartao_id', $cartao->id)
                        ->where('pago', 0)
                        ->where('created_at', '<', Carbon::now()->toDateTimeString())
                        ->get();
                      //  ->sum('valor_parcela');

                     //   dd($valorTotalFatura);
                  //  dd($lancamentosCartao);
                    $fechamentoFatura = [

                        'cartao_id' => $cartao->id,
                        'valor_fatura' => $lancamentosCartao->sum('valor_parcela'),
                        'vencimento_fatura' => $lancamentosCartao->first()->data_vencimento,
                        'pago' => false,
                        'valor_pago' => 0,
                        'team_id' => $cartao->team_id,
                    ];


                    if (!DataFatura::where('cartao_id', $cartao->id)->where('valor_fatura',$lancamentosCartao->sum('valor_parcela'))->where('vencimento_fatura', $lancamentosCartao->first()->data_vencimento)->exists()) {
                        DataFatura::create($fechamentoFatura);

                        Notification::make()
                            ->title('ATENÇÃO: Fechamento de Fatura')
                            ->body('A fatura do cartão: ' . $cartao->nome . ' está fechada.')
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }
            }
        }
    }
}
