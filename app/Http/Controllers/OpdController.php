<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use Illuminate\Http\Request;

class OpdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $opds = Opd::withCount('accounts')->orderBy('nama', 'asc')->get();
        return view('opds.index', compact('opds'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:opds,nama',
        ]);

        Opd::create($request->all());

        return redirect()->route('opds.index')->with('success', 'OPD berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Opd $opd)
    {
        $request->validate([
            'nama' => 'required|unique:opds,nama,' . $opd->id,
        ]);

        $opd->update($request->all());

        return redirect()->route('opds.index')->with('success', 'OPD berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Opd $opd)
    {
        if ($opd->accounts()->count() > 0) {
            return back()->with('error', "OPD {$opd->nama} tidak bisa dihapus karena masih digunakan oleh akun Inaproc.");
        }
        $opd->delete();
        return redirect()->route('opds.index')->with('success', 'OPD berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih.');
        }

        $opds = Opd::whereIn('id', $ids)->get();
        $deletedCount = 0;
        $failedNames = [];

        foreach ($opds as $opd) {
            if ($opd->accounts()->count() > 0) {
                $failedNames[] = $opd->nama;
                continue;
            }
            $opd->delete();
            $deletedCount++;
        }

        $msg = "Berhasil menghapus $deletedCount data OPD.";
        if (count($failedNames) > 0) {
            return redirect()->route('opds.index')->with('success', $msg)
                             ->with('error', 'Gagal menghapus beberapa OPD karena masih digunakan: ' . implode(', ', $failedNames));
        }

        return redirect()->route('opds.index')->with('success', $msg);
    }
}
