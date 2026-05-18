<?php

namespace App\Http\Controllers;

use App\Models\AlmacenItem;
use App\Models\Almacen;
use App\Models\Item;
use Illuminate\Http\Request;

class AlmacenItemController extends Controller
{
    /**
     * Display a listing of stock with filters and stats.
     */
    public function index(Request $request)
    {
        $filtroTipo = $request->get('tipo', 'todos');
        $buscar = $request->get('buscar', '');
        $orden = $request->get('orden', 'almacen');

        // Cargar con relaciones (sin joins)
        $query = AlmacenItem::with(['almacen', 'item']);

        // Filtrar por tipo de almacén
        if ($filtroTipo !== 'todos') {
            $query->whereHas('almacen', function ($q) use ($filtroTipo) {
                $q->where('tipo_almacen', $filtroTipo);
            });
        }

        // Búsqueda
        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->whereHas('almacen', function ($q2) use ($buscar) {
                    $q2->where('nombre', 'like', "%{$buscar}%");
                })->orWhereHas('item', function ($q3) use ($buscar) {
                    $q3->where('nombre', 'like', "%{$buscar}%");
                });
            });
        }

        // Ordenación (usando with y sort, sin joins problemáticos)
        $almacenItems = $query->get();

        // Ordenar después de obtener
        switch ($orden) {
            case 'item':
                $almacenItems = $almacenItems->sortBy(function($ai) {
                    return $ai->item->nombre ?? '';
                });
                break;
            case 'stock':
                $almacenItems = $almacenItems->sortByDesc('stock');
                break;
            default: // almacen
                $almacenItems = $almacenItems->sortBy(function($ai) {
                    return $ai->almacen->nombre ?? '';
                });
                break;
        }

        // Paginar manualmente
        $page = request()->get('page', 1);
        $perPage = 15;
        $total = $almacenItems->count();
        $almacenItems = $almacenItems->forPage($page, $perPage);
        
        // Crear paginador manual
        $almacenItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $almacenItems->values(),
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Estadísticas
        $totalItems = Item::count();
        $totalProductos = Item::where('tipo_item', 'producto')->count();
        $totalInsumos = Item::where('tipo_item', 'insumo')->count();

        // Totales de stock por tipo
        $totalInsumoStock = AlmacenItem::whereHas('almacen', fn($q) => $q->where('tipo_almacen', 'insumo'))->sum('stock');
        $totalProductoStock = AlmacenItem::whereHas('almacen', fn($q) => $q->where('tipo_almacen', 'producto'))->sum('stock');
        $totalMixtoStock = AlmacenItem::whereHas('almacen', fn($q) => $q->where('tipo_almacen', 'mixto'))->sum('stock');

        return view('almacen-item.index', compact(
            'almacenItems',
            'filtroTipo',
            'buscar',
            'orden',
            'totalItems',
            'totalProductos',
            'totalInsumos',
            'totalInsumoStock',
            'totalProductoStock',
            'totalMixtoStock'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $almacenes = Almacen::all();
        $items = Item::all();
        
        return view('almacen-item.create', compact('almacenes', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_almacen' => 'required|exists:almacenes,id_almacen',
            'id_item' => 'required|exists:items,id_item',
            'stock' => 'required|numeric|min:0',
        ]);

        AlmacenItem::create($validated);

        return redirect()->route('almacen-items.index')
            ->with('success', 'Almacén-Item creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($almacen, $item)
    {
        $almacenItem = AlmacenItem::where('id_almacen', $almacen)
            ->where('id_item', $item)
            ->with(['almacen', 'item'])
            ->firstOrFail();
        
        return view('almacen-item.show', compact('almacenItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($almacen, $item)
    {
        $almacenItem = AlmacenItem::where('id_almacen', $almacen)
            ->where('id_item', $item)
            ->firstOrFail();
        
        $almacenes = Almacen::all();
        $items = Item::all();
        
        return view('almacen-item.edit', compact('almacenItem', 'almacenes', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $almacen, $item)
    {
        $almacenItem = AlmacenItem::where('id_almacen', $almacen)
            ->where('id_item', $item)
            ->firstOrFail();

        $validated = $request->validate([
            'stock' => 'required|numeric|min:0',
        ]);

        $almacenItem->update($validated);

        return redirect()->route('almacen-items.index')
            ->with('success', 'Almacén-Item actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($almacen, $item)
    {
        $almacenItem = AlmacenItem::where('id_almacen', $almacen)
            ->where('id_item', $item)
            ->firstOrFail();

        $almacenItem->delete();

        return redirect()->route('almacen-items.index')
            ->with('success', 'Almacén-Item eliminado exitosamente');
    }
}
