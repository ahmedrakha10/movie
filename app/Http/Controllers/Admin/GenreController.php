<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Yajra\DataTables\DataTables;

class GenreController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_genres')->only(['index']);
        $this->middleware('permission:create_genres')->only(['create', 'store']);
        $this->middleware('permission:update_genres')->only(['edit', 'update']);
        $this->middleware('permission:delete_genres')->only(['delete', 'bulk_delete']);

    }// end of __construct

    public function index()
    {
        return view('admin.genres.index');

    }// end of index

    public function data()
    {
        $genres = Genre::select();

        return DataTables::of($genres)
            ->addColumn('record_select', 'admin.genres.data_table.record_select')
            ->editColumn('created_at', function (Genre $genre) {
                return $genre->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'admin.genres.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data


    public function destroy(Genre $genre)
    {
        $this->delete($genre);
        session()->flash('success', __('Deleted successfully'));
        return response(__('Deleted successfully'));

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $genre = Genre::FindOrFail($recordId);
            $this->delete($genre);

        }//end of for each

        session()->flash('success', __('Deleted successfully'));
        return response(__('Deleted successfully'));

    }// end of bulkDelete

    private function delete(Genre $genre)
    {
        $genre->delete();

    }// end of delete

}//end of controller