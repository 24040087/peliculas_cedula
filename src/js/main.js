$(document).ready(function () {
    $("form").submit(function (event) {
        event.preventDefault();
        const actionForm = $(this).attr("action");

        switch (actionForm) {
            case "login":
                login(this);
                break;
        
            default:
                break;
        }
    });

    function login(form) {
        const formData = new FormData(form);
        const jsonData = Object.fromEntries(formData.entries());
        
        $.ajax({
            url: "php/login.php",
            type: "POST",
            data: JSON.stringify(jsonData),
            processData: false,
            contentType: "application/json",
            success: function (response) {

                $(form)[0].reset();
                toast({
                    icon: "success",
                    title: "Se inicio sesion correctamente",
                    time: 2000,
                    position: "top-center",
                    onClose: function() {
                        window.location.href = "dashboard.php";
                    }
                });
            },
            error: function (xhr, status, error, response) {
                toast({
                    icon: "error",
                    title: `Error al intentar Iniciar sesion. Codigo ${xhr.status}`,
                    time: 5000,
                    position: "center"
                });
                console.error("--- Este es el error resultante de ajax ---");
                console.error(error);
            }
        });
    }

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
                if(typeof settings.onClose === "function") {settings.onClose()}
            },
        });
        Toast.fire({
            icon: settings.icon,
            title: settings.title,
        });
    }
});