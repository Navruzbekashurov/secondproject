<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['review', 'rating'];

    //

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);

    }

    protected static function booted()
    {
        static::updated(fn(Review $review)=>cache()->forget('book:' . $review->book_id));
        static::deleted(fn(Review $review)=>cache()->forget('book:' . $review->book_id));
        static::created(fn(Review $review)=>cache()->forget('book:' . $review->book_id));
    }
    /*
     *> $review = \App\Models\Review::find(1614);
     *> $review->update(['rating'=>1]);
     *
     * $review =\App\Models\Review::where('id',1614)->update(['rating'=>1]);
     *
     * sabab nimada tepadegida ozgardi frontiyam pasdegida ozgarmadi.
     *
     */
}
