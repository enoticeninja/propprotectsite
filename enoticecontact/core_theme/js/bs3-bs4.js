            var n = function(t) {
                "" != t.val() ? t.addClass("edited") : t.removeClass("edited")
            };
            $("body").on("keydown", ".form-md-floating-label .form-control", function(t) {
                n($(this))
            }), $("body").on("blur", ".form-md-floating-label .form-control", function(t) {
                n($(this))
            }), $(".form-md-floating-label .form-control").each(function() {
                //console.log($(this).val());
                if($(this).val() != null) $(this).val().length > 0 && $(this).addClass("edited")
            });