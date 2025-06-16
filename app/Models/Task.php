<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
    'title',
    'description',
    'order',
    'column_id',
    'priority' // Adicione este campo
];

    public function column()
    {
        return $this->belongsTo(Column::class);
    }
}
