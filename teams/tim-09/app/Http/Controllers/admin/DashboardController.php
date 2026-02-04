<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resto;
use App\Models\Menu;
use App\Models\Review;
use App\Models\Kota;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        //Data Statistik Utama
        $totalResto  = Resto::count();
        $totalMenu   = Menu::count();
        $totalReview = Review::count();
        $totalKota   = Kota::count();
        $latestReviews = Review::with('menu.resto')
                                ->latest()
                                ->take(5)
                                ->get();
        //Chart.js
        $chartData = DB::table('menus')
            ->join('restos', 'menus.id_resto', '=', 'restos.id_resto')
            ->join('kotas', 'restos.id_kota', '=', 'kotas.id_kota')
            ->select('kotas.nama_kota', DB::raw('count(menus.id_menu) as total'))
            ->groupBy('kotas.nama_kota')
            ->get();

        //Pisahkan label (nama kota) dan data (jumlah) untuk Chart.js
        $chartLabels = $chartData->pluck('nama_kota');
        $chartValues = $chartData->pluck('total');

        return view('admin.dashboard', compact(
            'totalResto', 
            'totalMenu', 
            'totalReview', 
            'totalKota',
            'latestReviews',
            'chartLabels',
            'chartValues'
        ));
    }
}