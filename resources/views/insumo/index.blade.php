@extends('layouts.adminlte')

@section('title', 'Gestión de Insumos')
@section('page-title', 'Gestión de Insumos')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="insumoTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="insumos-tab" data-toggle="tab" href="#insumos" role="tab" aria-controls="insumos" aria-selected="true">
                            <i class="fas fa-boxes"></i> Insumos
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
                        <strong>¡Éxito!</strong> {{ $message }}
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>¡Error!</strong> {{ $message }}
                    </div>
                @endif

                <div class="tab-content">
                    <!-- Tab de Insumos -->
                    <div class="tab-pane fade show active" id="insumos" role="tabpanel" aria-labelledby="insumos-tab">
                        <div class="mb-3">
                            <a href="{{ route('insumos.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Crear Insumo
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="30%">Nombre</th>
                                        <th width="20%">Categoría</th>
                                        <th width="15%">Unidad de Medida</th>
                                        <th width="15%">Precio Compra</th>
                                        <th width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($insumos as $insumo)
                                    <tr>
                                        <td>{{ $insumo->id_insumo }}</td>
                                        <td>
                                            <strong>{{ $insumo->item->nombre ?? 'N/A' }}</strong>
                                            <br>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $insumo->categoria->nombre ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $insumo->item->unidad_medida ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">${{ number_format($insumo->precio_compra, 2) }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('insumos.edit', $insumo->id_insumo) }}" class="btn btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('insumos.destroy', $insumo->id_insumo) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este insumo?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                                                No hay insumos registrados
                                            </td
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($insumos->hasPages())
                            <div class="card-footer clearfix">
                                <div class="float-right">
                                    {{ $insumos->links() }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Tab de Categorías -->
                    <div class="tab-pane fade" id="categorias" role="tabpanel" aria-labelledby="categorias-tab">
                        <div class="mb-3">
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalCategoria">
                                <i class="fas fa-plus"></i> Nueva Categoría
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="30%">Nombre</th>
                                        <th width="35%">Descripción</th>
                                        <th width="15%">Insumos</th>
                                        <th width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categorias as $categoria)
                                    <tr>
                                        <td>{{ $categoria->id_cat_insumo }}</td>
                                        <td>
                                            <strong>{{ $categoria->nombre }}</strong>
                                        </td>
                                        <td>
                                            {{ $categoria->descripcion ?? 'Sin descripción' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $categoria->insumos->count() }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-warning btn-sm btn-edit-categoria"
                                                        data-id="{{ $categoria->id_cat_insumo }}"
                                                        data-nombre="{{ $categoria->nombre }}"
                                                        data-descripcion="{{ $categoria->descripcion }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('insumos.categorias.destroy', $categoria->id_cat_insumo) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta categoría?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="fas fa-tags fa-3x mb-3 d-block"></i>
                                                No hay categorías registradas
                                            </td
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($categorias->hasPages())
                            <div class="card-footer clearfix">
                                <div class="float-right">
                                    {{ $categorias->links() }}
                                </div>
                            </div>
                        @endif
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
            <form id="formCategoria" action="{{ route('insumos.categorias.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="categoriaId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="255">
                        <small class="text-muted">Ej: Harinas, Lácteos, Huevos</small>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" maxlength="500"></textarea>
                        <small class="text-muted">Describe brevemente esta categoría</small>
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
            $('#formCategoria').attr('action', '/insumos/categorias/' + id);
            $('#modalCategoria').modal('show');
        });

        // Resetear modal cuando se cierra
        $('#modalCategoria').on('hidden.bs.modal', function() {
            $('#modalCategoriaTitle').text('Nueva Categoría');
            $('#formCategoria')[0].reset();
            $('#formMethod').val('POST');
            $('#categoriaId').val('');
            $('#formCategoria').attr('action', '{{ route("insumos.categorias.store") }}');
        });
    });
</script>
@endsection
