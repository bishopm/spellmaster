<?php

namespace Bishopm\Spellmaster\Http\Controllers\Api;

use Bishopm\Spellmaster\Models\Word;
use Bishopm\Spellmaster\Models\Score;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WordsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	
	public function index()
	{
        return Word::orderBy('word')->get()->toJson();
    }
    
    public function search($txt)
	{
        return Word::where('word','like','%' . $txt . '%')->get()->toJson();
	}

    public function random()
	{
        return Word::all()->random(1);
	}

    public function answer(Request $request)
    {
        $score = Score::where('word_id', $request->word_id)->where('user_id', $request->user_id)->first();
        if (is_null($score)) {
            if ($request->answer == 1) {
                $gpa = 1;
            } else {
                $gpa = 0;
            }
            $newscore = Score::create([
                'word_id'=>$request->word_id,
                'user_id'=>$request->user_id,
                'attempts'=>1,
                'correct'=>intval($request->answer),
                'score'=>$gpa
            ]);
        } else {
            $attempts = $score->attempts + 1;
            $correct = $score->score + intval($request->answer);
            if ($correct > 0) {
                $gpa = $correct / $attempts;
            } else {
                $gpa = 0;
            }
            $score->update(['attempts'=>$attempts, 'correct'=>$correct, 'score'=>$gpa]);
        }
        return $this->userscore($request->user_id);
    }

    public function userscore($id)
    {
        $data['attempts'] = intval(Score::where('user_id',$id)->sum('attempts'));
        $data['correct'] = intval(Score::where('user_id',$id)->sum('correct'));
        $data['score'] = $data['correct'] / $data['attempts'] * 100;
        return $data;
    }

    public function store(Request $request)
    {
        return Word::create([
            'word'=>$request->word,
            'hint'=>$request->hint,
            'level'=>$request->level
        ]);
    }

}