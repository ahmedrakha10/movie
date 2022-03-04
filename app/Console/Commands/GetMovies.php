<?php

namespace App\Console\Commands;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Http as HttpAlias;

class GetMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:movies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'list of popular movies from TMDB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->getPopularMovies();
    }//End of handle

    public function getPopularMovies()
    {

        for ($i = 1; $i <= config('services.tmdb.max_pages'); $i++) {
            $response = Http::get(config('services.tmdb.url') . '/movie/popular?region=us&api_key=' . config('services.tmdb.api_key') .
                                  '&page=' . $i);
            foreach ($response->json()['results'] as $result) {
                $movie = Movie::create([
                                           'e_id'         => $result['id'],
                                           'title'        => $result['title'],
                                           'description'  => $result['overview'],
                                           'poster'       => $result['poster_path'],
                                           'banner'       => $result['backdrop_path'],
                                           'release_date' => $result['release_date'],
                                           'vote'         => $result['vote_average'],
                                           'vote_count'   => $result['vote_count'],
                                       ]);

                foreach ($result['genre_ids'] as $genreId) {
                    $genre = Genre::where('e_id', $genreId)->first();
                    $movie->genres()->attach($genre->id);
                }
            } //End foreach
        } //End of for
    }
}//End of class
