<?php 

namespace App\Providers; 

use App\Repository\TeamRepositoryInterface; 
use App\Repository\Model\TeamRepository; 
use App\Repository\TeamPlayerRepositoryInterface; 
use App\Repository\Model\TeamPlayerRepository; 
use Illuminate\Support\ServiceProvider; 

/** 
* Class RepositoryServiceProvider 
* @package App\Providers 
*/ 
class RepositoryServiceProvider extends ServiceProvider 
{ 
   /** 
    * Register services. 
    * 
    * @return void  
    */ 
   public function register() 
   { 
       $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
       $this->app->bind(TeamPlayerRepositoryInterface::class, TeamPlayerRepository::class);
   }
}