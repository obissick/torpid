<?php
namespace App\CustomClass;
 
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Capsule\Manager as Capsule;

class query_slow_host
{
   public static function run($host, $database, $query){
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $host,
            'database'  => $database,
            'username'  => env('SLOW_USER', 'root'),
            'password'  => env('SLOW_PASSWORD', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        $capsule->setAsGlobal();

        try{
            $results = Capsule::select("EXPLAIN ".$query);
            return $results;
        }catch (Exception $e){

        }
        Capsule::disconnect();
    }
}