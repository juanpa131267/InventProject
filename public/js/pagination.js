function renderPagination(data) {
    let paginationContainer = document.getElementById('pagination');
    paginationContainer.innerHTML = '';

    // Definir la cantidad de botones de página visibles
    const maxVisiblePages = 10;
    const currentPage = data.current_page;
    const lastPage = data.last_page;

    // Botón "Anterior"
    if (data.prev_page_url) {
        paginationContainer.innerHTML += `<button class="btn btn-primary mx-1" onclick="fetchMunicipios(document.getElementById('search-municipio').value, ${currentPage - 1})">Anterior</button>`;
    } else {
        paginationContainer.innerHTML += `<button class="btn btn-secondary mx-1" disabled>Anterior</button>`;
    }

    // Calcular el rango de páginas a mostrar
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(lastPage, startPage + maxVisiblePages - 1);

    // Ajustar el rango si estamos cerca del final
    if (endPage - startPage < maxVisiblePages - 1) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    // Mostrar el primer botón de página y "..." si hay una brecha
    if (startPage > 1) {
        paginationContainer.innerHTML += `<button class="btn btn-primary mx-1" onclick="fetchMunicipios(document.getElementById('search-municipio').value, 1)">1</button>`;
        if (startPage > 2) {
            paginationContainer.innerHTML += `<span class="mx-1">...</span>`;
        }
    }

    // Crear botones de página en el rango calculado
    for (let i = startPage; i <= endPage; i++) {
        if (i === currentPage) {
            paginationContainer.innerHTML += `<button class="btn btn-primary mx-1 active">${i}</button>`;
        } else {
            paginationContainer.innerHTML += `<button class="btn btn-primary mx-1" onclick="fetchMunicipios(document.getElementById('search-municipio').value, ${i})">${i}</button>`;
        }
    }

    // Mostrar "..." y el último botón de página si hay una brecha
    if (endPage < lastPage - 1) {
        paginationContainer.innerHTML += `<span class="mx-1">...</span>`;
    }
    if (endPage < lastPage) {
        paginationContainer.innerHTML += `<button class="btn btn-primary mx-1" onclick="fetchMunicipios(document.getElementById('search-municipio').value, ${lastPage})">${lastPage}</button>`;
    }

    // Botón "Siguiente"
    if (data.next_page_url) {
        paginationContainer.innerHTML += `<button class="btn btn-primary mx-1" onclick="fetchMunicipios(document.getElementById('search-municipio').value, ${currentPage + 1})">Siguiente</button>`;
    } else {
        paginationContainer.innerHTML += `<button class="btn btn-secondary mx-1" disabled>Siguiente</button>`;
    }
    }