<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{

    use HasFactory;

    //

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);

    }

    public function scopeTitle(Builder $builder, string $title): Builder
    {
        return $builder->where('title', 'like', '%' . $title . '%');
    }

    public function scopeWithReviewsCount(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withCount([
            'reviews' => fn(Builder $q) => $this->dataRangeFilter($q, $from, $to)]);
    }
    public function scopeWithAvgRating(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withAvg([
            'reviews' => fn(Builder $q) => $this->dataRangeFilter($q, $from, $to)], 'rating');
        }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withReviewsCount()
            ->orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withAvgRating()
            ->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeMinReviews(Builder $query, int $minReviews): Builder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }

    private function dataRangeFilter(Builder $query, $from = null, $to = null): void
    {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

    }

    public function scopePopularLastMonth(Builder $query)
    {

        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(2);

    }

    public function scopePopularLast6Months(Builder $query)
    {

        return $query->popular(now()->subMonth(6), now())
            ->highestRated(now()->subMonth(6), now())
            ->minReviews(5);

    }

    public function scopeHighestRatedLastMonth(Builder $query)
    {

        return $query->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReviews(2);

    }

    public function scopeHighestRatedLast6Months(Builder $query)
    {

        return $query->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minReviews(5);

    }

    protected static function booted()
    {
        static::updated(fn(Book $review)=>cache()->forget('book:' . $book->id));
        static::deleted(fn(Book $review)=>cache()->forget('book:' . $book->id));
    }
}
