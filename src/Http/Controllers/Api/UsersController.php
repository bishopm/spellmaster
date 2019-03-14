<?php

namespace Bishopm\Spellmaster\Http\Controllers\Api;

use Bishopm\Spellmaster\Models\User;
use Bishopm\Spellmaster\Models\Score;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	
	public function index()
	{
        return User::orderBy('name')->get()->toJson();
	}

    public function random()
	{
        return User::all()->random(1);
	}

	public function edit(User $User)
    {
        $tags=User::allTags()->get();
        $btags=array();
        foreach ($User->tags as $tag){
            $btags[]=$tag->name;
        }
        $suppliers=$this->suppliers->all();
        return view('connexion::Users.edit', compact('User','tags','btags','suppliers'));
    }

	public function show($slug)
	{
        $data['User']=$this->User->findBySlug($slug);
        if ($data['User']){
            $data['authors']=explode(',',$data['User']->author);
            $data['comments'] = $data['User']->comments()->paginate(5);
            $data['fulltitle'] = $data['User']->title . " (" . $data['User']->author . ")";
            $data['messagetxt'] = "I would like to buy a copy of: " . $data['fulltitle'] . ". When you email to confirm the User is ready for collection, I will bring cash or proof of payment of R" . $data['User']->saleprice . " to the church office. Thanks!";
            return view('connexion::site.User',$data);
        } else {
            abort(404);
        }
	}

    public function store(Request $request)
    {
        $User=User::firstOrCreate(['User'=>$request->User,'level'=>1]);
        $User->save();
        return redirect()->route('Users.index')->withSuccess('User has been added');
    }
	
    public function update(User $User, UpdateUserRequest $request)
    {      
        $this->User->update($User, $request->except('files','tags'));
        $User->tag($request->tags);
        return redirect()->route('admin.Users.index')->withSuccess('User has been updated');
    }

    public function destroy($id)
    {
        $User=$this->User->find($id);
        $supplier=$User->supplier_id;
        $User->delete();
        return redirect()->route('admin.suppliers.edit',$supplier)->withSuccess('User has been deleted');
    }

    public function welcome($id)
    {
        $data['user'] = User::find($id);
        $data['attempts'] = intval(Score::where('user_id',$id)->sum('attempts'));
        $data['correct'] = intval(Score::where('user_id',$id)->sum('correct'));
        $data['tags'] = DB::table('tags')->where('count','>',0)->orderBy('name')->get();
        if ($data['correct']==0) {
            $data['score'] = 0;
        } else {
            $data['score'] = $data['correct'] / $data['attempts'] * 100;
        }
        return $data;
    }

}