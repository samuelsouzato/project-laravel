<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage, Http;
use App\Http\Requests\StoreEventRequest;
use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{
    public function index(){

        $search = request('search');

        if($search) {

            $events = Event::where([
                ['title', 'like', '%' .$search. '%']
            ])->get();

        } else {
            $events = Event::all();
        }

        return view('welcome',['events' => $events, 'search' => $search]);
    }
    
    public function create(){
        return view('events.create');
    }

    public function store (StoreEventRequest $request) {

        $event = new Event();

        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = $request->items;
        
        
        // Validate the image
        $requestImage = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);[
        'image.required' => 'Por favor, envie uma imagem.',
        'image.image' => 'O arquivo precisa ser uma imagem.',
        'image.mimes' => 'A imagem deve estar nos formatos: jpeg, png, jpg ou gif.',
        
        ];

        // Image Upload
        if($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('img/events'), $imageName);

            $event->image = $imageName;

            // Envia para a API Python após salvar
            $imagePath = public_path('img/events/' . $imageName);
        try {
            $response = Http::attach(
                'file', fopen($imagePath, 'r'), $imageName
            )->post('http://127.0.0.1:8000/redimensionar/');

           if ($response->successful()) {
            // Salva a imagem redimensionada no lugar da original
                $processedImage = $response->body();
                Storage::disk('public')->put("img/events/{$imageName}", $processedImage);
                $event->image = $imageName;
            } else {
                logger('Erro ao processar imagem: ' . $response->status());
            }

        } catch (\Exception $e) {
            logger('Erro ao enviar imagem para a API Python: ' . $e->getMessage());
        }
            

        }

        $user = auth()->user();
        $event->user_id = $user->id;

        $event->save();

        return redirect('/')->with('msg', 'Evento criado com sucesso!');
    }
    
    public function show($id) {

        $event = Event::findOrFail($id);

        $user = auth()->user();
        $hasUserJoined = false;

        if($user) {

            $userEvents = $user->eventsAsParticipant->toArray();

            foreach($userEvents as $userEvents) {
                if($userEvents['id'] == $id) {
                    $hasUserJoined = true;
                }
            }

        }

        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner, 'hasUserJoined' => $hasUserJoined]);

        

    }

    public function dashboard() {

        $user = auth()->user();

        $events = $user->events;

        $eventsAsParticipant = $user->eventsAsParticipant;

        return view('events.dashboard', 
        ['events' => $events, 'eventsAsParticipant' => $eventsAsParticipant]);

    }

    public function destroy($id) {
        Event::findOrFail($id)->delete();

        return redirect('/dashboard')->with('msg', 'Evento excluído com Sucesso!!');
    }

    public function edit($id) {

        $user = auth()->user();

        $event = Event::findOrFail($id);

        if($user->id != $event->user_id) {
            return redirect('/dashboard');
        }

        return view('events.edit', ['event'=> $event]);
    }

    public function update(request $request) {

        $data = $request->all();

        // Image Upload
        if($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('img/events'), $imageName);

            $data['image'] = $imageName;

        }

        Event::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg', 'Evento editado com Sucesso!!');

    }

    public function joinEvent($id) {

        $user = auth()->user();

        $user->eventsAsParticipant()->attach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no evento ' .$event->title);

    }

    public function leaveEvent($id) {

        $user = auth()->user();

        $user->eventsAsParticipant()->detach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Você saiu com sucesso do evento: ' .$event->title);

    }
    

}
