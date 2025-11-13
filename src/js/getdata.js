
    const levelsContentTable = $("#levelsTableBody");
    if (levelsContentTable.length === 1) {
        getLevels();
    }

    function getLevels() {
        $.ajax({
            url: "php/getLevels.php",
            type: "GET",
            contentType: "application/json",
            dataType: "json",
            success: function (response) {
                const data = response.data;
                levelsContentTable.empty();

                data.forEach((element) => {
                    levelsContentTable.append(`<tr> <td>${element.ClasificacionId}</td> <td>${element.ClasificacionDesc}</td> <td class="d-flex gap-1"><button class="btn btn-icon btn-detail" data-id="${element.ClasificacionId}"><i class="fa-solid fa-pen"></i></button><button class="btn btn-icon btn-danger" data-id="${element.ClasificacionId}"><i class="fa-solid fa-trash-can"></i></button></td> </tr>`);
                });
            },
            error: function (xhr, status, error, response) {
                const errorData = xhr.responseJSON.json || {};
                toast({
                    icon: "error",
                    title: `Error al intentar registrar la clasificacion. <br><br> ${errorData.message || "Error desconocido"} <br> Código ${xhr.status}`,
                    time: 5000,
                    position: "center",
                });
                console.error("--- Este es el error resultante de ajax ---");
                console.error(xhr.status, errorData);
            },
        });
    }
