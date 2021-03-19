<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CommentController extends Controller
{
	public function index()
	{
		return view('welcome');
	}

	public function store(Request $request)
	{
		$request->validate([
			'parent_id' 	=> 'required',
			'name' 			=> 'required',
			'message' 		=> 'required'
		]);

		DB::table('comments')->insert($request->only('level', 'parent_id', 'name', 'message'));

		return response()->json([ 'message' => 'Posted!']);
	}

	public function list()
	{
		$collections = DB::table('comments')->orderBy('created_at', 'DESC')->get();
		$comments = $collections->where('parent_id', 0)->sortByDesc('created_at')->all();
		$rows = [];
		foreach ($comments as $comment) {
			$sub_comments = $this->recursive($comment->id, $collections);

			$rows[$comment->id] = [
				'id' => $comment->id,
				'parent_id' => $comment->parent_id,
				'name' => $comment->name,
				'message' => $comment->message,
				'created_at' => $comment->created_at,
				'sub_comments' => $sub_comments
			];
		}

		return response()->json($rows);
	}

    public function recursive($id, $parent_comments)
    {
		$rows = [];
    	$comments = $parent_comments->where('parent_id', $id)->sortByDesc('created_at')->all();

    	foreach ($comments as $comment) {
			$rows[$comment->id] = [
				'id' => $comment->id,
				'parent_id' => $comment->parent_id,
				'name' => $comment->name,
				'message' => $comment->message,
				'created_at' => $comment->created_at
			];

    		$sub_comments = $parent_comments->where('parent_id', $id)->sortByDesc('created_at')->all();
	    	if (count($sub_comments) > 0) {
	    		$rows[$comment->id]['sub_comments'] = $this->recursive($comment->id, $parent_comments);
	    	}
    	}
    	return $rows;
    }
}
