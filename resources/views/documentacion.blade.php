<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documentación EDLibre - Administrador</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            position: relative;
        }

        .docs-header {
            background-color: #0f172a;
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1040;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        .sidebar {
            position: sticky;
            top: 5rem;
            height: calc(100vh - 5rem);
            overflow-y: auto;
            padding-top: 1rem;
            padding-bottom: 2rem;
            border-right: 1px solid #e2e8f0;
        }

        .nav-pills .nav-link {
            color: #64748b;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .nav-pills .nav-link:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .nav-pills .nav-link.active {
            background-color: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }

        .content-section {
            padding-top: 2rem;
            padding-bottom: 3rem;
        }

        h1, h2, h3 {
            color: #0f172a;
            font-weight: 700;
        }

        h2 {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 0.5rem;
            margin-top: 2rem;
            margin-bottom: 1.5rem;
        }

        .card {
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1), 0 1px 2px 0 rgba(0,0,0,0.06);
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .step-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background-color: #2563eb;
            color: white;
            border-radius: 50%;
            font-weight: bold;
            margin-right: 0.75rem;
            font-size: 0.875rem;
        }
        
        code {
            background-color: #f1f5f9;
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            color: #b91c1c;
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target="#docsSidebar" data-bs-offset="100" tabindex="0">

    <header class="docs-header">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ url('/') }}" class="text-white text-decoration-none">
                    <img src="{{ asset('logo_horizontal.svg') }}" alt="EDLibre" height="40" onerror="this.src=''; this.alt='EDLibre';">
                </a>
                <span class="border-start border-secondary ps-3 ms-1 text-secondary fw-semibold">Documentación</span>
            </div>
            <div>
                <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">Volver al Inicio</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 d-none d-lg-block">
                <nav id="docsSidebar" class="sidebar">
                    <div class="px-3 mb-4">
                        <h5 class="fw-bold mb-3 text-dark">Guía del Administrador</h5>
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="#intro"><i class="bi bi-info-circle me-2"></i>Introducción</a>
                            </li>
                            <li class="nav-item mt-3 mb-1">
                                <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">1. Configuración Base</span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#usuarios"><i class="bi bi-people me-2"></i>Gestión de Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#dependencias"><i class="bi bi-building me-2"></i>Dependencias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#niveles"><i class="bi bi-layers me-2"></i>Niveles Jerárquicos</a>
                            </li>
                            <li class="nav-item mt-3 mb-1">
                                <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">2. Catálogo EDL</span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#competencias"><i class="bi bi-star me-2"></i>Competencias y Conductas</a>
                            </li>
                            <li class="nav-item mt-3 mb-1">
                                <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">3. Ciclo de Evaluación</span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#periodos"><i class="bi bi-calendar-check me-2"></i>Apertura de Periodos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#evaluadores"><i class="bi bi-person-badge me-2"></i>Asignación de Evaluadores</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#evaluados"><i class="bi bi-person-lines-fill me-2"></i>Registro de Evaluados</a>
                            </li>
                        </ul>
                        
                        <hr class="my-4 border-secondary border-opacity-25">
                        
                        <h5 class="fw-bold mb-3 text-dark">Guía del Evaluador</h5>
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="#mis-evaluados"><i class="bi bi-people-fill me-2"></i>Mis Evaluados</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#concertacion-evaluador"><i class="bi bi-handshake me-2"></i>Concertación</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#evidencias-evaluador"><i class="bi bi-folder-check me-2"></i>Evidencias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#evaluacion-definitiva"><i class="bi bi-clipboard2-data me-2"></i>Evaluación Definitiva</a>
                            </li>
                        </ul>
                        
                        <hr class="my-4 border-secondary border-opacity-25">
                        
                        <h5 class="fw-bold mb-3 text-dark">Guía del Evaluado</h5>
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="#mis-compromisos"><i class="bi bi-list-task me-2"></i>Mis Compromisos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#concertacion-evaluado"><i class="bi bi-handshake me-2"></i>Concertación</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#evidencias-evaluado"><i class="bi bi-folder-plus me-2"></i>Registro de Evidencias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#evaluacion-notificacion"><i class="bi bi-check-circle me-2"></i>Notificación y Aceptación</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

            <!-- Content -->
            <div class="col-lg-9 content-section ps-lg-5">
                
                <!-- Introducción -->
                <section id="intro" class="mb-5">
                    <h1 class="display-5 fw-bold mb-3">Parametrización del Sistema EDLibre</h1>
                    <p class="lead text-muted">Manual detallado para el rol de <strong>Administrador del Sistema</strong>. Aquí aprenderá paso a paso cómo configurar y poner en marcha el proceso de Evaluación del Desempeño Laboral (LNR) para su entidad.</p>
                    
                    <div class="alert alert-primary d-flex align-items-center mt-4" role="alert">
                        <i class="bi bi-lightbulb-fill fs-4 me-3"></i>
                        <div>
                            <strong>Flujo recomendado de configuración:</strong><br>
                            Para asegurar la integridad referencial de los datos, siga el orden exacto de este manual: cree primero los Usuarios, luego las Dependencias y Niveles, después parametrize las Competencias y finalmente abra un Periodo para vincular a los Evaluadores y Evaluados.
                        </div>
                    </div>
                </section>

                <!-- Usuarios -->
                <section id="usuarios" class="mb-5">
                    <h2><span class="step-badge">1</span> Gestión de Usuarios</h2>
                    <p>El módulo de usuarios es la base del sistema. Todo servidor público que vaya a interactuar con EDLibre (sea para administrar, evaluar o ser evaluado) debe existir previamente como usuario.</p>
                    
                    <div class="card p-4">
                        <h5 class="fw-bold"><i class="bi bi-person-plus text-primary me-2"></i> Crear o Editar un Usuario</h5>
                        <ul class="mb-0 text-muted">
                            <li class="mb-2">Navegue a <strong>Administración > Usuarios</strong>. Aquí podrá visualizar todos los usuarios que tienen cuenta en la plataforma.</li>
                            <li class="mb-2">Haga clic en el botón <strong>"Nuevo Usuario"</strong> (o utilice el botón de editar en un registro existente).</li>
                            <li class="mb-2">Gestione los datos personales: Tipo y Número de Documento, Nombre Completo, Correo Electrónico Institucional y Contraseña.</li>
                            <li><strong>Definir Administradores del Sistema:</strong>
                                <ul class="mt-2">
                                    <li>En el formulario encontrará el interruptor <strong>"¿Es Administrador?"</strong>.</li>
                                    <li>Actívelo únicamente si desea otorgar privilegios de <code>admin</code> (control total de configuración) a ese usuario.</li>
                                    <li><span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 mt-1"><i class="bi bi-info-circle me-1"></i> Nota: Los roles para evaluar o ser evaluado se asignan en sus propios módulos (Pasos 6 y 7).</span></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </section>

                <!-- Dependencias -->
                <section id="dependencias" class="mb-5">
                    <h2><span class="step-badge">2</span> Estructura de Dependencias</h2>
                    <p>Las dependencias representan la estructura organizacional de su entidad (Despachos, Direcciones, Secretarías, Oficinas, etc.). Es fundamental para enrutar correctamente quién evalúa a quién.</p>
                    
                    <div class="card p-4 border-start border-4 border-info">
                        <h5 class="fw-bold"><i class="bi bi-diagram-2 text-info me-2"></i> Creación Jerárquica</h5>
                        <p class="text-muted mb-2">Al crear una dependencia, puede (opcionalmente) seleccionar una <strong>"Dependencia Padre"</strong>.</p>
                        <ul class="text-muted mb-0">
                            <li>Si es la máxima autoridad (ej. Despacho del Gobernador), deje el campo de "Dependencia Padre" en blanco.</li>
                            <li>Si es una oficina subordinada (ej. Oficina de Talento Humano), asigne como padre a la Secretaría o Dirección correspondiente.</li>
                        </ul>
                    </div>
                </section>

                <!-- Niveles -->
                <section id="niveles" class="mb-5">
                    <h2><span class="step-badge">3</span> Niveles Jerárquicos</h2>
                    <p>Acorde a la normatividad para empleos de Libre Nombramiento y Remoción, el sistema requiere clasificar los cargos en niveles.</p>
                    
                    <div class="card p-4 bg-light">
                        <p class="mb-0">Debe registrar al menos los 4 niveles base que establece la normativa colombiana para efectos de evaluación:</p>
                        <ol class="fw-bold mt-2 mb-0">
                            <li>Nivel Asesor</li>
                            <li>Nivel Profesional</li>
                            <li>Nivel Técnico</li>
                            <li>Nivel Asistencial</li>
                        </ol>
                        <small class="text-muted mt-2 d-block">* Los porcentajes de la evaluación (Funcional 85% / Comportamental 15%) aplican de manera estándar para estos niveles en el modelo EDLibre.</small>
                    </div>
                </section>

                <!-- Competencias -->
                <section id="competencias" class="mb-5">
                    <h2><span class="step-badge">4</span> Competencias y Conductas Comportamentales</h2>
                    <p>Este módulo es el núcleo cualitativo de la evaluación. Aquí se define el catálogo de competencias comportamentales que serán evaluadas, las cuales equivalen al 15% del peso total de la EDL.</p>
                    
                    <div class="card p-4 mb-3">
                        <h5 class="fw-bold text-success"><i class="bi bi-star-fill me-2"></i> 4.1. Catálogo de Competencias</h5>
                        <p class="text-muted">Para agregar una competencia:</p>
                        <ul class="text-muted mb-0">
                            <li>Navegue a <strong>Administración > Competencias</strong>.</li>
                            <li>Al crearla, asígnele un nombre y descripción normativa.</li>
                            <li><strong>Asignación de Nivel:</strong> Usted puede ligar la competencia a un nivel específico (ej. Solo "Nivel Asesor") o marcarla como transversal para todos los niveles de la entidad.</li>
                        </ul>
                    </div>

                    <div class="card p-4">
                        <h5 class="fw-bold text-primary"><i class="bi bi-list-check me-2"></i> 4.2. Registro de Conductas</h5>
                        <p class="text-muted">Cada competencia requiere <strong>Conductas Asociadas</strong> (los ítems que el evaluador calificará de 0 a 100).</p>
                        <ul class="text-muted mb-0">
                            <li>Haga clic en el botón <span class="badge bg-primary">Conductas</span> ubicado en la fila de la competencia recién creada.</li>
                            <li>Agregue uno por uno los comportamientos esperados (ej. "Aporta elementos para la toma de decisiones").</li>
                            <li>El sistema promediará automáticamente estas conductas durante el proceso de calificación.</li>
                        </ul>
                    </div>
                </section>

                <!-- Periodos -->
                <section id="periodos" class="mb-5">
                    <h2><span class="step-badge">5</span> Apertura de Periodos</h2>
                    <p>El sistema trabaja por "Vigencias" anuales o semestrales. Sin un periodo de evaluación activo, no es posible concertar ni evaluar compromisos.</p>
                    
                    <div class="card p-4 border-warning border-start border-4">
                        <h5 class="fw-bold"><i class="bi bi-calendar-range text-warning me-2"></i> Configurar el Periodo</h5>
                        <ul class="text-muted mb-0">
                            <li>Diríjase a <strong>Administración > Periodos</strong>.</li>
                            <li>Defina un nombre claro (ej. <code>Vigencia 2026 - LNR</code>).</li>
                            <li>Establezca la <strong>Fecha de Inicio</strong> y <strong>Fecha de Fin</strong>.</li>
                            <li>Asegúrese de marcar la casilla <strong>Activo</strong>. Solo puede haber un periodo LNR de uso simultáneo para evitar solapamiento de calificaciones.</li>
                        </ul>
                    </div>
                </section>

                <!-- Evaluadores -->
                <section id="evaluadores" class="mb-5">
                    <h2><span class="step-badge">6</span> Asignación de Evaluadores</h2>
                    <p>Una vez creada la estructura y el periodo, el sistema necesita saber quién es el jefe inmediato (Evaluador) en cada Dependencia.</p>
                    
                    <div class="card p-4">
                        <p class="text-muted">Navegue a <strong>Configuración EDL > Evaluadores</strong> y haga clic en <strong>Asignar Evaluador</strong>.</p>
                        <p class="fw-bold mb-2">Para asignar un evaluador, configure lo siguiente:</p>
                        <ul class="text-muted">
                            <li class="mb-2"><strong>El Usuario:</strong> Usted puede seleccionar un usuario existente, o bien, utilizar la opción <strong>"Crear Nuevo"</strong>. Si utiliza esta opción, registrará directamente la cuenta de acceso en el modelo <code>User</code> (documento, correo, contraseña), lo que habilitará de inmediato al funcionario para iniciar sesión en la plataforma.</li>
                            <li class="mb-2"><strong>La Dependencia:</strong> Seleccione de qué oficina/dirección es jefe.</li>
                            <li><strong>Cargo y Fechas:</strong> Indique el cargo literal (ej. Secretario de Salud) y las fechas de ingreso/posesión (o retiro, si ya no está en el cargo).</li>
                        </ul>
                        <div class="alert alert-secondary mt-3 mb-0 p-2 text-center small">
                            <i class="bi bi-info-circle me-1"></i> Esta parametrización otorgará el rol de evaluación y permitirá a dicho usuario visualizar en su panel a todos los subordinados de su dependencia.
                        </div>
                    </div>
                </section>

                <!-- Evaluados -->
                <section id="evaluados" class="mb-5">
                    <h2><span class="step-badge">7</span> Registro de Evaluados (Sujetos de EDL)</h2>
                    <p>El último paso es registrar formalmente a los funcionarios de Libre Nombramiento y Remoción en el periodo de evaluación abierto.</p>
                    
                    <div class="card p-4">
                        <p class="text-muted">Vaya a <strong>Configuración EDL > Evaluados</strong> y registre al funcionario cruzando la siguiente información estructural:</p>
                        
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Campo</th>
                                        <th>Impacto en el Sistema</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold text-nowrap">Usuario (Evaluado)</td>
                                        <td class="text-muted">Otorga el acceso para que el funcionario pueda proponer compromisos funcionales y subir evidencias.</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Dependencia</td>
                                        <td class="text-muted">Enruta automáticamente a este evaluado hacia la bandeja del Evaluador asignado a la misma dependencia (Paso 6).</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nivel Jerárquico</td>
                                        <td class="text-muted">Define qué competencias comportamentales (Paso 4) le aplicarán en el formulario de calificación.</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Periodo</td>
                                        <td class="text-muted">Lo vincula a la vigencia actual (Paso 5).</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Fechas Posesión</td>
                                        <td class="text-muted">La "Fecha de Ingreso / Posesión" reemplaza la fecha de inicio del periodo en el reporte PDF en caso de vinculaciones extemporáneas.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-success fw-bold text-center mt-3 mb-0"><i class="bi bi-check-circle-fill me-2"></i> ¡Completado este paso, el ciclo de Concertación iniciará exitosamente para el evaluado!</p>
                    </div>
                </section>

                <hr class="my-5 border-secondary border-opacity-25">

                <!-- Guía del Evaluador Introducción -->
                <section id="guia-evaluador" class="mb-5">
                    <h1 class="display-5 fw-bold mb-3 text-primary" style="letter-spacing: -1px;">Guía del Evaluador</h1>
                    <p class="lead text-muted">Manual destinado a los <strong>Jefes Inmediatos</strong>. Aquí aprenderá cómo gestionar a sus colaboradores, revisar sus compromisos, validar evidencias y realizar la calificación final.</p>
                </section>

                <!-- Mis Evaluados -->
                <section id="mis-evaluados" class="mb-5">
                    <h2><span class="step-badge bg-secondary">1</span> Mis Evaluados</h2>
                    <p>Este es su panel principal de control. Desde aquí visualizará exclusivamente a los funcionarios que han sido vinculados bajo su supervisión en la dependencia actual.</p>
                    <div class="card p-4">
                        <ul class="text-muted mb-0">
                            <li class="mb-2">Navegue a <strong>Mis Evaluados</strong> en el menú principal del sistema.</li>
                            <li class="mb-2">Encontrará un listado de subordinados, indicando el estado actual de su proceso (Ej. <span class="badge bg-warning text-dark">En Concertación</span>, <span class="badge bg-success">Aceptada</span>).</li>
                            <li>Haciendo clic en las opciones de acción de la tabla, podrá ingresar al portafolio de cada funcionario para Concertar, Revisar Evidencias o Calificar.</li>
                        </ul>
                    </div>
                </section>

                <!-- Concertación -->
                <section id="concertacion-evaluador" class="mb-5">
                    <h2><span class="step-badge bg-secondary">2</span> Concertación de Compromisos</h2>
                    <p>Es la primera etapa del ciclo, donde se definen y acuerdan los objetivos (Compromisos Funcionales) que el funcionario deberá cumplir a lo largo de la vigencia.</p>
                    <div class="card p-4 mb-3">
                        <h5 class="fw-bold"><i class="bi bi-handshake text-success me-2"></i> Revisión y Aprobación</h5>
                        <ul class="text-muted mb-0">
                            <li class="mb-2"><strong>Proponer:</strong> Generalmente, el evaluado formulará inicialmente los compromisos y sus pesos porcentuales (los cuales sumados deben dar el 100%).</li>
                            <li class="mb-2"><strong>Aprobar o Rechazar:</strong> Usted, como jefe inmediato, debe analizar detenidamente cada compromiso. Si la redacción y los pesos son apropiados para las metas de la dependencia, proceda a hacer clic en <strong>Aprobar Concertación</strong>.</li>
                            <li class="mb-2"><strong>Generación de Acta:</strong> Una vez aprobados, el sistema habilitará el botón para descargar el acta en PDF (que incluye fecha y hora de aprobación para su firma física o respaldo magnético).</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Error Frecuente en LNyR:</strong> Aprobar compromisos ambiguos o que no son medibles. Evite verbos como <em>"Apoyar"</em> o <em>"Acompañar"</em> sin un entregable claro. Los compromisos deben ser específicos (Ej. <em>"Formular y presentar 1 documento técnico sobre..."</em>). Además, asegúrese de que el peso asignado refleje el impacto real de esa tarea en la dependencia.
                        </div>
                    </div>
                </section>

                <!-- Evidencias -->
                <section id="evidencias-evaluador" class="mb-5">
                    <h2><span class="step-badge bg-secondary">3</span> Validación de Evidencias</h2>
                    <p>Durante la ejecución del periodo, el evaluado deberá subir archivos u oficios como prueba del avance y cumplimiento de sus compromisos funcionales.</p>
                    <div class="card p-4 border-start border-4 border-warning mb-3">
                        <p class="text-muted">Como evaluador, su deber es el de supervisión continua:</p>
                        <ul class="text-muted mb-0">
                            <li class="mb-2">Ingrese a la vista de <strong>Evidencias</strong> del funcionario.</li>
                            <li class="mb-2">Visualice los anexos y/o URLs adjuntados por el evaluado correspondientes a cada compromiso.</li>
                            <li><strong>Verificación:</strong> Valide la autenticidad y el volumen de la evidencia; esto será su soporte objetivo al momento de emitir la calificación final.</li>
                        </ul>
                    </div>

                    <div class="alert alert-primary d-flex align-items-center" role="alert">
                        <i class="bi bi-lightbulb-fill fs-4 me-3"></i>
                        <div>
                            <strong>Recomendación Procedimental:</strong> No espere hasta el final del semestre o año para revisar las evidencias. La falta de retroalimentación oportuna puede invalidar un desempeño deficiente si el evaluado apela alegando que nunca fue advertido sobre la insuficiencia de sus entregables.
                        </div>
                    </div>
                </section>

                <!-- Evaluacion -->
                <section id="evaluacion-definitiva" class="mb-5">
                    <h2><span class="step-badge bg-secondary">4</span> Evaluaciones y Calificación Definitiva</h2>
                    <p>Durante y al concluir el periodo, usted deberá registrar distintos eventos de evaluación. El sistema EDLibre se encarga de las matemáticas complejas por usted, garantizando que el proceso de calificación cumpla con las normativas de EDL.</p>
                    
                    <div class="card p-4 border-info border-start border-4 mb-3">
                        <h5 class="fw-bold"><i class="bi bi-calendar-event text-info me-2"></i> Tipos de Evaluaciones (Causales)</h5>
                        <p class="text-muted mb-2">Para evaluar, haga clic en <strong>Nueva Evaluación</strong> en el panel de su evaluado. Encontrará tres grupos de causales:</p>
                        <ul class="text-muted mb-0">
                            <li class="mb-2"><strong>Evaluaciones Ordinarias (Semestrales):</strong> Son los parciales obligatorios (Primer semestre y Segundo semestre).</li>
                            <li class="mb-2"><strong>Evaluaciones Eventuales:</strong> Úselas cuando ocurra un evento extraordinario que requiera cortar la evaluación, por ejemplo: un cambio en el cargo del evaluado, una separación temporal mayor a 30 días, o si <strong>usted (el evaluador)</strong> cambia de cargo y necesita dejar evaluados a sus subordinados antes de retirarse.</li>
                            <li><strong>Consolidaciones:</strong> El sistema consolida automáticamente las notas para generar la calificación semestral o definitiva.</li>
                        </ul>
                    </div>

                    <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-shield-exclamation fs-3 me-3"></i>
                        <div>
                            <strong>¡ALERTA LEGAL Y PROCEDIMENTAL!</strong> Uno de los errores más graves y sancionables disciplinariamente en la administración pública es la <em>omisión de evaluación eventual por retiro del evaluador</em>. Si usted (Jefe Inmediato) va a renunciar, es trasladado o sale a vacaciones por más de 30 días, <strong>ESTÁ OBLIGADO A EVALUAR</strong> a todo su personal a cargo hasta su último día laboral mediante la causal <em>"Por cambio de evaluador"</em>, de lo contrario podría enfrentar investigaciones disciplinarias.
                        </div>
                    </div>

                    <div class="card p-4 border-warning border-start border-4 mb-4">
                        <h5 class="fw-bold"><i class="bi bi-clock-history text-warning me-2"></i> Las Fechas Son Clave</h5>
                        <p class="text-muted mb-0">Cada vez que usted cree una evaluación (ordinaria o eventual), el sistema le exigirá registrar la <strong>Fecha de Inicio</strong> y <strong>Fecha de Fin</strong> exacta que abarca dicho corte. 
                        <br><br>
                        <strong>¿Por qué?</strong> Porque el sistema calculará exactamente <strong>cuántos días</strong> abarca cada calificación para ponderar matemáticamente la nota final. Adicionalmente, se cuenta con una validación inteligente para evitar errores: el sistema no le permitirá ingresar fechas que estén por fuera del Periodo Oficial (Vigencia).</p>
                    </div>

                    <div class="card p-4 border-primary border-start border-4">
                        <h5 class="fw-bold"><i class="bi bi-clipboard2-data text-primary me-2"></i> Proceso de Calificación y Consolidación</h5>
                        <ol class="text-muted mb-3 mt-2">
                            <li class="mb-2"><strong>Calificar Compromisos (85%):</strong> Evaluará de 1 a 100 cada compromiso funcional basado en las evidencias de la plataforma.</li>
                            <li class="mb-2"><strong>Calificar Conductas (15%):</strong> Calificará de 1 a 100 las conductas comportamentales asociadas al evaluado.</li>
                            <li class="mb-2"><strong>Notificación:</strong> Al finalizar, pulse <em>"Notificar a Evaluado"</em>. Esta acción cerrará la edición y enviará la nota al evaluado para su aceptación.</li>
                            <li class="mb-2"><strong>¡Magia del Sistema (Consolidación Automática)!:</strong> Como evaluador, <strong>no necesita usar calculadoras</strong> para sacar la nota definitiva anual. 
                                <br><br>
                                En el preciso momento en que usted califique y notifique el <strong>"Parcial segundo semestre"</strong>, EDLibre tomará todas las evaluaciones previas del funcionario que hayan sido aceptadas y generará, de manera <strong>100% automática</strong>, la <em>"Consolidación Definitiva"</em> mediante un promedio exacto ponderado por días.</li>
                        </ol>
                        <div class="alert alert-success mb-0 p-3 text-center small fw-bold">
                            <i class="bi bi-file-earmark-pdf fs-5 d-block mb-1"></i> Una vez el evaluado acepte las calificaciones, usted podrá descargar el Reporte Oficial en PDF de la Consolidación Definitiva con el nivel de cumplimiento exacto (Sobresaliente, Satisfactorio, etc.) listo para firma.
                        </div>
                    </div>
                </section>

                <hr class="my-5 border-secondary border-opacity-25">

                <!-- Guía del Evaluado Introducción -->
                <section id="guia-evaluado" class="mb-5">
                    <h1 class="display-5 fw-bold mb-3 text-primary" style="letter-spacing: -1px;">Guía del Evaluado</h1>
                    <p class="lead text-muted">Manual destinado a los <strong>Funcionarios Sujetos a Evaluación</strong>. Conozca el paso a paso para formular sus compromisos, subir pruebas de su desempeño y aceptar la calificación final.</p>
                </section>

                <!-- Mis Compromisos -->
                <section id="mis-compromisos" class="mb-5">
                    <h2><span class="step-badge bg-info text-white">1</span> Panel de Mis Compromisos</h2>
                    <p>Esta es su bandeja de entrada. Aquí podrá visualizar el estado general de su proceso de Evaluación del Desempeño Laboral para la vigencia activa.</p>
                    <div class="card p-4">
                        <ul class="text-muted mb-0">
                            <li class="mb-2">Diríjase a la sección <strong>Mis Compromisos</strong> en el menú principal.</li>
                            <li class="mb-2">Verá una tarjeta o tabla principal con su información institucional: la Dependencia a la que pertenece, su Nivel Jerárquico, quién es su Evaluador designado y el estado general del proceso (ej. <span class="badge bg-secondary text-white">Borrador</span>, <span class="badge bg-primary text-white">Aprobado</span>, <span class="badge bg-info text-dark">Evaluado</span>).</li>
                            <li>A través de los botones de acción en esta vista, usted iniciará la propuesta de sus objetivos y, más adelante durante la vigencia, adjuntará los soportes requeridos.</li>
                        </ul>
                    </div>
                </section>

                <!-- Concertación -->
                <section id="concertacion-evaluado" class="mb-5">
                    <h2><span class="step-badge bg-info text-white">2</span> Formulación de la Concertación</h2>
                    <p>En esta fase inicial usted debe redactar los <strong>Compromisos Funcionales</strong>. Estos son los objetivos concretos y medibles que se compromete a alcanzar durante el periodo.</p>
                    <div class="card p-4 mb-3">
                        <h5 class="fw-bold"><i class="bi bi-pencil-square text-info me-2"></i> Creación de Compromisos</h5>
                        <ol class="text-muted mb-0">
                            <li class="mb-2">Haga clic en el botón <strong>Concertar</strong> dentro de "Mis Compromisos".</li>
                            <li class="mb-2">En la nueva pantalla, presione <strong>"Nuevo Compromiso"</strong>. Se abrirá un formulario donde debe redactar la meta u objetivo de forma clara (¿Qué se va a hacer? ¿Cómo se va a medir?).</li>
                            <li class="mb-2"><strong>Asignar Peso Porcentual:</strong> Cada compromiso tiene un nivel de importancia relativo. Asigne un peso (ej. 30%, 50%). Tenga en cuenta que <em>la suma total de todos los compromisos funcionales obligatoriamente debe ser exacta al 100%</em> para que la plataforma permita iniciar la evaluación.</li>
                            <li class="mb-2"><strong>Notificación al Jefe:</strong> Una vez formulados y alcanzado el 100%, su jefe (Evaluador) revisará su propuesta para <strong>Aprobarla</strong>. Tenga en cuenta que no se pueden subir evidencias si la concertación no está aprobada.</li>
                            <li><strong>Reporte Oficial:</strong> Tras la aprobación de su jefe, la plataforma habilitará un botón para descargar el acta de concertación en formato PDF.</li>
                        </ol>
                    </div>

                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-circle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Tip para el Evaluado (LNyR):</strong> Proyecte únicamente compromisos que dependan 100% de su gestión. Un error común es concertar compromisos que requieren aprobaciones presupuestales de terceros o firmas externas, lo cual le perjudicará si dichas entidades se retrasan. <em>Usted será evaluado estrictamente sobre lo que concerte aquí.</em>
                        </div>
                    </div>
                </section>

                <!-- Evidencias -->
                <section id="evidencias-evaluado" class="mb-5">
                    <h2><span class="step-badge bg-info text-white">3</span> Registro de Evidencias</h2>
                    <p>A medida que ejecuta sus labores a lo largo del año, es fundamental documentar los logros alcanzados. Las evidencias son las pruebas objetivas que su jefe utilizará para justificar su calificación.</p>
                    <div class="card p-4 border-start border-4 border-info mb-3">
                        <h5 class="fw-bold"><i class="bi bi-cloud-arrow-up text-info me-2"></i> Subir Soportes</h5>
                        <ul class="text-muted mb-0">
                            <li class="mb-2">Acceda a la vista de <strong>Evidencias</strong> desde su panel de Mis Compromisos.</li>
                            <li class="mb-2">Verá listados los compromisos funcionales que fueron aprobados en el Paso 2.</li>
                            <li class="mb-2">Presione el botón <strong>"Añadir Evidencia"</strong> o <strong>"Nueva Evidencia"</strong> frente al compromiso respectivo.</li>
                            <li><strong>Tipos de Evidencia:</strong>
                                <ul class="mt-2">
                                    <li class="mb-1"><strong>Archivo:</strong> Puede subir documentos directamente desde su dispositivo (Informes PDF, actas, formatos sellados).</li>
                                    <li><strong>Enlace (URL):</strong> Si la evidencia es un reporte en un sistema web, carpeta en la nube institucional o tablero de datos, coloque el link (URL) acompañado de una breve descripción explicativa.</li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <div class="alert alert-primary d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Importante:</strong> Su evaluación legal depende enteramente de sus evidencias. No cometa el error de acumular el trabajo para fin de año; las evidencias se deben cargar continuamente mes a mes, asegurando que si ocurre una eventualidad (Ej. su jefe renuncia inesperadamente), el sistema cuente con los soportes para respaldar su calificación parcial obligatoria.
                        </div>
                    </div>
                </section>

                <!-- Evaluación Definitiva -->
                <section id="evaluacion-notificacion" class="mb-5">
                    <h2><span class="step-badge bg-info text-white">4</span> Notificación y Aceptación de la Calificación</h2>
                    <p>Al finalizar el periodo laboral, su jefe cerrará el proceso emitiendo la calificación consolidada que promedia sus logros funcionales (85%) y la evaluación de sus competencias comportamentales (15%).</p>
                    <div class="card p-4 border-success border-start border-4 mb-3">
                        <h5 class="fw-bold"><i class="bi bi-check-circle text-success me-2"></i> Cierre del Proceso</h5>
                        <ol class="text-muted mb-3 mt-2">
                            <li class="mb-2">Cuando su evaluador guarde la calificación oficial, el estado de su proceso pasará a <strong>Evaluado</strong>.</li>
                            <li class="mb-2">Ingrese a su panel y haga clic en <strong>Ver Evaluación</strong>. Allí encontrará el detalle cuantitativo y cualitativo de cómo fue calificado ítem por ítem.</li>
                            <li><strong>Aceptar Evaluación:</strong> Si usted está conforme con el puntaje final, debe confirmarlo haciendo clic en el botón de aceptar. Con esto el proceso queda formalmente cerrado (estado <strong>Aceptada</strong>).</li>
                        </ol>
                        <div class="alert alert-success mb-0 p-2 text-center small">
                            <i class="bi bi-file-earmark-check me-1"></i> Una vez aceptada, el sistema le habilitará el botón para descargar el <strong>Reporte Oficial de Evaluación en PDF</strong>, documento clave y soporte válido para su hoja de vida y escalafón en la entidad.
                        </div>
                    </div>

                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-shield-exclamation fs-4 me-3"></i>
                        <div>
                            <strong>Tenga en Cuenta:</strong> Si usted NO está de acuerdo con la calificación obtenida, NO debe pulsar el botón de aceptar de inmediato. Usted tiene derecho a interponer recursos (reposición y apelación) en los tiempos que establece la ley desde el momento en que se le notifica la evaluación.
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
