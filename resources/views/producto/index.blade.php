@extends('layouts.adminlte')

@section('title', 'Gestión de Productos')
@section('page-title', 'Gestión de Productos')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="productoTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="productos-tab" data-toggle="tab" href="#productos" role="tab" aria-controls="productos" aria-selected="true">
                            <i class="fas fa-box"></i> Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="categorias-tab" data-toggle="tab" href="#categorias" role="tab" aria-controls="categorias" aria-selected="false">
                            <i class="fas fa-tags"></i> Categorías
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Éxito!</strong> {{ $message }}
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Error!</strong> {{ $message }}
                    </div>
                @endif

                <div class="tab-content">
                    <!-- Tab de Productos -->
                    <div class="tab-pane fade show active" id="productos" role="tabpanel" aria-labelledby="productos-tab">
                        <div class="mb-3">
                            <a href="{{ route('productos.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Crear Producto
                            </a>
                        </div>
                        
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Item</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productos as $producto)
                                <tr>
                                    <td>{{ $producto->id_producto }}</td>
                                    <td>{{ $producto->nombre }}</td>
                                    <td><span class="badge badge-primary">{{ $producto->categoria->nombre ?? 'N/A' }}</span></td>
                                    <td>{{ $producto->item->id_item ?? 'N/A' }}</td>
                                    <td>${{ number_format($producto->precio, 2) }}</td>
                                    <td>
                                        <a href="{{ route('productos.show', $producto->id_producto) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('productos.edit', $producto->id_producto) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('productos.destroy', $producto->id_producto) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este producto?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="card-footer">
                            {{ $productos->links() }}
                        </div>
                    </div>

                    <!-- Tab de Categorías -->
                    <div class="tab-pane fade" id="categorias" role="tabpanel" aria-labelledby="categorias-tab">
                        <div class="mb-3">
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalCategoria">
                                <i class="fas fa-plus"></i> Nueva Categoría
                            </button>
                        </div>
                        
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Productos Asociados</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categorias as $categoria)
                                <tr>
                                    <td>{{ $categoria->id_cat_producto }}</td>
                                    <td>{{ $categoria->nombre }}</td>
                                    <td>{{ $categoria->descripcion ?? 'Sin descripción' }}</td>
                                    <td><span class="badge badge-info">{{ $categoria->productos->count() }}</span></td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm btn-edit-categoria" 
                                                data-id="{{ $categoria->id_cat_producto }}"
                                                data-nombre="{{ $categoria->nombre }}"
                                                data-descripcion="{{ $categoria->descripcion }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('productos.categorias.destroy', $categoria->id_cat_producto) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta categoría?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="card-footer">
                            {{ $categorias->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar categoría -->
<div class="modal fade" id="modalCategoria" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCategoriaTitle">Nueva Categoría</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formCategoria" action="{{ route('productos.categorias.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="categoriaId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="255">
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" maxlength="500"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Editar categoría
        $('.btn-edit-categoria').on('click', function() {
            var id = $(this).data('id');
            var nombre = $(this).data('nombre');
            var descripcion = $(this).data('descripcion');
            
            $('#modalCategoriaTitle').text('Editar Categoría');
            $('#categoriaId').val(id);
            $('#nombre').val(nombre);
            $('#descripcion').val(descripcion);
            $('#formMethod').val('PUT');
            $('#formCategoria').attr('action', '/productos/categorias/' + id);
            $('#modalCategoria').modal('show');
        });
        
        // Resetear modal cuando se cierra
        $('#modalCategoria').on('hidden.bs.modal', function() {
            $('#modalCategoriaTitle').text('Nueva Categoría');
            $('#formCategoria')[0].reset();
            $('#formMethod').val('POST');
            $('#categoriaId').val('');
            $('#formCategoria').attr('action', '{{ route("productos.categorias.store") }}');
        });
    });
</script>
@endsection