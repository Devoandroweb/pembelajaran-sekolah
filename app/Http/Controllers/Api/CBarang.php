<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BarangRequest;
use App\Http\Resources\BarangResource;
use App\Http\Resources\ListWarningRefillResource;
use App\Models\MBarang;
use App\Models\Persediaan;
use App\Repositories\ApiHandle\ApiHandleRepository;
use App\Repositories\SystemEpic\SystemEpicRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CBarang extends Controller
{
    protected $apiHandleRepository;
    protected $systemEpicRepository;
    function __construct(
        ApiHandleRepository $apiHandleRepository,
        SystemEpicRepository $systemEpicRepository,
        ){
        $this->apiHandleRepository = $apiHandleRepository;
        $this->systemEpicRepository = $systemEpicRepository;
    }
    function list() {

        return $this->apiHandleRepository->safeApiCall(function(){
            $barang = MBarang::whereUser()->get();
            $barang = BarangResource::collection($barang);
            return responseSuccess($barang);
        });
    }
    function listWarningPersediaan() {

        return $this->apiHandleRepository->safeApiCall(function(){
            $listWarningRefillBarang = $this->systemEpicRepository->listWarningRefillBarang();
            $listWarningRefillBarang = ListWarningRefillResource::collection($listWarningRefillBarang);
            return responseSuccess($listWarningRefillBarang);
        });
    }
    function create(BarangRequest $barangRequest) {
        return $this->apiHandleRepository->safeApiCall(function() use ($barangRequest){
            $credentials = $barangRequest->validated();
            // dd($credentials);
            DB::transaction(function() use ($credentials) {
                // dd(request()->user()?->id_user);

                $barang = MBarang::create($credentials);
                Persediaan::create([
                    'id_barang' => $barang->id_barang,
                ]);
                updatedCreatedBy($barang);
            });
            DB::commit();
            return responseSuccess(['message'=>'Sukses Menambahkan Barang']);
        });

    }
    function edit(){
        return $this->apiHandleRepository->safeApiCall(function(){
            $barang = MBarang::whereIdBarang(request('id_barang'))->first();
            // dd(request('id_barang'),$barang);

            $barang = BarangResource::make($barang);
            return responseSuccess($barang);
        });
    }
    function update(BarangRequest $barangRequest) {
        return $this->apiHandleRepository->safeApiCall(function() use ($barangRequest){
            $credentials = $barangRequest->validated();
            MBarang::whereIdBarang($credentials['id_barang'])->update($credentials);
            if(isset($credentials['kode_barang'])){
                Persediaan::whereIdBarang($barangRequest->id_barang)->update([
                    'id_barang' => $credentials['id_barang'],
                ]);
            }
            return responseSuccess(['message'=>'Sukses Mengubah Barang']);
        });
    }
    function delete(){
        return $this->apiHandleRepository->safeApiCall(function(){
            $kode_barang = request('kode_barang');
            $barang = MBarang::whereKodeBarang($kode_barang)->first();
            $barang->delete();
            Persediaan::whereIdBarang($barang?->id_barang)->delete();

            return responseSuccess(['message'=>'Sukses Menghapus Barang']);
        });
    }
    function getBarangWithKategori($id_kategori){
        return $this->apiHandleRepository->safeApiCall(function()use($id_kategori){
            // dd($kode_barang);
            // dd($id_kategori);
            $barang = MBarang::where("id_kategori",$id_kategori)->get();
            $barang = BarangResource::collection($barang);
            return responseSuccess($barang);
        });
    }
    function dropdownSearch(){
        return $this->apiHandleRepository->safeApiCall(function(){
            // dd($kode_barang);
            $q = request()->query('q');
            $barang = MBarang::whereUser()->where("nama_barang","like","%".$q."%")->get();
            // dd($barang);
            $barang = BarangResource::collection($barang);
            return responseSuccess($barang);
        });
    }

}
