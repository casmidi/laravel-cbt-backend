<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SoalResource;
use App\Models\Soal;
use App\Models\Ujian;
use App\Models\UjianSoalList;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //create ujian
    public function createUjian(Request $request)
    {
        //get 20 soal angka random
        // $soalAngka = \App\Models\Soal::inRandomOrder()->limit(20)->get();
        $soalAngka = Soal::where ('kategori','numeric')->inRandomOrder()->limit(20)->get();
        // get 20 soal verbal random
        $soalVerbal = Soal::where('kategori','verbal')->inRandomOrder()->limit(20)->get();
        // get 20 soal logika random
        $soalLogika = Soal::where('kategori','logika')->inRandomOrder()->limit(20)->get();

        // create ujian
        $ujian = Ujian::create([
            'user_id' => $request->user()->id,
        ]);

        //attach soal angka

        foreach ($soalAngka as $soal) {
            UjianSoalList::create([
                'ujian_id' => $ujian->id,
                'soal_id' => $soal->id,
            ]);
        }

        return response()->json([
            'message' => 'Ujian berhasil dibuat',
            'data'  => $ujian,
        ]);
    }

        //get list soal by kategori
    public function getListSoalByKategori(Request $request)
    {
        $ujian = Ujian::where('user_id',$request->user()->id)->first();
        $ujianSoalList = UjianSoalList::where('ujian_id',$ujian->id)->get();
        $soalIds = $ujianSoalList->pluck('soal_id');

        // $ujianSoalListId = [];
        // dd($soalIds);

        // foreach ($ujianSoalList as $soal)
        // {
        //     array_push($ujianSoalListId, $soal->soal_id);
        // }

        // $soal = Soal::whereIn('id',$ujianSoalListId)->where('kategori',$request->kategori)->get();

         $soal = Soal::whereIn('id',$soalIds)->where('kategori',$request->kategori)->get();


        return response()->json([
            'message' => 'Berhasil mendapatkan soal',
            'data' =>SoalResource::collection($soal),
        ]);
    }

    //jawab soal
    public function jawabSoal(Request $request)
    {
        $validateData = $request->validate([
            'soal_id'   => 'required',
            'jawaban'   => 'required',
        ]);

        $ujian = Ujian::where('user_id',$request->user()->id)->first();
        $ujianSoalList = UjianSoalList::where('ujian_id',$ujian->id)->where('soal_id',$validateData['soal_id'])->first();
        $soal = Soal::where('id',$validateData['soal_id'])->first();

        //cek_jawaban
        if ($soal->kunci == $validateData['jawaban']) {
            $ujianSoalList->kebenaran = true;
            $ujianSoalList->update([
                'kebenaran' => true
            ]);
        }else {
            $ujianSoalList->kebenaran = false;
            $ujianSoalList->update([
                'kebenaran' => false
            ]);
        }

        return response()->json([
            'message' => 'Berhasil simpan jawaban',
            'jawaban' => $ujianSoalList->kebenaran,
        ]);
    }
}
