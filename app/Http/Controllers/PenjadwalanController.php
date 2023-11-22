<?php

namespace App\Http\Controllers;

use App\Models\Penjadwalan;
use App\Services\MessagesService;
use App\Services\PenjadwalanService;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PenjadwalanController extends Controller
{
    private MessagesService $messagesService;

    public function __construct(MessagesService $messagesService)
    {
        $this->messagesService = $messagesService;
    }

    function index(Request $request)
    {
        $id = $request->id;
        return view('penjadwalan/index', [
            'id' => $id
        ]);
    }

    function create(Request $request)
    {
        $request->validate([
            'user_tambah' => 'required',
        ]);

        $lastJadwal = Penjadwalan::where('jadwal_id', $request->jadwal)->where('is_done', false)->orderBy('urutan', 'DESC')->first(); 
        $urutan = $lastJadwal !== null ? $lastJadwal->urutan + 1 : 1;
        $tanggal = $lastJadwal!== null && $lastJadwal->tanggal_jadwal !== null ? Carbon::parse($lastJadwal->tanggal_jadwal)->addDay() : null;

        Penjadwalan::create([
            'user_id' => $request->input('user_tambah'),
            'jadwal_id' => $request->jadwal,
            'urutan' => $urutan,
            'tanggal_jadwal' => $tanggal
        ]);

        return redirect()->route('penjadwalan.index', ['id' => $request->jadwal])->with('success', 'Penjadwalan berhasil dibuat!');
    }

    function update(Request $request, $id)
    {
        $request->validate([
            'user' => 'required',
            'penjadwalan_tambah' => 'required',
        ]);

        $penjadwalan = Penjadwalan::find($id);
        if (!$penjadwalan) {
            return redirect()->route('penjadwalan.index')->with('error', 'Penjadwalan tidak ditemukan.');
        }

        $penjadwalan->user_id = $request->input('user');
        $penjadwalan->tanggal_jadwal = $request->input('penjadwalan_tambah');
        $penjadwalan->jadwal_id = $request->jadwal;

        $penjadwalan->save();
        return redirect()->route('penjadwalan.index', ['id' => $request->jadwal])->with('success', 'Penjadwalan berhasil Diubah!');
    }

    function delete(Request $request,$id)
    {
        $user = Penjadwalan::find($id);
        $user->delete();

        return redirect()->route('penjadwalan.index', ['id' => $request->jadwal])->with('success', 'Penjadwalan berhasil dihapus');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'telegram_id' => ['required'],
            'pesan' => ['required']
        ]);
        try {
            $this->messagesService->send($request->telegram_id,$request->pesan);
        } catch (ErrorException $e) {
            throw ValidationException::withMessages([
                'telegram_id' => $e->getMessage()
            ]);
        }
        return back();
    }

    public function sendNotificationManually()
    {
        app(PenjadwalanService::class)->sendNotificationAlert();
        return back();
    }

}
