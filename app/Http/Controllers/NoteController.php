<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth = Auth::user();
        try {
                $notes = Note::where(function ($query) {
                    $query->where('visible', 0)
                          ->orWhere(function ($query) {
                              $query->where('user_id', auth()->id())
                                    ->where('visible', 1);
                          });
                })->where('archive', 0)
                ->with('user')
                ->orderBy('created_at', "DESC")
                ->paginate(10);

            return response()->json([
                'success' => true,
                'notes' => $notes,
                'message' => "fetch notes success",
                'code' => 200
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreNoteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNoteRequest $request)
    {
        try {
            $auth = Auth::user();
            $note = Note::create([
                'user_id' => $auth->id,
                'key' => Uuid::uuid4(),
                'title' => ucfirst($request->input('title')),
                'slug' => \Str::slug($request->input('title')),
                'body' => $request->input('body'),
                'visible' => $request->input('visible') ? 1 : 0,
                'archive' => $request->input('archive') ? 1 : 0
            ]);

            $note['user'] = $auth;

            return response()->json([
                'success' => true,
                'message' => "Note has been created",
                'note' => $note,
                'code' => 201
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $note = Note::with('user')->where('slug', '=', $id)->first();
            return response()->json([
                'success' => true,
                'note' => $note,
                'message' => "fetch note success",
                'code' => 200
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Note not found",
                'code' => 404
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNoteRequest  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update($slug, UpdateNoteRequest $request)
    {
        try {
            $note = Note::where('slug', '=', $slug)->firstOrFail();
            $note->title = ucfirst($request->input('title'));
            $note->body = $request->input('body');
             if($request->has('visible')) {
                $note->visible = (int)$request->input('visible');
            }
            if($request->has('archive')){
                $note->archive = (int)$request->input('archive');
            }
            $note->save();

            if ($note) {
                return response()->json([
                    'success' => true,
                    'message' => "Note has been updated",
                    'note' => $note,
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Note not found",
                    'code' => 404
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'code' => 404
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $note = Note::where('slug', '=', $id)->firstOrFail();
            $note->delete();
            return response()->json([
                'success' => true,
                'message' => "delete note success",
                'code' => 200
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Note not found",
                'code' => 404
            ]);
        }
    }

    public function search(Request $request)
    {
        try {
            $note = Note::where('title', 'LIKE', '%' . $request->input('title') . '%')
                    ->where(function ($query) {
                    $query->where('visible', 0)
                          ->orWhere(function ($query) {
                              $query->where('user_id', auth()->id())
                                    ->where('visible', 1);
                          });
                })
                    ->where('archive', '=', 0)->get();
             return response()->json([
                'success' => true,
                'notes' => $note,
                'message' => "fetch note success",
                'code' => 200
            ]);
        } catch(Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Note not found",
                'code' => 404
            ]);
        }
    }

    public function archive()
    {
        try {
            $auth = Auth::user();
            $notes = Note::with('user')->where('archive', '=', 1)->orderBy('created_at', 'DESC')
                    ->where('user_id', '=', $auth->id)->get();
            return response()->json([
                'success' => true,
                'notes' => $notes,
                'code' => 200
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Notes not found",
                'code' => 404
            ]);
        }
        

    }
}
