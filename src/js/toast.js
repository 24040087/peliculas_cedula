// Funcion de Alertas con sweetaler2
function toast(options) {
    const settings = $.extend(
        {
            icon: "info",
            title: "The title",
            time: 3000,
            position: "top-end",
            onClose: null,
        },
        options
    );

    const Toast = Swal.mixin({
        toast: true,
        position: settings.position,
        showConfirmButton: false,
        showCloseButton: true,
        timer: settings.time,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
        didClose: () => {
            if (typeof settings.onClose === "function") {
                settings.onClose();
            }
        },
    });
    Toast.fire({
        icon: settings.icon,
        title: settings.title,
    });
}