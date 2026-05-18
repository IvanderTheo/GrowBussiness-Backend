<?php

namespace App\Http\Controllers;

use App\Models\ForumComment;
use Exception;
use Illuminate\Http\Request;
use App\Models\Forum;
use App\Models\ForumCategory;
use Illuminate\Support\Facades\DB;
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

        $forums = $query->latest()->paginate(10);

        return response()->json([
            'status'=>'sucess',
            'data'=>$forums
        ],201);
    }

    //get specific forum & comment
    public function show($id)
    {
        $forum = Forum::with([
            'user',
            'category',
            'comments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'comments.user'
        ])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $forum
        ]);
    }

    //store forum
    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'category_id' => ['required', 'exists:forum_categories,id'],
                'title' => ['required', 'string', 'max:255'],
                'content' => ['required', 'string'],
            ]);
            DB::transaction(function () use($validated) {
                Forum::create([
                'user_id' => auth()->id(),
                'category_id' => $validated['category_id'],
                'title' => $validated['title'],
                'content' => $validated['content'],
                ]);
            });

            return response()->json([
                'status'=>'success',
                'message' => 'Forum berhasil dibuat'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=>'failed',
                'message' => $e->getMessage()
            ],500);
        }
    }

    //post comment
    public function storeComment(Request $request, $id)
    {
        try {
            $request->validate([
                'comment' => ['required', 'string']
            ]);
            DB::transaction(function() use ($request, $id) {
                $forum = Forum::findOrFail($id);

                ForumComment::create([
                    'user_id' => auth()->id(),
                    'forum_id' => $forum->id,
                    'comment' => $request->comment
                ]);
            });

            return response()->json([
                'message' => 'Komentar berhasil ditambahkan'
            ], 201);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()]);
        }
    }


    //get category
    public function category()
    {
        $result = ForumCategory::all();
        return response()->json([
            'status'=>"success",
            'message'=> "Success Retreieved Data",
            'data'=>$result,
        ]);
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
