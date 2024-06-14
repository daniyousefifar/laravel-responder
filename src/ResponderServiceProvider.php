<?php

namespace Zirsakht\Responder;

use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;
use Zirsakht\Responder\Macros\RequestMacros;
use Zirsakht\Responder\Macros\ResponseMacros;

class ResponderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // ...
    }

    public function register(): void
    {
        Request::mixin(new RequestMacros());

        ResponseFactory::mixin(new ResponseMacros());

        $this->app->bind('responder',function(){
            return new Responder();
        });
    }
}
