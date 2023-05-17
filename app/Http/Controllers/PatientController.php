<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use Exception;
use FFI\Exception as FFIExpection;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $search =$request->search_nama;
        $limit = $request->limit;
        $patients= Patient::where('nama', 'LIKE', '%'.$search.'%')->limit($limit)->get();
        
        
       // $patients = Patient :: all();
        if ($patients){
            return ApiFormatter::createAPI(200, 'success', $patients);
        }else{
            return ApiFormatter::createAPI(400,'failed');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|min:3',
                'umur' => 'required|numeric',
                'no_hp'=>'required|numeric',
                'riwayat_penyakit' => 'required',
                'terakhir_periksa'=> 'required',  
            ]);
             $patient = Patient::create([
                'nama' => $request->nama,
                'umur'=> $request->umur,
                'no_hp'=> $request->no_hp,
                'riwayat_penyakit' => $request->riwayat_penyakit,
                'terakhir_periksa' => \Carbon\Carbon::Parse($request->terakhir_periksa)->format('Y-m-d'),
             ]);
             $hasilTambahData = Patient::where('id', $patient->id)->first();
             if($hasilTambahData){
                return ApiFormatter::createAPI(200, 'success', $patient);
             }else{
                 return ApiFormatter::createAPI(400,'failed');
             }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error',$error->getMessage());
        }
    }
    public function createToken()
    {
        return csrf_token();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $patient = Patient::find($id);
            if ($patient){ 
                return ApiFormatter::createAPI(200, 'success', $patient);
            }else{
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama' => 'required|min:3',
                'umur' => 'required|numeric',
                'no_hp'=>'required|numeric',
                'riwayat_penyakit' => 'required',
                'terakhir_periksa'=> 'required',
            ]);
            $patient = Patient::find($id);
            $patient ->update([
                'nama' => $request->nama,
                'umur'=> $request->umur,
                'no_hp'=> $request->no_hp,
                'riwayat_penyakit' => $request->riwayat_penyakit,
                'terakhir_periksa' => \Carbon\Carbon::Parse($request->terakhir_periksa)->format('Y-m-d'),
             ]);
             $dataTerbaru = Patient::where('id',$patient->id)->first();
             if ($dataTerbaru){ 
                return ApiFormatter::createAPI(200, 'success', $patient);
            }else{
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage()); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $patient = Patient :: find($id);
            $cekBerhasil = $patient->delete();
            if ($cekBerhasil){
                return ApiFormatter::createAPI(200, 'success', 'Data Terhapuus!');
            }else{
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch(Exception $error){
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }
    public function trash ()
    {
        try{
            $patients=Patient::onlyTrashed()->get();
            if($patients){
                return ApiFormatter::createAPI(200, 'success', $patients);
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch(Exception $error){
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function restore($id)
    {
        try{
            $patient=Patient::onlyTrashed()->where('id', $id);
            $patient->restore();
            $dataKembali = Patient::where('id', $id)->first();
            if ($dataKembali) {
                return ApiFormatter::createAPI(200, 'success', $dataKembali);
            }else{
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch(Exception $error){
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function permanentDelete($id)
    {
        try {
            $patient = Patient::onlyTrashed()->where('id',$id);
            $proses =$patient->forceDelete();
            return ApiFormatter::createAPI(200, 'success', 'Berhasil hapus permanen!');
            

        }catch(Exception $error){
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }
}

