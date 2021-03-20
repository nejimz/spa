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

		$input = $request->only('parent_id', 'name', 'message');
		$input['created_at'] = date('Y-m-d H:i:s');
		$input['updated_at'] = date('Y-m-d H:i:s');

		DB::table('comments')->insert($input);

		return response()->json([ 'message' => 'Posted!']);
	}

	public function list()
	{
		$collections = DB::table('comments')->orderBy('created_at', 'DESC')->get();
		$comments = $collections->where('parent_id', 0)->sortByDesc('created_at')->all();
		$rows = [];
		$n = 0;
		foreach ($comments as $comment) {
			$sub_comments = $this->recursive($comment->id, $collections);

			$rows[$n] = [
				'id' => $comment->id,
				'parent_id' => $comment->parent_id,
				'name' => $comment->name,
				'message' => $comment->message,
				'created_at' => $comment->created_at,
				'isShow' => false,
				'sub_comments' => $sub_comments
			];
			$n++;
		}
		#dd($rows);
		return response()->json($rows);
	}

    public function recursive($id, $parent_comments)
    {
		$rows = [];
    	$comments = $parent_comments->where('parent_id', $id)->sortByDesc('created_at')->all();
    	$n = 0;
    	foreach ($comments as $comment) {
			$rows[$n] = [
				'id' => $comment->id,
				'parent_id' => $comment->parent_id,
				'name' => $comment->name,
				'message' => $comment->message,
				'created_at' => $comment->created_at,
				'isShow' => false,
			];

    		$sub_comments = $parent_comments->where('parent_id', $id)->sortByDesc('created_at')->all();
	    	if (count($sub_comments) > 0) {
	    		$rows[$n]['sub_comments'] = $this->recursive($comment->id, $parent_comments);
	    	}
	    	$n++;
    	}
    	return $rows;
    }
}
