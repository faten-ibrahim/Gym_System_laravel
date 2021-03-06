<?php

namespace App\Http\Controllers\Gyms;

use App\Gym;
use App\User;
use App\City;
use App\Http\Requests\Gym\StoreGymRequest;
use App\Http\Requests\Gym\UpdateGymRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class GymsController extends Controller
{
    public function index()
    {
        return view('gyms.index');
    }

    public function create()
    {
        $cities = City::all();
        //to get current user
        $user = auth()->user();
        return view('gyms.create', [
            'cities' => $cities,
            'user' => $user
        ]);
    }

    public function store(StoreGymRequest $request)
    {
        $data = request()->all();
        $data['cover_image'] = Storage::put("images", $request->file('cover_image'));
        Gym::create($data);
        return redirect()->route('gyms.index');
    }

    public function edit(Gym $gym)
    {
        return view('gyms.edit', [
            'gym' => $gym,
        ]);
    }

    public function update(UpdateGymRequest $request, Gym $gym)
    {
        $gym->update(request()->all());
        return redirect()->route('gyms.index');
    }

    public function destroy(Gym $gym)
    {
        $gymId = $gym->id;
        $session = DB::select('select * from sessions where gym_id = :gym_id', ['gym_id' => $gymId]);
        if (!$session) {

            $gym->delete();
        } else {

            return redirect()->back()->with('alert', 'You can not delete this gym');
        }
        return redirect()->route('gyms.index');
    }


    public function show(Gym $gym)
    {
        return view('gyms.show', [
            'gym' => $gym,
        ]);
    }

    public function get_table()
    {
        $user = \Auth::user();
        $role = $user->roles->first()->name;

        if ($role === 'admin') {

            $gyms = DB::table('gyms')
                ->join('cities', 'cities.id', '=', 'gyms.city_id')
                ->join('users', 'users.id', '=', 'cities.city_manager_id')
                ->select(
                    'gyms.id as id',
                    'gyms.name as name',
                    'users.name as city_manager',
                    'gyms.cover_image as cover_image'

                )
                ->get();
            return (datatables()->of($gyms)->make(true));
            //    return response()->json($gyms);
            $id = Auth::user()->id;
            $city_id = DB::table('cities')
                ->select('cities.id')
                ->where('cities.city_manager_id', '=', $id)
                ->value('id');
            $gyms = DB::table('gyms')
                ->select(
                    'gyms.id as id',
                    'gyms.name as name',
                    'gyms.cover_image as cover_image'
                )->where('gyms.city_id', '=', $city_id)
                ->get();
            //   return response()->json($gyms);
            return (datatables()->of($gyms)->make(true));
        }
    }
}
