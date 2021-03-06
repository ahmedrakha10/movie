<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use Yajra\DataTables\DataTables;

class MovieController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_movies')->only(['index']);
        $this->middleware('permission:create_movies')->only(['create', 'store']);
        $this->middleware('permission:update_movies')->only(['edit', 'update']);
        $this->middleware('permission:delete_movies')->only(['delete', 'bulk_delete']);

    }// end of __construct

    public function index()
    {
        $genres = Genre::all();
        return view('admin.movies.index', compact('genres'));

    }// end of index

    public function data()
    {
        $movies = Movie::whenGenreId(request('genre_id'))->with('genres');

        return DataTables::of($movies)
                         ->addColumn('record_select', 'admin.movies.data_table.record_select')
                         ->addColumn('poster', function (Movie $movie) {
                             return view('admin.movies.data_table.poster', compact('movie'));
                         })
                         ->addColumn('genres', function (Movie $movie) {
                             return view('admin.movies.data_table.genres', compact('movie'));
                         })
                         ->addColumn('vote', 'admin.movies.data_table.vote')
                         ->addColumn('actions', 'admin.movies.data_table.actions')
                         ->rawColumns(['record_select', 'vote', 'actions'])
                         ->toJson();

    }// end of data


    public function destroy(Movie $movie)
    {
        $this->delete($movie);
        session()->flash('success', __('Deleted successfully'));
        return response(__('Deleted successfully'));

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $movie = Movie::FindOrFail($recordId);
            $this->delete($movie);

        }//end of for each

        session()->flash('success', __('Deleted successfully'));
        return response(__('Deleted successfully'));

    }// end of bulkDelete

    private function delete(Movie $movie)
    {
        $movie->delete();

    }// end of delete
}//End of controller
