@extends('layouts.appWel')
@section('title', 'Rentalo - Crear Publicación')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <div class="position-relative d-inline-block mb-2">
            <div class="circle-shape bg-primary text-white p-4 shadow" style="width: 100px; height: 100px; border-radius: 50%;">
                <i class="mai-document fs-2"></i>
            </div>
        </div>
        <h2 class="mb-0 mt-2">Crear Publicación</h2>
        <p class="text-muted">Completa los formularios para publicar tu inmueble en Rentalo</p>
    </div>

    {{-- Mensajes de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    {{-- Mostrar mensajes de error de validación --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario de creación de inmueble --}}
    <div class="card mx-auto shadow-lg border-0" style="max-width: 750px;">
        <div class="card-body">
            <h4 class="text-center mb-4 text-primary">Información del Inmueble</h4>

            <form action="{{ route('publicationRegister') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="rolxpersona_id" value="{{ Auth::user()->id }}">

                {{-- Datos del inmueble --}}
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">Detalles del Inmueble</div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Ej: Pongo en alquiler habitación..." required></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="detalles_habitacion" class="form-label">Detalles de la Habitación <span class="text-danger">*</span></label>
                            <textarea name="detalles_habitacion" id="detalles_habitacion" class="form-control" rows="2" placeholder="Ej: 2 habitaciones, 1 baño privado, servicio de lavanderia, cocina comunitaria..." required></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="precio" class="form-label">Valor alquiler (Mensual) <span class="text-danger">*</span></label>
                            <input type="number" name="precio" id="precio" class="form-control" placeholder="Ej: $1.500.000" required>
                            <small id="precioFormatted" class="form-text text-muted"></small>
                        </div>

                    </div>
                </div>

                {{-- Datos de ubicación --}}
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">Ubicación</div>
                    <div class="card-body">
                        <input type="hidden" name="departamento_id" value="CUNDINAMARCA">
                        <input type="hidden" name="municipio_id" value="Girardot">
                        
                        <div class="form-group mb-3">
                            <label for="pais">País:</label>
                            <input type="text" name="pais" id="pais" value="Colombia" class="form-control" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="estado" class="form-label">Departamento</label>
                            <input type="text" name="estado" id="estado" value="CUNDINAMARCA" class="form-control" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="ciudad" class="form-label">Municipio</label>
                            <input type="text" name="ciudad" id="ciudad" value="Girardot" class="form-control" readonly>
                        </div>

                        {{-- (Oculta, se completará automáticamente) --}}
                        <input type="hidden" name="municipio_id" id="municipio_id" value="372" readonly>   
                        
                        <div class="form-group mb-3">
                            <label for="direccion" class="form-label">Dirección | Barrio <span class="text-danger">*</span></label>
                            <textarea name="direccion" id="direccion" class="form-control" rows="3" placeholder="Ej: Calle 10 # 5-67, Barrio Centro" required></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="coordenadas" class="form-label">Coordenadas (Lat, Long) <span class="text-danger">*</span></label>
                            <input type="text" name="coordenadas" id="coordenadas" class="form-control" placeholder="Ej: 4.624335, -74.063644" required>
                            <div class="text-center mt-2">
                                <p>¿No sabes las coordenadas? Busca en Google Maps.</p>
                                <a href="https://www.google.com/maps" target="_blank" class="btn btn-primary btn-sm">Buscar Coordenadas en Google Maps</a>
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#helpModal">Ayuda</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Imágenes del inmueble --}}
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">Imágenes del Inmueble</div>
                    <div class="card-body">
                        <div id="imageFields">
                            <div class="imageField mb-3">
                                <div class="text-center">
                                    <h5>Imagen 1</h5> <!-- Encabezado para la primera imagen -->
                                </div>
                                <div class="form-group">
                                    <label for="imagen" class="form-label">Subir Imagen <span class="text-danger">*</span></label>
                                    <input type="file" name="imagenes[]" class="form-control" required onchange="previewImage(event, this)">
                                </div>
                                <div class="form-group">
                                    <label for="descripcion_imagen" class="form-label">Descripción de la Imagen <span class="text-danger">*</span></label>
                                    <input type="text" name="descripcion_imagen[]" class="form-control" placeholder="Ej: Habitación" required>
                                </div>
                                <!-- Contenedor para la previsualización de la imagen -->
                                <div class="text-center mb-3">
                                <label for="Vista previa de la imagen" class="form-label"> Previsualización de la imagen cargada <span class="text-danger"></span></label>
                                    <img id="preview1" src="" alt="Vista previa de la imagen" class="img-fluid shadow-lg rounded" style="display: none; max-width: 50%; height: auto; margin: 0 auto;">
                                </div>
                                <button type="button" class="btn btn-danger cancelImageBtn" disabled>Eliminar imagen</button> <!-- Botón deshabilitado para la primera imagen -->
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" id="addImageBtn">Agregar otra imagen</button>
                    </div>
                </div>

                {{-- Contacto --}}
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">Opciones de Contacto</div>
                    <div class="card-body">
                        <p>Esta es la información de contacto que se mostrará en la publicación:</p>
                        <ul>
                            <li><strong>Teléfono:</strong> {{ Auth::user()->persona->telefono }}</li>
                            <li><strong>Email:</strong> {{ Auth::user()->persona->correo }}</li>
                        </ul>
                        <p class="text-muted">Si necesitas modificar esta información, puedes hacerlo en tu <a href="{{ route('perfil.edit') }}" target="_blank" class="text-primary">perfil</a>.</p>
                    </div>     
                </div>

                {{-- Términos y Condiciones --}}
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span>Términos y Condiciones</span>
                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Haz clic para más detalles"></i>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Para poder continuar con la publicación de tu inmueble, es necesario que aceptes nuestros términos y condiciones.</p>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="termsAndConditions" required>
                            <label class="form-check-label" for="termsAndConditions">
                                Acepto los <a href="{{ url('/terminosycondiciones') }}" target="_blank" class="text-primary">términos y condiciones</a> de la publicación.
                            </label>
                        </div>
                        <p class="text-muted small">Al aceptar, confirmas que has leído y comprendido nuestras políticas de uso.</p>
                    </div>
                </div>


                {{-- Botones de acción --}}
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary mx-2">
                        <i class="bi bi-save-fill"></i> Guardar
                    </button>
                    <a href="{{ url('/') }}" class="btn btn-danger mx-2">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal de Ayuda --}}
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel"><strong>Cómo obtener las coordenadas en Google Maps </strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Paso 1 -->
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <strong>Paso 1:</strong> Buscar las coordenadas
                    </div>
                    <div class="card-body">
                        <p>Haz clic en el botón "Buscar Coordenadas en Google Maps". Este te abrirá una nueva pestaña en Google Maps para que puedas buscar la ubicación de tu inmueble.</p>
                        <div class="text-center mb-3">
                            <img src="{{ asset('assetsWelcome/img/botonMaps.png') }}" alt="Botón para abrir Google Maps" class="img-fluid shadow-lg rounded">
                        </div>
                    </div>
                </div>

                <!-- Paso 2 -->
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <strong>Paso 2:</strong> Obtener las coordenadas
                    </div>
                    <div class="card-body">
                        <p>Una vez en Google Maps, busca la ubicación de tu inmueble, haz clic derecho sobre el lugar exacto y selecciona la opción <strong>"¿Qué hay aquí?"</strong>. Esto mostrará las coordenadas del lugar.</p>
                        <div class="text-center mb-3">
                            <img src="{{ asset('assetsWelcome/img/mapsClick.png') }}" alt="Instrucción en Google Maps" class="img-fluid shadow-lg rounded">
                        </div>
                    </div>
                </div>

                <!-- Paso 3 -->
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <strong>Paso 3:</strong> Copiar y pegar las coordenadas
                    </div>
                    <div class="card-body">
                        <p>Las coordenadas aparecerán en la parte inferior de la pantalla, copia las coordenadas en el formato <strong>Latitud, Longitud</strong> y pégalas en el campo correspondiente de este formulario.</p>
                        <div class="text-center">
                            <img src="{{ asset('assetsWelcome/img/mapsCoords.png') }}" alt="Coordenadas en Google Maps" class="img-fluid shadow-lg rounded">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>

// Agregar un nuevo formulario para subir una imagen y su descripción
document.getElementById('addImageBtn').addEventListener('click', function() {
    const container = document.getElementById('imageFields');
    const index = container.getElementsByClassName('imageField').length + 1; // Contador para el encabezado
    const newField = document.createElement('div');
    newField.classList.add('imageField', 'mb-3');
    newField.innerHTML = `
        <div class="text-center">
            <h5>Imagen ${index}</h5> <!-- Encabezado para cada imagen -->
        </div>
        <div class="form-group">
            <label for="imagen" class="form-label">Subir Imagen</label>
            <input type="file" name="imagenes[]" class="form-control" required onchange="previewImage(event, this)">
        </div>
        <div class="form-group">
            <label for="descripcion_imagen" class="form-label">Descripción de la Imagen</label>
            <input type="text" name="descripcion_imagen[]" class="form-control" placeholder="Descripción de la imagen" required>
        </div>
        <div class="text-center mb-3">
            <label for="Vista previa de la imagen" class="form-label"> Previsualización de la imagen cargada <span class="text-danger"></span></label>
            <img id="preview1" src="" alt="Vista previa de la imagen" class="img-fluid shadow-lg rounded" style="display: none; max-width: 50%; height: auto; margin: 0 auto;">
        </div>
        <button type="button" class="btn btn-danger cancelImageBtn">Eliminar imagen</button> <!-- Botón para eliminar imagen -->
    `;
    container.appendChild(newField);

    // Habilitar el botón de eliminar en la primera imagen solo después de agregar una segunda
    updateImageHeaders();
});

// Eliminar imagen
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('cancelImageBtn')) {
        e.target.closest('.imageField').remove(); // Eliminar el formulario de imagen
        updateImageHeaders(); // Reindexar encabezados
    }
});


// Función para actualizar el encabezado de las imágenes
function updateImageHeaders() {
    const imageFields = document.querySelectorAll('.imageField');
    imageFields.forEach((field, index) => {
        const deleteBtn = field.querySelector('.cancelImageBtn');
        field.querySelector('h5').textContent = `Imagen ${index + 1}`; // Actualizar el número en el encabezado
        // Habilitar el botón de eliminar solo si hay más de una imagen
        if (imageFields.length > 1) {
            deleteBtn.disabled = false;
        } else {
            deleteBtn.disabled = true;
        }
    });
}

document.getElementById('precio').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, ''); // Remover caracteres no numéricos
    let formattedValue = new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP' }).format(value);
    document.getElementById('precioFormatted').textContent = formattedValue; // Mostrar formato
    e.target.value = value; // Guardar solo el número en el input
});

document.getElementById('coordenadas').addEventListener('input', function (e) {
    const regex = /^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?((1[0-7]\d)|(\d{1,2}))(\.\d+)?$/;
    const value = e.target.value;
    if (!regex.test(value)) {
        e.target.setCustomValidity("Por favor ingresa coordenadas en formato válido: latitud, longitud.");
    } else {
        e.target.setCustomValidity("");
    }
});

// Habilitar el botón de guardar cuando la casilla de términos y condiciones esté marcada
document.getElementById('termsAndConditions').addEventListener('change', function() {
    document.getElementById('submitBtn').disabled = !this.checked;
});

// Función para previsualizar la imagen seleccionada
function previewImage(event, input) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = input.closest('.imageField').querySelector('img');
        output.src = reader.result;
        output.style.display = 'block'; // Mostrar la imagen
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

@endsection