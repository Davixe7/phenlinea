<!DOCTYPE html>
<html lang="es">

<head>
    <link href="{{ asset('favicon.png') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Términos y condiciones de uso de la plataforma PH en Línea.">
    <meta name="keywords" content="términos, condiciones, uso, plataforma, PH en Línea, phenlinea">
    <style>
        body {
            font-size: 18px;
            line-height: 1.6rem;
        }

        .main {
            max-width: 620px;
        }

        h1 {
            font-size: 32px;
        }

        h2 {
            margin-bottom: 1rem;
        }

        aside ul li {
            margin-bottom: .5rem;
        }
    </style>
    <title>Términos y condiciones - PHenlínea</title>
</head>

<body>
    <div class="phenlinea-navbar">
        <div class="phenlinea-navbar__title">
            <a class="btn btn-round me-3" href="{{ route('home')  }}">
                <i class="material-symbols-outlined">
                    arrow_back
                </i>
            </a>
            {{ isset($title) ? $title : ''}}
        </div>
        <div class="phenlinea-navbar__brand d-none d-sm-inline-block">
            <img src="{{ asset('img/logo.png') }}" alt="" style="width: 120px; margin-top: -20px;">
        </div>
        <div class="phenlinea-navbar__actions">
            @auth
            <div class="me-3 d-none d-sm-inline-block">
                {{ auth()->user()->name }}
            </div>
            <form action="{{ route('logout') }}" method="post" id="logoutForm">
                @csrf
            </form>
            <button type="button" class="btn btn-round" onclick="document.querySelector('#logoutForm').submit()">
                <i class="material-symbols-outlined">logout</i>
            </button>
            @endauth
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 sticky-top">
                <aside>
                    <div class="ps-3 pb-3">
                        <small>
                            Indice
                        </small>
                    </div>
                    <nav id="indice">
                        <ul>
                            <li><a href="#politicas-y-terminos">Políticas y términos</a></li>
                            <li><a href="#cambios-a-estos-terminos">Cambios a estos términos</a></li>
                            <li><a href="#contenido">Contenido</a></li>
                            <li><a href="#cookies">Cookies</a></li>
                            <li><a href="#descargo-de-responsabilidad">Descargo de responsabilidad</a></li>
                            <li><a href="#compensacion">Compensación</a></li>
                            <li><a href="#jurisdiccion-y-ley-aplicable">Jurisdicción y ley aplicable</a></li>
                        </ul>
                    </nav>
                </aside>
            </div>
            <div class="col-md-6">
                <header>
                    <h1>Términos y condiciones</h1>
                    <hr>
                </header>
                <main>
                    <section>
                        <p>Lea estos Términos de servicio detenidamente porque brindan información importante sobre sus derechos, recursos y obligaciones legales. Al acceder o utilizar la Plataforma PH en Línea, usted acepta estar sujeto a estos Términos de servicio. Estos Términos de servicio ("Términos") constituyen un servicio legalmente vinculante ("Servicio") entre usted y PH en Línea (como se define a continuación) que rigen su acceso y uso de la Plataforma PH en Línea, incluido cualquier subdominio de la misma.</p>

                        <p>Al igual que con cualquier otra aplicación, sitio web o servicio que PH en Línea pueda poner a su disposición, la Plataforma y los servicios proporcionados por PH en Línea se denominarán colectivamente como la "Plataforma PH en Línea". Nuestras políticas se incorporan aquí por referencia.</p>

                        <p>Siempre que se utilice "PH en Línea", "nosotros" o "nuestro" (incluidas otras variaciones) en estos "Términos", Nuestra Política de privacidad describe cómo usamos y recopilamos información personal sobre cada usuario que accede y usa la Plataforma PH en Línea.</p>

                        <p>El Usuario es el único responsable de identificar, comprender y cumplir con todas las leyes, normas y reglamentos aplicables a la información transmitida y de identificar y obtener las licencias, permisos o registros necesarios para cualquier solicitud.</p>
                    </section>
                    <section>
                        <h2 id="cambios-a-estos-terminos">Cambios a estos términos</h2>
                        <p>PH EN LÍNEA se reserva el derecho de cambiar estos términos en cualquier momento de acuerdo con esta disposición. Si realizamos algún cambio en estos Términos, se lo notificaremos en la sección "Última actualización" en la parte superior de este documento. También le notificaremos dicho aviso por correo electrónico al menos treinta (10) días antes del aviso formal.</p>
                    </section>

                    <section>
                        <h2 id="contenido">Contenido</h2>
                        <p>Al crear, cargar, enviar, transmitir, recibir, almacenar o poner a disposición Contenido a través de la Plataforma, otorga a cualquier Soporte o Plataforma de PH en Línea una licencia no exclusiva, mundial, libre de regalías que es irrevocable, perpetua (o exigible protegido), podrá otorgar una sublicencia y cesión para acceder, utilizar, almacenar y reproducir dichos contenidos.</p>

                        <p>Usted es el único responsable de todo el contenido que envíe a PH en Línea y cargue en la Plataforma.</p>

                        <p>Usted declara y garantiza por separado que:</p>
                        <ol>
                            <li>es el único propietario de todo el Contenido disponible a través de la Plataforma PH en Línea y que tiene todos los derechos, licencias, consentimientos y exenciones necesarios otorgados a PH en Línea con respecto a dicho Contenido según lo dispuesto en estos Términos, y</li>
                            <li>ni el Contenido ni su publicación, carga, distribución o transmisión infringen, constituyen apropiación indebida o violan los derechos de autor, marca comercial, secreto comercial, derecho moral o cualquier derecho privado o derecho de propiedad intelectual de terceros, y no causan una violación. cualquier ley o reglamento aplicable.</li>
                        </ol>

                        <p>No puede cargar, enviar ni transmitir ningún Contenido de usuario que sea:</p>
                        <ol>
                            <li>fraudulento, falso o engañoso (ya sea directamente o por omisión o falta de actualización de la información),</li>
                            <li>difamatorio, obsceno, pornográfico, vulgar u ofensivo,</li>
                            <li>promueva la discriminación, la intolerancia, el racismo, el odio, el acoso o el daño contra cualquier persona o grupo,</li>
                            <li>actos de violencia, amenace o incite a la violencia o amenace a cualquier persona, o</li>
                            <li>promueva actividades o sustancias ilegales o nocivas.</li>
                        </ol>

                        <p>PH en Línea se adhiere a la ley de derechos de autor y espera hacer lo mismo.</p>

                        <p>Si cree que cualquier contenido en la plataforma PH en Línea infringe sus derechos de autor, envíe un correo electrónico a <a href="mailto:info@phenlinea.com">info@phenlinea.com</a></p>
                    </section>

                    <section>
                        <h2 id="cookies">Cookies</h2>
                        <p>La plataforma PH en Línea utilizará cookies para registrar información como los datos de tráfico del sitio web.</p>

                        <p>De esta forma, el usuario no tiene que volver a introducir los datos cada vez que inicia sesión en la aplicación.</p>

                        <p>Estas cookies se utilizarán siempre con el consentimiento del usuario, existiendo el riesgo de que sean eliminadas según sus estándares.</p>

                        <p>PH en Línea utilizará estas cookies para recopilar información y analizar el uso del sitio web con fines estadísticos.</p>
                    </section>

                    <section>
                        <h2 id="descargo-de-responsabilidad">Descargo de responsabilidad</h2>
                        <p>Si utiliza la Plataforma PH en Línea, lo hace bajo su propio riesgo e iniciativa.</p>

                        <p>La Plataforma PH en Línea se proporciona "tal cual" sin garantía de ningún tipo, ya sea expresa o implícita.</p>

                        <p>Usted reconoce que está familiarizado con nuestros Servicios y las leyes, normas y reglamentos que rigen la Plataforma.</p>

                        <p>Si elegimos realizar verificaciones de identidad o de antecedentes de los usuarios según lo exija la ley, renunciamos a cualquier garantía, expresa o implícita, con respecto al proceso de detección de abusos pasados de los usuarios.</p>

                        <p>También nos reservamos el derecho de tomar las medidas necesarias para garantizar que los usuarios no se involucren en dicho comportamiento en el futuro.</p>

                        <p>Los usuarios se abstendrán de realizar cualquier acción o reclamo relacionado con la información, el contenido, las opiniones o los comentarios ajenos a PH en Línea de otros usuarios o terceros y aceptan que dichos reclamos o procedimientos legales solo pueden iniciarse contra sus supervisores directos.</p>

                        <p>PH en Línea no puede divulgar información sobre posibles infractores; dicha información está protegida por información confidencial proporcionada a los usuarios.</p>

                        <p>En este caso, los datos serán facilitados a requerimiento de la autoridad competente.</p>
                    </section>

                    <section>
                        <h2 id="compensacion">Compensación</h2>
                        <p>Usted acepta: eximir de responsabilidad a PH en Línea (incluidas sus filiales, subsidiarias, funcionarios, empleados y agentes), eximir (de acuerdo con los estándares de PH en Línea) de reclamos y responsabilidades relacionados con:</p>
                        <ol>
                            <li>su violación de estos Términos o nuestras políticas, e indemnizar PH en Línea.</li>
                            <li>el uso indebido de la Plataforma o cualquiera de los Servicios de PH en Línea, o</li>
                            <li>violaciones de leyes, reglamentos y derechos de terceros, y para indemnizarlos por cualquier pérdida, daño y gasto que surja de lo anterior.</li>
                        </ol>
                    </section>

                    <section>
                        <h2 id="jurisdiccion-y-ley-aplicable">Jurisdicción y ley aplicable</h2>
                        <p>Con respecto a la interpretación, cumplimiento y ejecución de las disposiciones y los estatutos sociales, o de las controversias que pudieran derivarse de su interpretación o aplicación, las partes acuerdan tomar las medidas legales que estimen necesarias. PH en Línea a su entera discreción.</p>

                        <p>Al utilizar la Plataforma PH en Línea como usuario, usted confirma que ha leído, entendido y acepta estar sujeto a estos términos y condiciones.</p>

                        <p>Si tiene alguna pregunta sobre estos términos y condiciones, envíe un correo electrónico a <a href="mailto:info@phenlinea.com">info@phenlinea.com</a></p>
                    </section>
                </main>
                <footer>
                    <div class="main">
                        <p>&copy; 2025 PHenlínea. Todos los derechos reservados.</p>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</body>

</html>