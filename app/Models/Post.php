<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'content', 'category_id', 'thumbnail'];
    use Sluggable;




    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }


    //======== ДОПОЛНИТЕЛЬНЫЕ МЕТОДЫ ============
    // Сохранение файла картинки
    public static function uploadImage(Request $request, $image = null)
    {
        if ($request->hasFile('thumbnail')) {
            if ($image) {
                Storage::delete($image);
            }
            $folder = date('Y-m-d');
            $folder = str_replace('-','/',$folder);
            $targetFolder = public_path('uploads/images/'.$folder);
            //dd($targetFolder);
            // Если папка не существует она создается
            if(!file_exists($targetFolder)) {
                if (!mkdir($targetFolder, 0777, true)) {
                    die('Не удалось создать директории...');
                }
            }
            return Storage::putFile('images/'.$folder, $request->file('thumbnail'));
            //return $request->file('thumbnail')->store("/images/{$folder}");
        }
        return null;
    }

    public function getImage()
    {
        if (!$this->thumbnail) {
            return asset("no-image.png");
        }
        return asset("uploads/{$this->thumbnail}");
    }


}
