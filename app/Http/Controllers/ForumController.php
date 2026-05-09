<?php

namespace App\Http\Controllers;

use App\Models\ForumComments;
use Illuminate\Http\Request;
use App\Models\Forum;
use App\Models\ForumCategory;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    //all forum only (no comment)
    public function index(Request $request) {
        $query = Forum::with([
            'user',
            'category',
        ]);

        // query berdasarkan slug
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $forums = $query->latest()->get()->paginate(10);

        return response()->json([
            'status'=>'sucess',
            'data'=>$forums
        ],201);
    }

    //get specific forum & comment
    public function show($id) {
        $query = Forum::with([
            'user',
            'category',
            'comments.user'
        ])->findOrFail($id);

        $forums = $query->latest()->get()->paginate(10);

        return response()->json([
            'status'=>'sucess',
            'data'=>$forums
        ],201);
    }

    //store forum
    public function store(Request $request) {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $forum = Forum::create([
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return response()->json([
            'message' => 'Forum berhasil dibuat',
            'data' => $forum->load('category', 'user')
        ]);
    }

    //post comment
    public function storeComment(Request $request, $id)
    {
        $validated = $request->validate([
            'comment' => ['required', 'string']
        ]);

        $forum = Forum::findOrFail($id);

        $comment = ForumComments::create([
            'user_id' => auth()->id(),
            'forum_id' => $forum->id,
            'comment' => $validated['comment']
        ]);

        return response()->json([
            'message' => 'Komentar berhasil ditambahkan',
            'data' => $comment->load('user')
        ], 201);
    }


    //get category
    public function category()
    {
        return response()->json(
            ForumCategory::all()
        );
    }
    // store category
    public function storeCategory(Request $request) {
        ForumCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'description'=>$request->description,
        ]);
        return response()->json([
            'status'=>'success',
            'message'=>'Forum Category stored',
        ],201);
    }
}
