// ================================
//  SISTEMA GLOBAL DE ALARMES
//  Funciona em QUALQUER página
// ================================

// Carrega os alarmes do localStorage
let alarmes = JSON.parse(localStorage.getItem("alarmes")) || [];

// Função para tocar o som caso exista o <audio> na página
function tocarSom() {
    const som = document.getElementById("somAlarme");
    if (som) {
        som.play().catch(() => {});
    }
}

// Verifica alarmes a cada segundo
function verificarAlarmes() {
    const agora = new Date();
    const horaAtual = agora.toTimeString().slice(0, 5);
    const dataHoje = agora.toDateString();

    alarmes.forEach(a => {
        // Reset diário
        if (!a.ultimaData || a.ultimaData !== dataHoje) {
            a.disparadoHoje = false;
            a.ultimaData = dataHoje;
        }

        // Disparo
        if (a.hora === horaAtual && !a.disparadoHoje) {

            tocarSom();

            if (Notification.permission === "granted") {
                new Notification("Hora do remédio!", { body: a.nome });
            } else {
                alert("Hora do remédio: " + a.nome);
            }

            a.disparadoHoje = true;
            localStorage.setItem("alarmes", JSON.stringify(alarmes));
        }
    });
}

// Solicita permissão de notificação
if ("Notification" in window) {
    Notification.requestPermission();
}

// Roda verificação constantemente
setInterval(verificarAlarmes, 1000);