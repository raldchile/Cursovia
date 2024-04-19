$(document).ready(function () {
	console.log("inicie");
	function stot(txt) {
		// Convierte la primera letra a mayúsculas y el resto a minúsculas
		return txt.toUpperCase();
	}
	function formateaMoneda(monto) {
		// Formatear el monto como una cadena separada por comas y con un signo de dólar
		const monto_format =
			"$" + monto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		return monto_format;
	}
	if (offset < totalCourses) {
		$("#load-more-courses").fadeIn();
		console.log("apareci");
	}

	$("#load-more-courses").on("click", function () {
		console.log("me precionaron -" + clientSlug + "- " + offset);
		$.ajax({
			url: baseURL + "/ccourses/loadMoreCourses/",
			method: "POST",
			data: {
				client_slug: clientSlug,
				offset: offset,
			},
			dataType: "json",
			success: function (response) {
				response.courses.forEach(function (curso) {
					var image = curso.image_int;
					const name = stot(curso.name);
					const clientName = stot(curso.client_name);
					const clientSlug = curso.client_slug;
					const slug = curso.slug;
					const description = curso.description;
					const idCliente = curso.client_id;
					const idCurso = curso.id;
					const fav = curso.favorite;
					const price = curso.price;
					var classVar = "";
					var marcaVar = "";
					const totalFavorites = curso.total_favorites;
					if (fav) {
						classVar = "active";
						marcaVar = "isFav";
					}

					if (!image) {
						const noimage = [1, 2, 3, 4, 5, 6, 7, 8];
						const randomIndex = Math.floor(Math.random() * noimage.length);
						const randomNoImage = noimage[randomIndex];
						image =
							"https://cursovia.com/public/imgs/nophoto0" +
							randomNoImage +
							".jpg";
					} else {
						image = "https://w3.kampusproject.com/" + image;
					}

					const hover = ["one", "two", "thr", "fou"];
					const randomHover = hover[Math.floor(Math.random() * hover.length)];

					// Construir el HTML del curso
					var cursoHTML = `
<div class="col-md-4 courses-box grid-item ${marcaVar}">
  <div class="content ${classVar} ${randomHover}">
      <a href="${baseURL}/mostrar/${slug}">
          <div class="datos">
			  <a href="${baseURL}/mostrar/${slug}"><h1>${name}</h1></a>
              <a href="${baseURL + clientSlug}"><h2>${clientName}</h2></a>
          </div>
`;

					// Comprobar si el precio no es 'Cotizar' y agregarlo al HTML
					const client = response.client.name;
					if (price !== "Cotizar" && !client) {
						cursoHTML += `
						<a href="${baseURL}/mostrar/${slug}">
						<span class="price">${formateaMoneda(price)}</span></a>`;
					} else if (price !== "Cotizar" && client) {
						console.log(response.client.color_second);
						cursoHTML += `
						<a href="${baseURL}/mostrar/${slug}" class="price" style="color:${
							response.client.color_second
						}">
						<span>${formateaMoneda(price)}</span></a>`;
					} else if (price == "Cotizar" && client) {
						cursoHTML += `
						<a href="${baseURL}/mostrar/${slug}" class="price" style="color:${response.client.color_second}">
						<span>${price}</span></a>`;
					} else {
						cursoHTML += `<a href="${baseURL}/mostrar/${slug}">
						<span class="price">${price}</span></a>`;
					}

					// Continuar construyendo el HTML del curso
					cursoHTML += `
          <div class="imgViewer" style="background:url('${image}') no-repeat; background-size: cover;"></div>
      </a>
      <div class="row share-menu">
          <div class="col-3" style="padding-left: 0;">
              <i class="fas fa-heart fav ${classVar}" data-idcurso="${idCurso}">
                  <span style="font-family:arial; font-size: 12px;" id="fav-${idCurso}">${totalFavorites}</span>
              </i>
          </div>
          <div class="col-9 share-bar index">
              <span style="margin-right: 10px">Compartir en:</span> 
              <span> 
                  <ul>
                      <li><a href="https://www.facebook.com/sharer/sharer.php?u=${baseURL}/mostrar/${slug}" style="background-image: url('public/imgs/facebook.svg');" target="_blank"></a></li>
                      <li><a href="https://api.whatsapp.com/send?text=Hola!%20te%20Env%C3%ADo%20este%20interesante%20link%20${baseURL}/mostrar/${slug}" style="background-image: url('public/imgs/whatsapp.svg');" target="_blank"></a></li>
                  </ul>
              </span>
          </div>
      </div>
  </div>
</div>
`;

					// Agregar el HTML del curso al contenedor de cursos
					try {
						$("#cursos-container").append(cursoHTML);
						loadMasonry();
					} catch (error) {
						console.log(error);
					}
				});

				offset += response.courses.length;

				if (offset >= totalCourses) {
					$("#load-more-courses").fadeOut();
				}
			},
			error: function (xhr, status, error) {
				console.error("Error al cargar más cursos:", error);
			},
		});
	});

	var $subir = $(".subir");

	$(window).scroll(function () {
		// Obtener la posición vertical del scroll
		var scrollPos = $(window).scrollTop();

		// Mostrar el botón si la posición del scroll es mayor a 500px (ajusta este valor según tus necesidades)
		if (scrollPos > 500) {
			$subir.fadeIn();
		} else {
			$subir.fadeOut();
		}
	});

	$subir.click(function () {
		// Obtener la posición superior de la barra de búsqueda
		var searchBarTop = $("#SearchBar").offset().top;

		// Calcular la posición a la que deseas desplazarte
		var scrollToPos = searchBarTop - 200; // Resta una cantidad de píxeles para dejar la barra en el medio

		// Hacer scroll suavemente a la posición calculada
		$("html, body").animate(
			{
				scrollTop: scrollToPos,
			},
			800
		); // Duración de la animación en milisegundos
	});
});
