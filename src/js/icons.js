const indexIcons = ["fa-clapperboard", "fa-compact-disc", "fa-film", "fa-ticket", "fa-ticket-simple", "fa-video"];
const container = $("#indexIcons");

function fillIcons() {
    container.empty();

    const containerWidth = container.width();
    const containerHeight = container.height();
    // const iconSize = 80; // mismo tamaño que en grid-template
    const iconSize = container.data("icon-size") || 80;

    const cols = Math.ceil(containerWidth / iconSize);
    const rows = Math.ceil(containerHeight / iconSize);
    const totalIcons = cols * rows;

    for (let i = 0; i < totalIcons; i++) {
        const iconClass = indexIcons[i % indexIcons.length];
        container.append(`<i class="fas ${iconClass}"></i>`);
    }
}

fillIcons();

// Vuelve a llenar si cambia el tamaño de la pantalla
$(window).on("resize", function () {
    clearTimeout(window.fillIconsTimeout);
    window.fillIconsTimeout = setTimeout(fillIcons, 100);
});