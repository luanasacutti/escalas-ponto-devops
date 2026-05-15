function atualizarRelogio() {
    const el = document.getElementById('clock');
    if (!el) return;
    const agora = new Date();
    el.textContent = agora.toLocaleTimeString('pt-BR');
}
setInterval(atualizarRelogio, 1000);
atualizarRelogio();
