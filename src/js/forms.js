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
            dataType: "json",
            success: function (response) {
                $(form)[0].reset();

                // toast({
                //     icon: "success",
                //     title: `Se inicio sesion correctamente <br> Bienvenido ${response.fullname}`,
                //     time: 2000,
                //     position: "top",
                //     onClose: function () {
                //         window.location.href = "dashboard.php";
                //     },
                // });
            },
            error: function (xhr, status, error, response) {
                const errorData = xhr.responseJSON.json || {};
                toast({
                    icon: "error",
                    title: `Error al intentar iniciar sesión. <br><br> ${errorData.message || "Error desconocido"} <br> Código ${xhr.status}`,
                    time: 5000,
                    position: "center",
                });
                console.error("--- Este es el error resultante de ajax ---");
                console.error(xhr.status, errorData);
            },
        });
    }

    $("#btnShowPass").click(function () {
        const inputPass = $(this).data("input");
        const type = $(inputPass).attr("type") === "text" ? "password" : "text";

        $(inputPass).attr("type", type);
        $(this).toggleClass("fa-eye fa-eye-slash");
    });
});