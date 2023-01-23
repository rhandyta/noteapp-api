<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $notes = Note::where('visible', '=', 0)
                ->where('archive', '=', 0)
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
            $note = Note::where('slug', '=', $id)->first();
            return response()->json([
                'success' => true,
                'notes' => $note,
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
    public function update(UpdateNoteRequest $request)
    {
        try {
            $auth = Auth::user();
            $note = Note::where('user_id', '=', $auth->id)
                ->where('id', '=', (int)$request->input('id'))
                ->update([
                    'title' => ucfirst($request->input('title')),
                    'body' => $request->input('body'),
                    'visible' => $request->input('visible'),
                    'archive' => $request->input('archive'),
                ]);


            if ($note) {
                $result = Note::findOrFail($request->input('id'));
                return response()->json([
                    'success' => true,
                    'message' => "Note has been updated",
                    'note' => $result,
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
            $note = Note::findOrFail($id);
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
}
