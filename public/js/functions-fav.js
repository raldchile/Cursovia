$(document.body).on("click", ".fav", function () {
	var that = $(this);
	var idcurso = $(this).data("idcurso");
	$.ajax({
		url: baseURL + "/cfavorites/fav/" + idcurso,
		context: document.body,
	}).done(function (data) {
		if (data) {
			that.parent(".content").toggleClass("active");
			that.toggleClass("active");
			var fav = data.split("|");
			$("#cantfav").html("(" + fav[0] + ")");
			if (!fav[1]) {
				fav[1] = 0;
			} else if (fav[1] > 1000) {
				fav[1] = "+999";
			}
			$("#fav-" + idcurso).html(fav[1]);
		} else {
			$("#login-button").trigger("click");
		}
	});
});
/*  $( ".fav" ).click(function() {

          that = $(this);

          idcurso = $(this).data("idcurso");
          console.log(idcurso);
          $.ajax({
          url: baseURL+"/cfavorites/fav/"+idcurso,
          context: document.body
          }).done(function(data) {
            if(data){
              //alert(data);
                that.parent('.content').toggleClass('active');
                that.toggleClass('active');
                fav = data.split("|");
                $('#cantfav').html('('+fav[0]+')');
                if(!fav[1]){
                  fav[1] = 0;
                }else if(fav[1]>1000){
                  fav[1] = '+999';
                }
                // $('#fav-'+idcurso).html(fav[1]+' me gusta');
                $('#fav-'+idcurso).html(fav[1]);
            }else{
               $('#login-button').trigger('click');
            }

          });
        }); */

$("#go").click(function () {
	$("#buscar").submit();
});
