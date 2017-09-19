<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;
class Type extends Model {
    protected $table = 'types';
    protected $fillable = [
        'type',
        'label-singular',
        'label-plural'
    ];

    public static function getAllTypes(){
        return DB::table('types');
    }
  
}