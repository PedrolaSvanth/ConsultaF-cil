<?php
session_start();
include '../models/conexao.php';

// Bloquear acesso se não estiver logado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: ../pages/login_v2.html"); // ajuste pro seu caminho real
    exit;
}

$cliente_id = $_SESSION['cliente_id'];

// Buscar CEP e endereço do usuário
$sql = "SELECT nome_completo, cep, endereco FROM clientes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Usuário não encontrado.");
}

$cliente = $result->fetch_assoc();

// Tira tudo que não é número do CEP
$cepNumeros = preg_replace('/\D/', '', $cliente['cep']);

// 1) Endereço completo que vai pro Nominatim
$enderecoCompleto = $cliente['endereco'] . ', ' . $cliente['cep'] . ', Brasil';

// 2) CEP puro – pode ficar guardado, mas NÃO vamos usar sozinho pra geocode
$cepCompleto = $cepNumeros;


?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <title>Consulta Fácil - Localizar Farmácias</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Estilos da sua aplicação -->
    <link rel="stylesheet" href="../assets/css/login.css" />
    <link rel="stylesheet" href="../assets/css/home.css" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <style>
        /* Área do mapa */
        #map {
            width: 100%;
            height: 450px;
            border-radius: 10px;
            margin-top: 15px;
        }

        .search-box {
            margin-top: 15px;
            display: flex;
            gap: 8px;
        }

        .search-box input {
            flex: 1;
            padding: 8px 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .search-box button {
            padding: 8px 14px;
            border-radius: 6px;
            border: none;
            background-color: #008ea4;
            color: #fff;
            cursor: pointer;
            font-weight: 500;
        }

        .search-box button:hover {
            opacity: 0.9;
        }

        .leaflet-popup-content {
            font-size: 0.9rem;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <div class="title"><span>Consulta Fácil</span></div>

        <div class="home-container">
            <h2>Localizar Farmácias</h2>
            <p>
                Olá,
                <strong><?php echo htmlspecialchars($_SESSION['apelido'] ?? $cliente['nome_completo']); ?></strong>!<br>
                Mostrando farmácias próximas do seu endereço cadastrado.
            </p>

            <!-- Campo de busca -->
            <div class="search-box">
                <input type="text" id="busca" placeholder="Buscar por nome, CEP ou endereço..." />
                <button id="btnBuscar">Buscar nessa região</button>
                <button id="btnVoltarEndereco" type="button">Voltar para meu endereço</button>
            </div>

            <!-- Mapa -->
            <div id="map"></div>

            <br>
            <button class="home-btn" onclick="window.location.href='../controllers/telaprincipal.php'">
                <i class="fas fa-arrow-left"></i> Voltar para Home
            </button>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Font Awesome (se ainda não tiver sido carregado) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>

    <script>
        // Endereço do usuário vindo do PHP
        const USER_ADDRESS = <?php echo json_encode($enderecoCompleto); ?>;
        const USER_ZIP = <?php echo json_encode($cepCompleto); ?>;
        const USER_NAME = <?php echo json_encode($cliente['nome_completo']); ?>;

        let map;
        let userMarker;
        let pharmacyLayer = L.layerGroup(); // grupo para as farmácias

        // coordenadas do usuário
        let userLat = null;
        let userLon = null;
        let userDisplayName = "";

        async function geocode(address) {
            console.log("Geocodificando:", address);

            const url = `geocode.php?q=${encodeURIComponent(address)}`;
            const response = await fetch(url);

            const data = await response.json();
            console.log("Resposta do geocode.php:", data);

            // Se geocode.php devolveu um objeto {error: ...}
            if (!Array.isArray(data)) {
                const msg = data.error || 'Erro desconhecido ao geocodificar';
                console.error("Erro vindo do geocode.php:", data);
                throw new Error(msg);
            }

            // Se é um array, mas vazio, significa que o Nominatim não encontrou nada
            if (data.length === 0) {
                throw new Error('Endereço não encontrado');
            }

            return {
                lat: parseFloat(data[0].lat),
                lon: parseFloat(data[0].lon),
                display_name: data[0].display_name
            };
        }


        // Tenta primeiro com o endereço completo (endereço + CEP + Brasil)
        async function geocodeUser() {
            console.log("Tentando geocodificar pelo endereço completo...");
            return await geocode(USER_ADDRESS);
        }


        // Buscar farmácias via Overpass API
        async function buscarFarmacias(lat, lon) {
            // Limpa as farmácias já desenhadas
            pharmacyLayer.clearLayers();

            const radius = 1500; // em metros (1,5km)
            const query = `
        [out:json][timeout:25];
        (
          node["amenity"="pharmacy"](around:${radius},${lat},${lon});
          way["amenity"="pharmacy"](around:${radius},${lat},${lon});
          relation["amenity"="pharmacy"](around:${radius},${lat},${lon});
        );
        out center;
    `;

            const response = await fetch("https://overpass-api.de/api/interpreter", {
                method: "POST",
                body: query
            });

            const data = await response.json();

            if (!data.elements) return;

            data.elements.forEach(el => {
                const latEl = el.lat || (el.center && el.center.lat);
                const lonEl = el.lon || (el.center && el.center.lon);
                if (!latEl || !lonEl) return;

                const tags = el.tags || {};
                const nome = tags.name || "Farmácia / Drogaria";
                const endereco =
                    (tags["addr:street"] || "") + " " +
                    (tags["addr:housenumber"] || "") + "<br>" +
                    (tags["addr:suburb"] || tags["addr:neighbourhood"] || "") + " " +
                    (tags["addr:city"] || "") + " " +
                    (tags["addr:state"] || "");

                const marker = L.marker([latEl, lonEl]).bindPopup(
                    `<strong>${nome}</strong><br>${endereco}`
                );
                pharmacyLayer.addLayer(marker);
            });

            pharmacyLayer.addTo(map);
        }

        // Inicializar o mapa a partir do endereço do usuário
        async function initMap() {
            try {
                const geo = await geocodeUser();

                userLat = geo.lat;
                userLon = geo.lon;
                userDisplayName = geo.display_name;


                // Cria o mapa centralizado no usuário
                map = L.map('map').setView([geo.lat, geo.lon], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);

                // Marcador do usuário
                userMarker = L.marker([geo.lat, geo.lon]).addTo(map)
                    .bindPopup(`<strong>Você está aqui</strong><br>${USER_NAME}<br>${geo.display_name}`)
                    .openPopup();

                // Carrega farmácias próximas
                await buscarFarmacias(geo.lat, geo.lon);

            } catch (e) {
                console.error(e);
                alert("Não foi possível localizar seu endereço automaticamente. Tente fazer uma busca manual.");
                // Se quiser, pode criar um mapa padrão em Brasília, por exemplo:
                map = L.map('map').setView([-15.7942, -47.8822], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);
            }
        }

        async function voltarParaEndereco() {
            try {
                // se ainda não tivermos salvo as coordenadas, tenta geocodificar de novo
                if (userLat === null || userLon === null) {
                    const geo = await geocodeUser();
                    userLat = geo.lat;
                    userLon = geo.lon;
                    userDisplayName = geo.display_name;
                }

                // recentra o mapa
                map.setView([userLat, userLon], 15);

                // ajusta (ou cria) o marcador do usuário
                if (!userMarker) {
                    userMarker = L.marker([userLat, userLon]).addTo(map);
                } else {
                    userMarker.setLatLng([userLat, userLon]);
                }

                userMarker
                    .bindPopup(`<strong>Você está aqui</strong><br>${USER_NAME}<br>${userDisplayName}`)
                    .openPopup();

                // recarrega farmácias perto do usuário
                await buscarFarmacias(userLat, userLon);

            } catch (e) {
                console.error(e);
                alert("Não foi possível voltar para o seu endereço agora.");
            }
        }


        async function buscarPorTexto() {
            const texto = document.getElementById('busca').value.trim();
            if (!texto) {
                alert("Digite um nome, CEP ou endereço para buscar.");
                return;
            }

            try {
                // ajuda o Nominatim a focar no Brasil
                const geo = await geocode(texto + ', Brasil');
                map.setView([geo.lat, geo.lon], 15);

                if (!userMarker) {
                    userMarker = L.marker([geo.lat, geo.lon]).addTo(map);
                } else {
                    userMarker.setLatLng([geo.lat, geo.lon]);
                }
                userMarker.bindPopup(`<strong>Região buscada</strong><br>${geo.display_name}`).openPopup();

                await buscarFarmacias(geo.lat, geo.lon);
            } catch (e) {
                console.error(e);
                alert("Local não encontrado. Tente refinar sua busca.");
            }
        }


        // Eventos
        document.getElementById('btnBuscar').addEventListener('click', buscarPorTexto);
        document.getElementById('btnVoltarEndereco').addEventListener('click', voltarParaEndereco);
        document.getElementById('busca').addEventListener('keyup', function (e) {
            if (e.key === 'Enter') {
                buscarPorTexto();
            }
        });

        // Inicia tudo
        initMap();
    </script>

</body>

</html>