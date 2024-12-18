<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Legajos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            min-height: 100vh;
        }

        .navbar {
            background-color: black;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-content {
            max-width: 95%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-link {
            color:rgb(241, 244, 248);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logout-link {
            color: #dc2626;
            text-decoration: none;
        }

        .container {
            max-width: 95%;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .main-content {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        h1 {
            color: #111827;
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
        }

        .alert.success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .alert.error {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .add-button {
            display: inline-block;
            background-color: #059669;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            text-decoration: none;
            margin-bottom: 2rem;
            transition: background-color 0.2s;
        }

        .add-button:hover {
            background-color: #047857;
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-group label {
            font-size: 0.875rem;
            color: #374151;
            font-weight: 500;
        }

        .filter-group select,
        .filter-group input {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .table-container {
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        th {
            background-color: #f9fafb;
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            color: #111827;
        }

        .view-link {
            color: #2563eb;
            text-decoration: none;
        }

        .view-link:hover {
            text-decoration: underline;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .edit-button {
            background-color: #eab308;
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .delete-button {
            background-color: #dc2626;
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .per-page-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .per-page-container select {
            padding: 0.375rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .page-button {
            padding: 0.375rem 0.75rem;
            border: 1px solid #d1d5db;
            background-color: white;
            color: #374151;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .page-button:disabled {
            background-color: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
        }

        .current-page {
            padding: 0.375rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            width: 4rem;
            text-align: center;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .filters {
                grid-template-columns: 1fr;
            }

            .pagination-container {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-content">
            <a href="/" class="back-link">← Regresar al Panel de Administración</a>
            <a href="/logout" class="logout-link">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div class="main-content">
            <h1>Gestión de Legajos</h1>

            <?php if (isset($_GET['message'])): ?>
                <div class="alert success"><?= htmlspecialchars($_GET['message']) ?></div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert error"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

            <a href="/legajo/create" class="add-button">Agregar Nuevo Legajo</a>

            <div class="filters">
                <div class="filter-group">
                    <label for="tipoDocumento">Tipo Documento</label>
                    <select id="tipoDocumento">
                        <option value="">Todos</option>
                        <option value="1">DNI</option>
                        <option value="4">CE</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="numeroDocumento">Número Documento</label>
                    <input type="text" id="numeroDocumento" placeholder="Buscar...">
                </div>

                <div class="filter-group">
                    <label for="ejercicio">Ejercicio</label>
                    <select id="ejercicio">
                        <option value="">Todos</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="periodo">Periodo</label>
                    <select id="periodo">
                        <option value="">Todos</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="emitido">Emitido</label>
                    <select id="emitido">
                        <option value="">Todos</option>
                        <option value="1">Registrado</option>
                        <option value="0">Pendiente</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="subido">Subido</label>
                    <select id="subido">
                        <option value="">Todos</option>
                        <option value="1">Registrado</option>
                        <option value="0">Pendiente</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="fisico">Físico</label>
                    <select id="fisico">
                        <option value="">Todos</option>
                        <option value="1">Si</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo Documento</th>
                            <th>Número Documento</th>
                            <th>Apellidos y Nombres</th>
                            <th>Ejercicio</th>
                            <th>Periodo</th>
                            <th>Emitido</th>
                            <th>Subido</th>
                            <th>Físico</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="legajosTableBody">
                        <!-- Se llenará dinámicamente con JavaScript -->
                    </tbody>
                </table>
            </div>

            <div class="pagination-container">
                <div class="per-page-container">
                    <label for="perPage">Mostrar:</label>
                    <select id="perPage">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                    <span>registros por página</span>
                </div>

                <div class="pagination-controls">
                    <button id="firstPage" class="page-button">⟨⟨</button>
                    <button id="prevPage" class="page-button">⟨</button>
                    <span>Página</span>
                    <input type="number" id="currentPage" class="current-page" min="1" value="1">
                    <span>de <span id="totalPages">1</span></span>
                    <button id="nextPage" class="page-button">⟩</button>
                    <button id="lastPage" class="page-button">⟩⟩</button>
                </div>
            </div>
        </div>
    </div>

   <!-- El HTML anterior se mantiene igual hasta el script -->

<script>
    // Variables de estado
    let currentPage = 1;
    let perPage = 10;
    let totalPages = 1;
    let legajos = [];

    // Elementos del DOM
    const tableBody = document.getElementById('legajosTableBody');
    const perPageSelect = document.getElementById('perPage');
    const currentPageInput = document.getElementById('currentPage');
    const totalPagesSpan = document.getElementById('totalPages');
    const firstPageBtn = document.getElementById('firstPage');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const lastPageBtn = document.getElementById('lastPage');

    // Elementos de filtros
    const filters = {
        tipoDocumento: document.getElementById('tipoDocumento'),
        numeroDocumento: document.getElementById('numeroDocumento'),
        ejercicio: document.getElementById('ejercicio'),
        periodo: document.getElementById('periodo'),
        emitido: document.getElementById('emitido'),
        subido: document.getElementById('subido'),
        fisico: document.getElementById('fisico')
    };

    // Función para obtener el nombre del mes
    function getNombreMes(numero) {
        const meses = {
            '1': 'Enero', '2': 'Febrero', '3': 'Marzo', '4': 'Abril',
            '5': 'Mayo', '6': 'Junio', '7': 'Julio', '8': 'Agosto',
            '9': 'Septiembre', '10': 'Octubre', '11': 'Noviembre', '12': 'Diciembre'
        };
        return meses[numero] || '-';
    }

    // Función para cargar datos
    async function loadData() {
        const filterParams = new URLSearchParams({
            page: currentPage,
            per_page: perPage,
            tipo_documento: filters.tipoDocumento.value,
            n_documento: filters.numeroDocumento.value,
            ejercicio: filters.ejercicio.value,
            periodo: filters.periodo.value,
            emitido: filters.emitido.value,
            subido: filters.subido.value,
            fisico: filters.fisico.value
        });

        try {
            const response = await fetch(`/legajo/api?${filterParams.toString()}`);
            const result = await response.json();
            legajos = result.data;
            totalPages = result.pages;
            renderTable();
            updatePaginationControls();
        } catch (error) {
            console.error('Error al cargar datos:', error);
        }
    }

    // Función para renderizar la tabla
    function renderTable() {
        tableBody.innerHTML = '';

        if (legajos.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="10" style="text-align: center;">No hay registros disponibles.</td>
                </tr>
            `;
            return;
        }

        legajos.forEach(legajo => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${legajo.id}</td>
                <td>${legajo.tipo_documento === '1' ? 'DNI' : 'CE'}</td>
                <td>${legajo.n_documento}</td>
                <td>${legajo.apellidos_nombres || '-'}</td>
                <td>${legajo.ejercicio}</td>
                <td>${getNombreMes(legajo.periodo)}</td>
                <td>${legajo.emitido ? 
                    `<a href="${legajo.emitido}" class="view-link" target="_blank">Ver</a>` : 
                    '-'}</td>
                <td>${legajo.subido ? 
                    `<a href="${legajo.subido}" class="view-link" target="_blank">Ver</a>` : 
                    '-'}</td>
                <td>${legajo.fisico === 1 ? 'Si' : 'No'}</td>
                <td class="action-buttons">
                    <a href="/legajo/update/${legajo.id}" class="edit-button">Editar</a>
                    <button onclick="deleteLegajo(${legajo.id})" class="delete-button">Eliminar</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    // Función para actualizar controles de paginación
    function updatePaginationControls() {
        totalPagesSpan.textContent = totalPages;
        currentPageInput.value = currentPage;
        
        firstPageBtn.disabled = currentPage === 1;
        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === totalPages;
        lastPageBtn.disabled = currentPage === totalPages;
    }

    // Función para eliminar legajo
    async function deleteLegajo(id) {
        if (!confirm('¿Está seguro de eliminar este legajo?')) {
            return;
        }

        try {
            const response = await fetch('/legajo/delete/' + id, {
                method: 'DELETE'
            });

            if (response.ok) {
                // Si estamos en la última página y es el último registro,
                // retroceder una página
                if (currentPage > 1 && legajos.length === 1) {
                    currentPage--;
                }
                await loadData();
            } else {
                alert('Error al eliminar el legajo');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al eliminar el legajo');
        }
    }

    // Event Listeners para paginación
    perPageSelect.addEventListener('change', () => {
        perPage = parseInt(perPageSelect.value);
        currentPage = 1;
        loadData();
    });

    firstPageBtn.addEventListener('click', () => {
        if (currentPage !== 1) {
            currentPage = 1;
            loadData();
        }
    });

    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            loadData();
        }
    });

    nextPageBtn.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            loadData();
        }
    });

    lastPageBtn.addEventListener('click', () => {
        if (currentPage !== totalPages) {
            currentPage = totalPages;
            loadData();
        }
    });

    currentPageInput.addEventListener('change', () => {
        const newPage = parseInt(currentPageInput.value);
        if (newPage && newPage >= 1 && newPage <= totalPages) {
            currentPage = newPage;
            loadData();
        } else {
            currentPageInput.value = currentPage;
        }
    });

    // Event Listeners para filtros
    Object.values(filters).forEach(filter => {
        filter.addEventListener('change', () => {
            currentPage = 1;
            loadData();
        });

        // Para el campo de número de documento, filtrar mientras se escribe
        if (filter === filters.numeroDocumento) {
            filter.addEventListener('keyup', () => {
                currentPage = 1;
                loadData();
            });
        }
    });

    // Cargar datos iniciales
    loadData();
</script>
</body>
</html>