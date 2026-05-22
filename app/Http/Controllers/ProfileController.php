<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

    public function index(Request $request)
    {
        return view('profile.perfil');
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function uploadAvatar(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'avatar' => 'image|max:4096'
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Somente imagens é permitido!');
        }

        $user = auth()->user();
        //dd($request->file('avatar'));
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            // Caminho: public/uploads
            $destination = public_path('uploads/avatars');
            if (!file_exists($destination)) {
                mkdir($destination, 0775, true);
            }
            // dd($file->move($destination, $filename));
            $file->move($destination, $filename);

            $user->avatar = '/uploads/avatars/' . $filename;
            $user->save();

            return redirect()->back()->with('success', 'Avatar atualizado com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Não foi possivel alterar o avatar. Tente novamente!');
        }
    }
}
