function login(){
     /* Get by Jquery */
     $.ajax({
        type: "POST",
        url: "../controller/Controller.php",
        data: $("#frmLogin").serialize(),
        datatype: "text/html",
        success: function (response) {
             if (parseInt(response) !== 0) {
                let datosJSON = JSON.parse(response);
                alert( datosJSON[0]['usuario'] );
             } else {
                 alert('No such user or password');
             }
        }
    });
}
function toPay() {
	let total = 0;
	let ship = parseFloat($('#items_shipment_field').val().replace(/[^0-9\.-]+/g,""));
	$('.total_price').each(function(i) {
		total += parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
	});
	$('#items_totals_field').val(parseFloat(total).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
	$('#grand_totals_field').val(parseFloat(total+ship).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
	return false;
}
$('body').on('change', "#countryDD", function() {
	$.get('/user/citylist', {countryCD: $("#countryDD").val()})
	 .done(function(result) {
		 $("#cityDD").html(result);
	 })
	 .fail(function(fail) {
		 console.log(fail);
	 });
});

$(function() {
	if ($('#items_part').length >0) toPay(); //
	$("body").on('change', "#file_select", function() {
	    if (this.files && this.files[0]) {
	        let reader = new FileReader();

	        reader.onload = function (e) {
	            $('#image_holder').prop('src', e.target.result);
	        };
	        reader.readAsDataURL(this.files[0]);
	    }
	});
//
// tabs
//
	if ($("#updateprofile").length > 0) {
		let currentTab = 0;
		showTab(currentTab);
		function showTab(n) {
		  let x = $(".tab");
		  $(x[n]).show();
		  if (n == 0) {
			  $("#prevBtn").hide();
		  } else {
			  $("#prevBtn").show();
		  }
		  $("#nextBtn").text((n == (x.length - 1))?"Submit":"Next")
		  fixStepIndicator(n)
		}
		function nextPrev(n) {
			let x = $(".tab");
		    if (n == 1 && !validateForm()) return false;
		    $(x[currentTab]).hide();
		    currentTab += n;
		    if (currentTab >= x.length) {
		      $("#updateprofile").submit();
		      return false;
		    }
		    showTab(currentTab);
		}
		function validateForm() {
		  let x, y, valid = true;
		  x = $(".tab");
		  y = $(x[currentTab]).find("input");
		  for (let i = 0; i < y.length; ++i) {
		    if ($(y[i]).hasClass("required") && y[i].value == "") {
		      $(y[i]).addClass("invalid");
		      valid = false;
		    } else {
		      $(y[i]).removeClass("invalid");
		    }
		  }
		  if (valid) {
		    $($(".step")[currentTab]).addClass("finish");
		  }
		  return valid;
		}
		function fixStepIndicator(n) {
			  let x = $(".step");
			  for (let i = 0; i < x.length; ++i) {
			    $(x[i]).removeClass("active");
			  }
			  $(x[n]).addClass("active");
		}
		$("#nextBtn").on('click', function() {nextPrev(1);});
		$("#prevBtn").on('click', function() {nextPrev(-1);});
	}


	if ($("#login-time").length > 0) {
		let countDownSec = $("#login-time").data('seconds');
		let x = setInterval(function() {

			let distance = countDownSec -  Math.floor($.now()/1000);
			let minutes = Math.floor((distance % (60 * 60)) / 60);
			let seconds = Math.floor(distance % 60);

			$("#login-time").text(`${minutes}m ${seconds}s`);

		    if (distance < 0) {
		       clearInterval(x);
		    $("#login-time").text("You may login now");
		  }
		}, 1000);
	}
	$("#password").on("cut copy paste",function(e) {
      e.preventDefault();
    });
	// Password creation
	$("#password .signup").on('focusin', function() {
			$("#password-strength").show();
		}).on('focusout', function() {
			$("#password-strength").hide();
		}).on('keyup', function() {
			let sml = cap = numb = len = ver = false;
			if (($(this).val().match(/[a-z]/g) || []).length ) {
				$("#letter").addClass("valid").removeClass('invalid');
				sml = true;
			} else {
				$("#letter").addClass("invalid").removeClass('valid');
			}
			if (($(this).val().match(/[A-Z]/g) || []).length ) {
				$("#capital").addClass("valid").removeClass('invalid');
				cap = true;
			} else {
				$("#capital").addClass("invalid").removeClass('valid');
			}
			if (($(this).val().match(/[0-9]/g) || []).length ) {
				$("#number").addClass("valid").removeClass('invalid');
				numb = true;
			} else {
				$("#number").addClass("invalid").removeClass('valid');
			}
			if (($(this).val() || []).length >=8) {
				$("#length").addClass("valid").removeClass('invalid');
				len = true;
			} else {
				$("#length").addClass("invalid").removeClass('valid');
			}
			if ($(this).val() == $("#password-repeat").val()) {
				$("#password-repeat").addClass("bg-success").removeClass('bg-warning');
				ver = true;
			} else {
				$("#password-repeat").addClass("bg-warning").removeClass('bg-success');
			}
			if (sml && cap && numb && len) {
				$("#password").addClass("bg-success");
				if (ver) {
					$("#password-repeat").addClass('bg-success');
				} else {
					$("#password-repeat").removeClass('bg-success');
				}
			} else {
				$("#password").removeClass("bg-success");
				$("#password-repeat").removeClass('bg-success')
			}
		});
	$("#password-repeat").on('keyup', function() {
		if ($(this).val() == $("#password").val()) {
			$("#password-repeat").addClass("bg-success").removeClass('bg-warning');
		} else {
			$("#password-repeat").addClass("bg-warning").removeClass('bg-success');
		}
	});
	$("#username").on('focusout', function() {
		let user=$(this).val();
		if (user.length>0) {
		     $.get("/user/userFound", { username: user })
		     	.done(function (result) {
		              if (result) {
		      			$("#username").addClass("redundant-name").removeClass("unique-name");
		              } else {
		      			$("#username").addClass("unique-name").removeClass("redundant-name");
		              }
		         	})
		         .fail( console.log("Ajax failed"));
		     }
	});
	$("#hide-show-passwword").on('click', function() {
		if (($("#password").prop('type')==="password")) {
			$("#password").prop('type', "text");
			$("#hide-show-passwword .fa").addClass("text-danger fa-eye").removeClass("text-success fa-eye-slash");
		} else {
			$("#password").prop('type', "password");
			$("#hide-show-passwword .fa").addClass("text-success fa-eye-slash").removeClass("text-danger fa-eye");
		}
	});
	$('body').on('click', '.signupbtn', function(e) {
		e.preventDefault();
		if (!$("#username").hasClass("redundant-name")
			&& $("#password-repeat").hasClass('bg-success')
			&& $("#password").hasClass("bg-success")) {
			let form = $(this).closest('form');
			let action=form.prop('action');
			let formdata=form.serializeArray();

		    $.post(action, formdata)
		     .done(function (result) {
		              if (result=='FOUND') {
		            	$("#message-dupl").show(500).delay(2000).hide(1000);
		              } else if (result=='SUCCESS') {
			            $("#message-save").show(500).delay(2000).hide(1000);
		              }
		         	})
		     .fail( console.log("Ajax failed"));
		    return false;
		}
	});
	$('body').on('click', '.send-to-wish', function() {
		let id=$(this).prop('id').substring(5);
		let todo;
		let icon = $(this).find('i');
		let target = $("#wish-list").find('i');
		let items = parseInt(target.attr('data-items')) || 0;
		if ($(icon).hasClass('fa-heart')) {
			$(icon).removeClass('fa-heart text-success').addClass('fa-heart-o');
			todo = 'del';
			--items;
		} else {
			$(icon).removeClass('fa-heart-o').addClass('fa-heart text-success');
			todo = 'add';
			++items;
			let icon2 = $(`#cart-${id}`).find('i');
			let target2 = $("#shopping-cart").find('i');
			let items2 = parseInt(target2.attr('data-items')) || 0;
			if ($(icon2).hasClass('fa-shopping-cart')) {
				$(icon2).removeClass('fa-shopping-cart text-success').addClass('fa-cart-plus');
				--items2;
				$(target2).attr('data-items', items2);
				if (items2) {
					target2.removeClass('shopping-empty').addClass('shopping');
				} else {
					target2.removeClass('shopping').addClass('shopping-empty');
				}
			}
		}
		$(target).attr('data-items', items);

		if (items) {
			target.removeClass('shopping-empty').addClass('shopping');
		} else {
			target.removeClass('shopping').addClass('shopping-empty');
		}
		$.ajax({
			type: 'PUT',
			url: '/item/handle',
			data: {id: id, place: 'WISH', todo: todo},
			success: console.log('Wish action')
		});
	});
	$('body').on('click', '.send-to-cart', function() {
		let id=$(this).prop('id').substring(5);
		let todo;
		let icon = $(this).find('i');
		let target = $("#shopping-cart").find('i');
		let items = parseInt(target.attr('data-items')) || 0;
		if ($(icon).hasClass('fa-cart-plus')) {
			$(icon).removeClass('fa-cart-plus').addClass('fa-shopping-cart text-success');
			todo = 'add';
			++items;
			let icon2 = $(`#wish-${id}`).find('i');
			let target2 = $("#wish-list").find('i');
			let items2 = parseInt(target2.attr('data-items')) || 0;
			if ($(icon2).hasClass('fa-heart')) {
				$(icon2).removeClass('fa-heart text-success').addClass('fa-heart-o');
				--items2;
				$(target2).attr('data-items', items2);
				if (items2) {
					target2.removeClass('shopping-empty').addClass('shopping');
				} else {
					target2.removeClass('shopping').addClass('shopping-empty');
				}
			}
		} else {
			$(icon).removeClass('fa-shopping-cart text-success').addClass('fa-cart-plus');
			todo = 'del';
			--items;
		}
		$(target).attr('data-items', items);

		if (items) {
			target.removeClass('shopping-empty').addClass('shopping');
		} else {
			target.removeClass('shopping').addClass('shopping-empty');
		}
		$.ajax({
			type: 'PUT',
			url: '/item/handle',
			data: {id: id, place: 'CART', todo: todo},
			success: console.log('Cart action')
		});
	});
	$('body').on('dblclick', '.editable', function() {
		$(this).prop('disabled', false);
	});
	$('body').on('blur', '.editable', function() {
		let parts = $(this).prop('id').split('__');
		$(this).prop('disabled', true);
		$.ajax({
			type: 'PUT',
			url: '/item/edit',
			data: {id: parts[1], field: parts[0], value: $(this).val()},
			success: function(result) {
						if (result!=="Success") {
							$("#error-explanation").text(result);
							$("#message-failure").show(500).delay(2000).hide(1000);
						} else {
							$("#message-success").show(500).delay(2000).hide(1000);
						}
				},
			failure: function() {
					$("#message-failure").show(500).delay(2000).hide(1000);
				}
		});
	});
	$('body').on('change', '.checkboxed', function(e) {
		let box = $(this);
		let id = box.prop('id');
		let parts = id.split('__');
		let newValue = (box.prop('checked'))?1:0;
		$.ajax({
//			async: false,
			type: 'PUT',
			url: '/item/edit',
			data: {id: parts[1], field: parts[0], value: newValue},
			success: function(result) {
						if (result!=="Success") {
							$("#error-explanation").text(result);
							$("#message-failure").show(500).delay(2000).hide(1000);
						} else {
							$("#message-success").show(500).delay(2000).hide(1000);
							if (newValue) {
								box.closest('tr').removeClass('danger');
								box.prop('value', 'on');
								box.prop('checked', true)
							} else {
								box.closest('tr').addClass('danger');
								box.prop('value', 'off');
								box.prop('checked', false)
							}
						}
				}
		});
	});

	$('body').on('click', '#add_new_item', function(e) {
		let fields = $("#updateprofile :input[required]").filter(function(){return $(this).val() == ""; })
		if (fields.length) {
			let safeFields = $("#updateprofile :input[required]").filter(function(){return $(this).val() != ""; })
			fields.remove('bg-success').addClass('bg-danger');
			safeFields.removeClass('bg-danger').addClass('bg-success');
			$("#error-explanation").text("Please fill all the marked fields");
            $("#message-failure").show(500).delay(3000).hide(1000);
		} else {
			let form = $(this).closest('form');
			let action=form.prop('action');
			let formdata=form.serializeArray();
			formdata.push({name:'picture', value: $("input:file").prop('files')[0]['name']});
		    $.post(action, formdata)
		     .done(function (response) {
		    	 	  let result = JSON.parse(response);
		              if (result.operation=='Success') {
		            	$("#message-success").show(500).delay(2000).hide(1000);
		            	$('#items_body').append(result.newArray);
		            	$("#updateprofile :input").val("");
		              } else {
		            	$("#error-explanation").text(result.operation);
			            $("#message-failure").show(500).delay(2000).hide(1000);
		              }
		         	})
		     .fail( console.log("Adding of item failed"));
		    return false;
		}
	});
	$('body').on('click', '.delete_row', function(e) {
		let box = $(this);
		let id = box.prop('id');
		let parts = id.split('__');
		let newValue = (box.prop('checked'))?1:0;
		$.ajax({
			type: 'DELETE',
			url: '/item/delete',
			data: {id: parts[1]},
			success: function(response) {
						if (response=="Success") {
			            	$("#message-success").show(500).delay(2000).hide(1000);
			            	$("#row__"+parts[1]).remove();
						} else {
			            	$("#error-explanation").text("Could not delete that item!");
				            $("#message-failure").show(500).delay(2000).hide(1000);
						}
				}
		});
	});
	$('body').on('click', '.shopping', function(e) {
		let id = $(this).closest('li').prop('id');
		if (id=="wish-list") {
			window.location.assign("/purchase/wish");
		} else if (id=="shopping-cart") {
			window.location.assign("/purchase/cart");
		}
		return false;
	});
	$('body').on('click', '#accept_conditions', function() {
		if ($(this).find('input').prop('checked')) {
			$('#pay_confirm').prop('disabled', false)
				 .removeClass('btn-warning')
				 .addClass('btn-success')
				 .prop('title', "Click to confirm the purchase of the items in your shopping cart");
		} else {
			$('#pay_confirm').prop('disabled', true)
				 .removeClass('btn-success')
				 .addClass('btn-warning')
				 .prop('title', "You have to agree on the condition in order to continue.");
		}
	});
	$('body').on('change', '.qtty_price', function() {
			let parts = $(this).prop('id').split('__');
			let key = parts[1];
			let qtty = $(this).val();
			let max = $(this).data('max');
			if (!$.isNumeric(qtty) || qtty < 1) {
				$(this).val(1);
				qtty = 1;
			} else if (qtty > max) {
				$(this).val(max);
				qtty = max;
			}
			let unit = $("#unit__"+key).val();
			$("#total__"+key).val(parseFloat(qtty * unit).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
			toPay();
			return false;
	});
	$('body').on('click', '.toggle_to_wish', function(e) {
		let id = $(this).prop('id');
		let parts = id.split('__');
		let parent = $(this).parents('div').eq(2);
		$.post('/purchase/towish', {id: parts[1]})
		 .done(function(response) {
					if (response=="Success") {
		            	parent.remove();
		        		let source = $("#shopping-cart").find('i');
		        		let inCart = parseInt(source.attr('data-items')) || 0;
	        			--inCart;
         				$(source).attr('data-items', inCart);
	        			let target = $("#wish-list").find('i');
	        			let inWish = parseInt(target.attr('data-items')) || 0;
        				++inWish;
        				$(target).attr('data-items', inWish);
        				if (inWish) {
        					target.removeClass('shopping-empty').addClass('shopping');
        				}
         				if (inCart==0) {
		        			source.removeClass('shopping').addClass('shopping-empty');
		        			window.location.assign("/");
		        		} else {
		        			toPay();
		        		}
					} else {
		            	$("#error-explanation").text(response);
			            $("#message-failure").show(500).delay(2000).hide(1000);
					}
			});
	});
	$('body').on('click', '.delete_from_cart', function(e) {
		let id = $(this).prop('id');
		let parts = id.split('__');
		let parent = $(this).parents('div').eq(2);
		$.post('/purchase/disposeof', {id: parts[1]})
		 .done(function(response) {
					if (response=="Success") {
		            	parent.remove();
		        		let source = $("#shopping-cart").find('i');
		        		let inCart = parseInt(source.attr('data-items')) || 0;
	        			--inCart;
         				$(source).attr('data-items', inCart);
         				if (inCart==0) {
		        			source.removeClass('shopping').addClass('shopping-empty');
		        			window.location.assign("/");
		        		} else {
		        			toPay();
		        		}
					} else {
		            	$("#error-explanation").text(response);
			            $("#message-failure").show(500).delay(2000).hide(1000);
					}
			});
	});
	$('body').on('click', '#go_back', function() {
		window.history.back();
	});
});
