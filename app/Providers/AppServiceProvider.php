<?php

namespace App\Providers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
// use App\Helpers;
use Illuminate\Support\Collection;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use TCG\Voyager\Http\Controllers\VoyagerBreadController;
use App\Http\Controllers\Voyager\VoyagerBreadAssignmentsController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

//         Collection::macro('paginate', 
//             function ($perPage = 15, $page = null, $options = []) {
//             $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
//             return (new LengthAwarePaginator(
//                 $this->forPage($page, $perPage), $this->count(), $perPage, $page, $options))
//                 ->withPath('');
//         });
      
        Schema::defaultStringLength(191);
      
      
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
           $this->app->bind(VoyagerBreadController::class, MyBreadController::class);

         Collection::macro('paginate', function( $perPage, $total = null, $page = null, $pageName = 'page' ) {
        $page = $page ?: LengthAwarePaginator::resolveCurrentPage( $pageName );

        return new LengthAwarePaginator( $this->forPage( $page, $perPage ), $total ?: $this->count(), $perPage, $page, [
          'path' => LengthAwarePaginator::resolveCurrentPath(),
          'pageName' => $pageName,
        ]);
      });
    }
}
