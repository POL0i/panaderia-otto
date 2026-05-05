{{-- resources/views/modulo-almacen/partials/modal-producto.blade.php --}}
<div class="modal fade" id="createProductoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">
                    <i class="fas fa-box"></i> Nuevo Producto
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateProducto" action="{{ route('modulo-almacen.productos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        {{-- Nombre del producto --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre del Producto <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="productoNombre" class="form-control" 
                                       placeholder="Ej: Pan Francés, Tarta de Manzana..." required>
                            </div>
                        </div>
                        {{-- Precio --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Precio de Venta <span class="text-danger">*</span></label>
                                <input type="number" name="precio" id="productoPrecio" class="form-control" 
                                       step="0.01" min="0" placeholder="0.00" required>
                            </div>
                        </div>
                        {{-- Unidad de Medida --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Unidad de Medida <span class="text-danger">*</span></label>
                                <select name="unidad_medida" id="productoUnidad" class="form-control" required>
                                    <option value="kg">Kilogramos (kg)</option>
                                    <option value="g">Gramos (g)</option>
                                    <option value="lb">Libras (lb)</option>
                                    <option value="oz">Onzas (oz)</option>
                                    <option value="L">Litros (L)</option>
                                    <option value="mL">Mililitros (mL)</option>
                                    <option value="unidad">Unidad</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        {{-- Categoría --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Categoría <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="id_cat_producto" id="productoCategoria" class="form-control" required>
                                        <option value="">Seleccionar categoría...</option>
                                        @foreach($categorias ?? [] as $categoria)
                                            <option value="{{ $categoria->id_cat_producto }}">{{ $categoria->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-info" 
                                                data-toggle="modal" 
                                                data-target="#createCategoriaProductoModal"
                                                data-dismiss="modal">
                                            <i class="fas fa-plus"></i> Nueva
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Imagen --}}
                    <div class="form-group">
                        <label>Imagen del Producto</label>
                        <div class="card">
                            <div class="card-body">
                                <div class="image-preview mb-2 text-center" id="localImagePreview" style="display: none;">
                                    <img id="localPreviewImg" src="" alt="Vista previa" style="max-width: 150px; max-height: 150px; border-radius: 5px;">
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="productoImagen" name="imagen" accept="image/*">
                                    <label class="custom-file-label" for="productoImagen">
                                        <i class="fas fa-upload"></i> Seleccionar imagen
                                    </label>
                                </div>
                                <small class="text-muted">
                                    Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Se creará automáticamente un registro en Items como "producto" con la unidad de medida seleccionada.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Crear Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.image-preview {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 10px;
}
.custom-file-label::after {
    content: "Examinar";
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    $('#productoImagen').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#localPreviewImg').attr('src', e.target.result);
                $('#localImagePreview').show();
            }
            reader.readAsDataURL(file);
        } else {
            $('#localImagePreview').hide();
        }
    });
    
    $('.custom-file-input').on('change', function() {
        const fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
});
</script>
@endpush