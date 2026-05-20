function atualizarRelogio() {
    const el = document.getElementById('clock');
    if (!el) return;
    const base = el.dataset.serverTime ? new Date(el.dataset.serverTime) : new Date();
    const segundos = Number(el.dataset.elapsedSeconds || 0);
    const agora = new Date(base.getTime() + segundos * 1000);

    el.textContent = agora.toLocaleTimeString('pt-BR', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        timeZone: 'America/Sao_Paulo'
    });
    el.dataset.elapsedSeconds = String(segundos + 1);
}
setInterval(atualizarRelogio, 1000);
atualizarRelogio();
 
