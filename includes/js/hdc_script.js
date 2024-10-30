/*
	HDC front end script
*/

jQuery(window).load(function() {
	console.log("HD Commerce loaded");
	hdc_currency_symbol = hdc_currency_symbol.split("|");

	// if we are on the cart page
	if (jQuery("#hdc_cart_table").length) {
		populateStates();
	}

	// get product data
	if (jQuery("body").hasClass("single-hdc_product")) {
		// show the default tab
		var activeTab = jQuery("#hdc_tabs .hdc_active_tab").attr("data-hdc-content");
		if (activeTab != "" && activeTab != null) {
			jQuery("#" + activeTab).addClass("hdc_tab_active");
			jQuery(".hdc_tab_active").slideDown(500);
		}
		initSingleProduct();
	}

	// run the custom gateway script
	if (hdc_id == hdc_checkout_page) {
		try {
			window["hdc_start_" + hdc_payment_gateway]();
		} catch (e) {
			//
		}
	}
});

// Tab navigation
// ______________________________________________

jQuery("#hdc_tabs li").click(function() {
	jQuery("#hdc_tabs li").removeClass("hdc_active_tab");
	jQuery(this).addClass("hdc_active_tab");
	var hdcContent = jQuery(this).attr("data-hdc-content");

	jQuery(".hdc_tab_active").fadeOut(500);
	jQuery(".hdc_tab").removeClass("hdc_tab_active");
	jQuery("#" + hdcContent)
		.delay(500)
		.fadeIn(500);
	jQuery("#" + hdcContent).addClass("hdc_tab_active");

	if (jQuery("body").hasClass("toplevel_page_hdc_options")) {
		jQuery("#hdc_last_tab").val(hdcContent);
	}
});

// Product page, on image select
// ______________________________________________

var hasCreatedGallery = false;
// if gallery image is selected
jQuery(".hdc_product_gallery").click(function() {
	jQuery("#hdc_gallery .hdc_gallery_image").hide();
	jQuery("#hdc_gallery .hdc_gallery_image").removeClass("hdc_gallery_active");
	jQuery("#hdc_gallery #hdc_gallery_next, #hdc_gallery #hdc_gallery_prev").removeClass("hdc_gallery_arrow_disabled");

	// dynamically create the HTML and load the full images
	// this is to stop the page from loading the full images if they are not needed

	// store the selected image URL so that it becomes the first in the array
	var firstImageUrl = jQuery(this).attr("data-hdc-gallery-url");
	if (hasCreatedGallery == false) {
		createGallery(firstImageUrl);
	} else {
		jQuery("#hdc_gallery").fadeIn(500);
	}
	hasCreatedGallery = true;
	jQuery("#hdc_gallery .hdc_gallery_image").each(function(index) {
		if (jQuery(this).attr("data-gallery-url") == firstImageUrl) {
			jQuery(this).addClass("hdc_gallery_active");
			jQuery(this).fadeIn(500);
		}
	});

	// TODO: don't display arrows if there are no gallery images
	if (!jQuery(".hdc_gallery_active").next(".hdc_gallery_image")[0]) {
		jQuery("#hdc_gallery_next").addClass("hdc_gallery_arrow_disabled");
	} else if (!jQuery(".hdc_gallery_active").prev(".hdc_gallery_image")[0]) {
		jQuery("#hdc_gallery_prev").addClass("hdc_gallery_arrow_disabled");
	}
});

// Close the gallery
// ______________________________________________

jQuery("#hdc_gallery_content, #hdc_gallery_close").click(function() {
	jQuery("#hdc_gallery").fadeOut(500);
});

// Show the next gallery image
// TODO: Swipe guestures
// ______________________________________________

jQuery("#hdc_gallery_content, #hdc_gallery_next").click(function() {
	// first check to make sure there is another element
	if (jQuery(".hdc_gallery_active").next(".hdc_gallery_image")[0]) {
		jQuery("#hdc_gallery_prev").removeClass("hdc_gallery_arrow_disabled");
		jQuery(".hdc_gallery_active").fadeOut(300);
		jQuery(".hdc_gallery_active")
			.next(".hdc_gallery_image")
			.delay(350)
			.css("display", "flex")
			.fadeIn(1000);
		jQuery(".hdc_gallery_active")
			.next(".hdc_gallery_image")
			.addClass("hdc_gallery_active");
		jQuery(".hdc_gallery_active")
			.first()
			.removeClass("hdc_gallery_active");
		// check if we are at the end or not
		if (!jQuery(".hdc_gallery_active").next(".hdc_gallery_image")[0]) {
			jQuery("#hdc_gallery_next").addClass("hdc_gallery_arrow_disabled");
		}
	}
});

// Show the previous gallery image
// ______________________________________________

jQuery("#hdc_gallery_content, #hdc_gallery_prev").click(function() {
	// first check to make sure there is another element
	if (jQuery(".hdc_gallery_active").prev(".hdc_gallery_image")[0]) {
		jQuery("#hdc_gallery_next").removeClass("hdc_gallery_arrow_disabled");
		jQuery(".hdc_gallery_active").fadeOut(300);
		jQuery(".hdc_gallery_active")
			.prev(".hdc_gallery_image")
			.delay(350)
			.css("display", "flex")
			.fadeIn(500);
		jQuery(".hdc_gallery_active")
			.prev(".hdc_gallery_image")
			.addClass("hdc_gallery_active");
		jQuery(".hdc_gallery_active")
			.last()
			.removeClass("hdc_gallery_active");
		// check if we are at the end or not
		if (!jQuery(".hdc_gallery_active").prev(".hdc_gallery_image")[0]) {
			jQuery("#hdc_gallery_prev").addClass("hdc_gallery_arrow_disabled");
		}
	}
});

// Populate the gallery slider
// ______________________________________________

function createGallery(firstImageUrl) {
	var imagesArray = [firstImageUrl];

	// loop through other gallery images to continue building array
	jQuery(".hdc_product_gallery").each(function(index) {
		var imageUrl = jQuery(this).attr("data-hdc-gallery-url");
		if (imageUrl != firstImageUrl) {
			imagesArray.push(imageUrl);
		}
	});

	// loop through array to build HTML
	var totalGalleryImages = imagesArray.length;
	for (var i = 0; i < totalGalleryImages; i++) {
		var data =
			'<div class = "hdc_gallery_image" data-gallery-url = "' +
			imagesArray[i] +
			'"><img src = "' +
			imagesArray[i] +
			'" alt =""/></div>';
		jQuery("#hdc_gallery_content").append(data);
	}

	jQuery("#hdc_gallery").fadeIn(1000);
	var totalImages = jQuery("#hdc_gallery .hdc_gallery_image").length;

	jQuery("#hdc_gallery .hdc_gallery_image").each(function(index) {
		if (jQuery(this).attr("data-gallery-url") == firstImageUrl) {
			jQuery(this).addClass("hdc_gallery_active");
			jQuery(this).fadeIn(500);
			if (totalImages > 1) {
				if (!jQuery(".hdc_gallery_active").next(".hdc_gallery_image")[0]) {
					jQuery("#hdc_gallery_next").addClass("hdc_gallery_arrow_disabled");
				} else if (!jQuery(".hdc_gallery_active").prev(".hdc_gallery_image")[0]) {
					jQuery("#hdc_gallery_prev").addClass("hdc_gallery_arrow_disabled");
				}
			} else {
				// disable the arrows
				jQuery("#hdc_gallery_prev, #hdc_gallery_next").addClass("hdc_gallery_arrow_disabled");
			}
		}
	});
}

// Create the safeId
// ______________________________________________

function createSafeId(val, slice) {
	val = val.toLowerCase();
	val = encodeURIComponent(val);
	val = val.replace(/%20/g, "-");
	val = val.replace(/%26/g, "and");
	val = val.replace(/%7C/g, "|"); // convert back to pipeline
	if (slice) {
		val = val.slice(0, -1);
	}
	return val;
}

// Remove product from cart
// ______________________________________________

jQuery(".hdc_cart_remove_product").click(function() {
	var tr = this;
	var key = jQuery(".hdc_cart_remove_product").index(tr);
	var ajaxUrl = hdc_ajax;
	// hide it so that multiple submitions are not made
	jQuery(this).hide();
	jQuery.ajax({
		type: "POST",
		data: {
			action: "hdc_remove_product_cart",
			key: key
		},
		url: ajaxUrl,
		success: function(data) {
			var remove = jQuery(tr)
				.parent()
				.parent();
			jQuery(remove).hide(500);
			setTimeout(function() {
				jQuery(remove).remove();
				recalculateTotals();
			}, 600);
		},
		error: function() {
			jQuery(tr).show();
		},
		complete: function() {
			// complete
		}
	});
});

// Continue to checkout button
// ______________________________________________

jQuery("#hdc_cart_continue").click(function() {
	jQuery(this).remove();
	jQuery("#hdc_cart").addClass("active");
	jQuery("#hdc_checkout").addClass("active");
});

// Cart quantity change
// ______________________________________________

jQuery("#hdc_cart_table .hdc_cart_quantity").change(function() {
	// first, make sure there is enough quantity
	let stock = parseInt(this.getAttribute("max"));
	if(this.value > stock){
		this.value = stock;	
	}
	
	updateCartCookie(this);
	getShippingInfo();
	recalculateTotals();	
	
});

async function updateCartCookie(el) {
	let data = {
		quantity: el.value,
		permutation: el.getAttribute("data-variation"),
		id: el.getAttribute("data-id")
	};
	console.log(data)
	var ajaxUrl = hdc_ajax;
	jQuery.ajax({
		type: "POST",
		data: {
			action: "hdc_update_cart_cookie_quantity",
			data: data
		},
		url: ajaxUrl,
		success: function(data) {
			console.log(data);
			// on success, submit the form
		},
		error: function(e) {
			return false;
		}
	});

	return false;
}

function recalculateTotals() {
	jQuery(".hdc_cart_total").html("&#x221e;");

	var productAmount = getProductAmount();
	var discount = getCartDiscountAmount(productAmount);
	var shippingCost = getShippingCost();
	var tax = getTaxAmount();
	var taxAmount = parseTaxAmount(tax, productAmount);

	var total = parseFloat(productAmount) - parseFloat(discount) + parseFloat(shippingCost) + parseFloat(taxAmount);
	var total2 = hdc_get_price(total);

	jQuery(".hdc_cart_total").html(total2);
	jQuery("#hdc_pay span").html(total2);
	jQuery("#hdc_payment_amount").val(total);
	return parseFloat(total).toFixed(2);
}

function getCartDiscountAmount(productAmount) {
	let addedCodes = document.getElementsByClassName("hdc_cart_coupon");
	let value = 0;
	if (addedCodes.length > 0) {
		let code = addedCodes[0];
		code = {
			code: code.getAttribute("data-code"),
			type: code.getAttribute("data-type"),
			symbol: code.getAttribute("data-symbol"),
			amount: parseFloat(code.getAttribute("data-amount"))
		};

		if (code.type === "amount") {
			value = parseFloat(code.amount);
		} else if (code.type === "percentage") {
			productAmount = parseFloat(productAmount);
			let amount = code.amount / 100;
			value = productAmount * amount;
		}
	}
	return value;
}

function getProductAmount() {
	var total = 0;
	jQuery("#hdc_cart_table .hdc_cart_quantity").each(function() {
		var price = jQuery(this).attr("data-price");
		var quantity = jQuery(this).val();
		total = total + price * quantity;
	});
	return total;
}

function getShippingCost() {
	if (hdc_shipping_disable != "1") {
		var shipping = jQuery("#hdc_shipping_method")
			.find(":selected")
			.val();
		if (shipping != "" && shipping != null) {
			shipping = parseFloat(shipping);
		} else {
			shipping = 0;
		}
	} else {
		shipping = 0;
	}
	return shipping;
}

function getTaxAmount(productAmount) {
	tax = [];
	var state = jQuery("#hdc_checkout_state")
		.find(":selected")
		.val();
	if (state != "" && state != null) {
		var country = jQuery("#hdc_checkout_country")
			.find(":selected")
			.val();
		var taxes = hdc_tax;
		taxes = jQuery.parseJSON(taxes);

		// first, find exact match with state/province
		jQuery(taxes).each(function() {
			if (country == this.country && state == this.state) {
				tax.push({
					name: this.taxName,
					value: this.taxValue
				});
			}
		});
		// if there were no exact matches, then just grab the tax for the entire country
		if (tax.length < 1) {
			jQuery(taxes).each(function() {
				if (country == this.country && this.state == "* all States/Provinces ") {
					tax.push({
						name: this.taxName,
						value: this.taxValue
					});
				}
			});
		}
	}
	return tax;
}

function parseTaxAmount(tax) {
	var totalAmount = 0;

	// get total product value
	var total = 0;
	jQuery("#hdc_cart_table .hdc_cart_quantity").each(function() {
		var price = jQuery(this).attr("data-price");
		var quantity = jQuery(this).val();
		total = total + price * quantity;
	});

	var discount = getCartDiscountAmount(total);
	total = total - discount;

	// remove existing tax tr
	jQuery("#hdc_cart_table tr.tax").remove();

	jQuery(tax).each(function() {
		var amount = 0;
		amount = this.value / 100;
		amount = total * amount;
		totalAmount = totalAmount + amount;
		var data =
			'<tr class = "tax"><td>' +
			this.name +
			": " +
			this.value +
			"%</td><td>" +
			hdc_get_price(amount) +
			"</td><td></td><td></td></tr>";
		jQuery("#hdc_cart_table tbody").append(data);
	});

	if (hdc_tax_inclusive == "yes") {
		totalAmount = 0;
	} else {
		totalAmount = parseFloat(totalAmount).toFixed(2);
	}
	return totalAmount;
}

// function to clean and convert amount to currency
// ______________________________________________

function hdc_get_price(price) {
	price = parseFloat(price).toFixed(2);
	if (hdc_currency_symbol[2] == "l") {
		price = hdc_currency_symbol[1] + price;
	} else {
		price = price + hdc_currency_symbol[1];
	}
	return price;
}

// On Payment Submit
// ______________________________________________

function hdc_cart_submit() {
	// Disable the submit button to prevent repeated clicks:
	jQuery("#hdc_checkout_form")
		.find("#hdc_pay")
		.prop("disabled", true);
	jQuery("#hdc_checkout_form")
		.find("#hdc_pay")
		.fadeOut();

	// get shipping name
	if (hdc_shipping_disable != "1") {
		var hdc_shipping_method_name = jQuery("#hdc_shipping_method")
			.find(":selected")
			.html();
		if (hdc_shipping_method_name != "Free Shipping") {
			hdc_shipping_method_name = hdc_shipping_method_name.split("|");
			hdc_shipping_method_name = hdc_shipping_method_name[1];
		}
		jQuery("#hdc_shipping_method_name").val(hdc_shipping_method_name);
	} else {
		jQuery("#hdc_shipping_method_name").val("No shipping");
	}

	// get tax info
	var tax = getTaxAmount();
	var taxAmount = parseTaxAmount(tax);
	tax = JSON.stringify(tax);
	jQuery("#hdc_checkout_tax").val(tax);
	jQuery("#hdc_checkout_tax_amount").val(taxAmount);

	// get coupons
	let coupons = document.getElementsByClassName("hdc_cart_coupon");
	let coupon_codes = [];
	for (let i = 0; i < coupons.length; i++) {
		let c = {
			code: coupons[i].getAttribute("data-code"),
			type: coupons[i].getAttribute("data-type"),
			amount: coupons[i].getAttribute("data-amount")
		};
		coupon_codes.push(c);
	}
	coupons = JSON.stringify(coupon_codes);
	jQuery("#hdc_coupon_codes").val(coupons);
	// get products
	var products = [];
	jQuery(".hdc_cart_quantity").each(function() {
		var productId = jQuery(this).attr("data-id");
		var productName = jQuery(this).attr("data-name");
		var productVariation = jQuery(this).attr("data-variation");
		var productPrice = jQuery(this).attr("data-price");
		var productQuantity = jQuery(this).val();
		products.push({
			id: productId,
			name: productName,
			variation: productVariation,
			price: productPrice,
			quantity: productQuantity
		});
	});
	products = JSON.stringify(products);
	console.log(products);
	jQuery("#hdc_checkout_products").val(products);
}

// Paypal
// ______________________________________________

function hdc_start_paypal() {
	jQuery("#hdc_checkout_form").submit(function(event) {
		event.preventDefault();
		hdc_cart_submit();

		// TODO: Put this in a function so that plugin devs
		// only need to retrieve their custom payment amount

		var jQueryform = jQuery("#hdc_checkout_form");
		// submit the order via ajax before heading to PayPal
		var ajaxUrl = hdc_ajax;

		// TODO! need to grab this info dynamically to be compatible with extra fields

		var hdc_checkout_first_name = jQuery("#hdc_checkout_first_name").val();
		var hdc_checkout_last_name = jQuery("#hdc_checkout_last_name").val();
		var hdc_checkout_email = jQuery("#hdc_checkout_email").val();
		var hdc_checkout_phone = jQuery("#hdc_checkout_phone").val();
		var hdc_checkout_country = jQuery("#hdc_checkout_country")
			.find(":selected")
			.val();
		var hdc_checkout_state = jQuery("#hdc_checkout_state")
			.find(":selected")
			.val();
		var hdc_checkout_address = jQuery("#hdc_checkout_address").val();
		var hdc_checkout_address2 = jQuery("#hdc_checkout_address2").val();
		var hdc_checkout_city = jQuery("#hdc_checkout_city").val();
		var hdc_checkout_zip = jQuery("#hdc_checkout_zip").val();
		var hdc_shipping_method = jQuery("#hdc_shipping_method").val();
		var hdc_shipping_method_name = jQuery("#hdc_shipping_method_name").val();
		var hdc_checkout_tax = jQuery("#hdc_checkout_tax").val();
		var hdc_checkout_tax_amount = jQuery("#hdc_checkout_tax_amount").val();
		var hdc_checkout_note = jQuery("#hdc_checkout_note").val();
		var hdc_checkout_products = jQuery("#hdc_checkout_products").val();
		var hdc_payment_amount = jQuery("#hdc_payment_amount").val();
		var hdc_cart_id = jQuery("#hdc_cart_id").val();
		var hdc_coupon_codes = jQuery("#hdc_coupon_codes").val();
		var hdc_cart_nonce = jQuery("#hdc_cart_nonce").val();
		jQuery("#hdc_paypal_amount").val(recalculateTotals());

		jQuery.ajax({
			type: "POST",
			data: {
				action: "hdc_submit_order",
				hdc_checkout_first_name: hdc_checkout_first_name,
				hdc_checkout_last_name: hdc_checkout_last_name,
				hdc_checkout_email: hdc_checkout_email,
				hdc_checkout_phone: hdc_checkout_phone,
				hdc_checkout_country: hdc_checkout_country,
				hdc_checkout_state: hdc_checkout_state,
				hdc_checkout_address: hdc_checkout_address,
				hdc_checkout_address2: hdc_checkout_address2,
				hdc_checkout_city: hdc_checkout_city,
				hdc_checkout_zip: hdc_checkout_zip,
				hdc_shipping_method: hdc_shipping_method,
				hdc_shipping_method_name: hdc_shipping_method_name,
				hdc_checkout_tax: hdc_checkout_tax,
				hdc_checkout_tax_amount: hdc_checkout_tax_amount,
				hdc_checkout_note: hdc_checkout_note,
				hdc_checkout_products: hdc_checkout_products,
				hdc_payment_amount: hdc_payment_amount,
				hdc_cart_id: hdc_cart_id,
				hdc_coupon_codes: hdc_coupon_codes,
				hdc_cart_nonce: hdc_cart_nonce
			},
			url: ajaxUrl,
			success: function(data) {
				console.log(data);
				// on success, submit the form
				if (data == "complete") {
					alert("There was an error saving your order");
				} else {
					jQuery("#hdc_order_id").val(data);
					jQueryform.get(0).submit();
				}
			},
			error: function() {
				alert("There was an error saving your order");
			}
		});

		return false;
	});
}

// Stripe
// ______________________________________________

function hdc_start_stripe() {
	Stripe.setPublishableKey(hdc_stripe_publishable);
	jQuery("#hdc_checkout_form").submit(function(event) {
		event.preventDefault();
		hdc_cart_submit();
		var jQueryform = jQuery("#hdc_checkout_form");
		// Request a token from Stripe:
		Stripe.card.createToken(jQueryform, hdc_stripeResponseHandler);
		return false;
	});
}

function hdc_start_payengines() {
	var jQueryform = jQuery("#hdc_checkout_form");
	jQuery("#hdc_checkout_form").submit(function(event) {
		event.preventDefault();
		hdc_cart_submit();
		jQueryform.get(0).submit();
	});
}

function hdc_stripeResponseHandler(status, response) {
	// Grab the form:
	var jQueryform = jQuery("#hdc_checkout_form");
	if (response.error) {
		jQueryform.find(".payment-errors").text(response.error.message);
		jQueryform.find("#hdc_pay").prop("disabled", false); // Re-enable submission
		jQueryform.find("#hdc_pay").fadeIn();
		alert(response.error.message);
	} else {
		var token = response.id;
		jQueryform.append(jQuery('<input type="hidden" name="stripeToken">').val(token));
		jQueryform.get(0).submit();
	}
}

// checkout form required fields on input
jQuery("#hdc_contact_shipping").on("keyup", ".required", function(e) {
	var content = jQuery(this).val();
	if (content.length > 0) {
		jQuery(this).addClass("content");
	} else {
		jQuery(this).removeClass("content");
	}
});

// checkout form required fields on change (for select boxes)
jQuery("#hdc_contact_shipping").on("change", "select.required", function(e) {
	var content = jQuery(this)
		.find(":selected")
		.val();
	if (content.length > 0) {
		jQuery(this).addClass("content");
	} else {
		jQuery(this).removeClass("content");
	}
});

// credit card field user enhancements
jQuery("#hdc_payment_form").on("keyup", "#hdc_cart_card", function(e) {
	// only allow numbers
	var key = e.charCode || e.keyCode || 0;
	if ((key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105)) {
		var digits = jQuery(this).val();
		var total = jQuery(this).val().length;
		digits = digits.replace(/ /g, "");
		digits = digits.length;
		if (digits % 4 == 0 && total <= 20) {
			var data = jQuery(this).val();
			var last = data.substr(data.length - 1);
			if (last != " ") {
				jQuery(this).val(data + " ");
			}
		}
	} else {
		var data = jQuery(this).val();
		var last = data.substr(data.length - 1);
		if (isNaN(last)) {
			data = data.slice(0, -1); // remove the last char
			jQuery(this).val(data);
		}
	}
});

// When checkout form field is changed, remove hdc_required
// ______________________________________________

jQuery("#hdc_contact_shipping").on("change", ".hdc_required", function(e) {
	jQuery(this).removeClass("hdc_required");
});

// Check if we can look up shipping methods
// ______________________________________________

jQuery("#hdc_contact_shipping").on("change", ".required-shipping", function(e) {
	getShippingInfo();
});

function getShippingInfo() {
	if (hdc_shipping_disable != "1") {
		var canShip = true;
		jQuery(".required-shipping").each(function() {
			var data = jQuery(this).val();
			if (data == "" || data == null) {
				canShip = false;
			}
		});
		if (canShip) {
			getShippingMethods();
		}
	}
}

// Checkout on country select
// ______________________________________________

jQuery("#hdc_checkout_country").change(function() {
	populateStates();
});

function populateStates() {
	var states = jQuery("#hdc_checkout_country")
		.find(":selected")
		.val();
	if (states != "" && states != null) {
		states = jQuery("#hdc_checkout_country")
			.find(":selected")
			.attr("data-states");
		if (states != "" && states != null) {
			// check if it's a textbox
			if (jQuery("#hdc_checkout_state").hasClass("stateInput")) {
				var parentDiv = jQuery("#hdc_checkout_state").parent();
				jQuery("#hdc_checkout_state").remove();
				var data =
					'<select id="hdc_checkout_state" class="required required-shipping" name="hdc_checkout_state" required="">\
								<option value="">Select State/Provice/County</option>\
							</select>';
				jQuery(parentDiv).html(data);
			}
			// add the new states/provinces
			states = states.split("|");
			jQuery("#hdc_checkout_state").html('<option value="">Select State/Provice/County</option>');
			jQuery(states).each(function() {
				var data = '<option value = "' + this + '">' + this + "</option>";
				jQuery("#hdc_checkout_state").append(data);
			});
		} else {
			// this country has no states or provinces added yet,
			// replace the select box with a input
			var parentDiv = jQuery("#hdc_checkout_state").parent();
			jQuery("#hdc_checkout_state").remove();
			var data =
				'<input id="hdc_checkout_state" class="required required-shipping stateInput" name="hdc_checkout_state" type="text" placeholder="State/Province" required="">';
			jQuery(parentDiv).html(data);
		}
	}
}

// Checkout on state/province select
// ______________________________________________

jQuery("#hdc_checkout_state").change(function() {
	recalculateTotals();
});

// Checkout on shipping change
// ______________________________________________

jQuery("#hdc_shipping_method").change(function() {
	parseShippingMethod();
});

// Load Checkout
// ______________________________________________

jQuery("#hdc_continue_to_checkout").click(function() {
	// validate required fields
	if (!jQuery(this).hasClass("disabled")) {
		var canContinue = true;
		jQuery(".required").each(function() {
			var data = jQuery(this).val();
			if (data == "" || data == null) {
				canContinue = false;
				jQuery(this).addClass("hdc_required");
			}
			// if checkbox
			if (jQuery(this).hasClass("hdc_checkbox_checkout")) {
				if (!jQuery(this).is(":checked")) {
					jQuery(this)
						.parent()
						.parent()
						.addClass("hdc_required");
					canContinue = false;
				}
			}
			// if email
			if (jQuery(this).hasClass("hdc_email_checkout")) {
				// man, this is a crazy regex. Taken from https://hexillion.com/samples/
				var reEmail = /^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!\.)){0,61}[a-zA-Z0-9]?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!$)){0,61}[a-zA-Z0-9]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/;

				if (!data.match(reEmail)) {
					canContinue = false;
					jQuery(this).addClass("hdc_required");
				}
			}
		});

		if (canContinue) {
			jQuery("#hdc_contact_shipping").hide(500);
			showCheckout();
		} else {
			// scroll up to the first hdc_required
			setTimeout(function() {
				jQuery("html, body").animate(
					{
						scrollTop: jQuery("#hdc_contact_shipping")
							.first(".hdc_required")
							.offset().top
					},
					1000
				);
			}, 30);
		}
	}
});

// Get shipping methods from shipping provider
// ______________________________________________

var hdc_shippingCost = 0;

function getShippingMethods() {
	// get total product weight
	var weight = 0;
	var width = 0;
	var height = 0;
	var length = 0;
	var shipping_class = "";
	// TODO: Figure out how to calculate length/width/height
	// for different boxes since the length/width/height is
	// addded to each other creating a super box
	// will have to guess since some shipping methods
	// like royal mail don't provide the box size
	var totalQuantity = 0;
	jQuery(".hdc_cart_quantity").each(function() {
		var quantity = jQuery(this).val();
		totalQuantity = parseInt(totalQuantity) + parseInt(quantity);
		quantity = parseInt(quantity);
		if (jQuery(this).attr("data-weight") != "" && jQuery(this).attr("data-weight") != null) {
			weight = weight + parseFloat(jQuery(this).attr("data-weight")) * quantity;
		}
		width = width + parseFloat(jQuery(this).attr("data-width")) * quantity;
		height = height + parseFloat(jQuery(this).attr("data-height")) * quantity;
		length = length + parseFloat(jQuery(this).attr("data-length")) * quantity;
		var class_quantity = "";
		for (i = 0; i < quantity; i++) {
			class_quantity = class_quantity + jQuery(this).attr("data-shipping-class") + "|";
		}
		shipping_class = shipping_class + class_quantity;
	});
	// trim trailing pipeline
	shipping_class = shipping_class.slice(0, -1);
	// get address info
	var t_address = jQuery("#hdc_checkout_address").val();
	var t_city = jQuery("#hdc_checkout_city").val();
	var t_state = jQuery("#hdc_checkout_state")
		.find(":selected")
		.val();
	var t_country = jQuery("#hdc_checkout_country")
		.find(":selected")
		.attr("data-country-code");
	var t_zip = jQuery("#hdc_checkout_zip").val();
	var ajaxUrl = hdc_ajax;

	// temporarily disable continue to checkout
	jQuery("#hdc_continue_to_checkout").addClass("disabled");
	// update shipping options to show the user that we are calculating and they have to wait
	var data = '<option value = "">calculating shipping...</option>';
	jQuery("#hdc_shipping_method").html(data);

	jQuery.ajax({
		type: "POST",
		data: {
			action: "get_hdc_shipping_methods",
			shipping_class: shipping_class,
			quantity: totalQuantity,
			weight: weight,
			width: width,
			height: height,
			length: length,
			t_address: t_address,
			t_city: t_city,
			t_state: t_state,
			t_country: t_country,
			t_zip: t_zip
		},
		url: ajaxUrl,
		success: function(data) {
			// console.log(data);
			if (data != "" && data != null) {
				// if there were no options returned
				if (data == "no options") {
					var data2 = '<option value = "">ERROR: No Options Found</option>';
					jQuery("#hdc_shipping_method").html(data2);
				} else {
					// if there were returned methods
					jQuery("#hdc_shipping_method").html(data);
					parseShippingMethod();
					jQuery("#hdc_continue_to_checkout").removeClass("disabled");
				}
			} else {
				// if there was an error (like invalid postal code, or the API was down)
				var data2 = '<option value = "0">ERROR: Address or Product issue</option>';
				jQuery("#hdc_shipping_method").html(data2);
			}
		},
		error: function() {
			var data2 = '<option value = "0">ERROR: Server error</option>';
			jQuery("#hdc_shipping_method").html(data2);
		},
		complete: function() {
			// complete
		}
	});
}

function parseShippingMethod() {
	var shippingName = "";

	// if free shipping cart amount is enabled
	if (hdc_shipping_free != null && hdc_shipping_free != "") {
		// convert to float
		hdc_shipping_free = parseFloat(hdc_shipping_free);
		// check the total in the cart
		var cartTotal = getProductAmount();
		if (cartTotal >= hdc_shipping_free) {
			// free shipping!
			hdc_shippingCost = 0;
			jQuery("#hdc_free_shipping").remove();
			var shippingName = ["Free Shipping", "Free Shipping"];
			var data = jQuery("#hdc_shipping_method").html();
			var data2 = '<option value = "0" id = "hdc_free_shipping">' + shippingName[1] + "</option>";
			data = data2 + data;
			jQuery("#hdc_shipping_method").html(data);
		} else {
			jQuery("#hdc_free_shipping").remove();
		}
	}

	hdc_shippingCost = jQuery("#hdc_shipping_method").val();
	if (shippingName == "") {
		var shippingName = jQuery("#hdc_shipping_method")
			.find(":selected")
			.html();
		shippingName = shippingName.split("|");
	}

	recalculateTotals();

	if (jQuery("#hdc_cart_table tr.shipping")[0]) {
		var data =
			"<td>Shipping Method: " +
			shippingName[1] +
			"</td><td>" +
			hdc_get_price(hdc_shippingCost) +
			"</td><td></td><td></td>";
		jQuery("#hdc_cart_table tbody tr.shipping").html(data);
	} else {
		// add new shipping TR to cart table
		var data =
			'<tr class = "shipping"><td>Shipping Method: ' +
			shippingName[1] +
			"</td><td>" +
			hdc_get_price(hdc_shippingCost) +
			"</td><td></td><td></td></tr>";
		jQuery("#hdc_cart_table tbody").append(data);
	}
}

function showCheckout() {
	jQuery("#hdc_payment_form").fadeIn();
	jQuery("span.hdc_cart_notice_tax").hide();
}

// Leave a review
// ______________________________________________

// star rating
jQuery("#hdc_review_rating span").click(function() {
	jQuery("#hdc_review_rating span").removeClass("rated");
	// get the index of the selected start
	var selectedStar = jQuery(this).index();
	// the stars are RTL
	var stars = 5 - selectedStar;
	// now add rated class to all stars before
	// and including the selected start
	jQuery("#hdc_review_rating span").each(function(index) {
		if (index >= selectedStar) {
			jQuery(this).addClass("rated");
		}
	});

	jQuery("#hdc_rating").val(stars);
});

// on product ratings clicked
jQuery("#hdc_product_rating").click(function() {
	// small delay to allow reviews to expand
	setTimeout(function() {
		// todo: add a function to text what the scrollable area is ala HDQuiz
		jQuery("html, body").animate(
			{
				scrollTop: jQuery("#hdc_tab_content").offset().top
			},
			1000
		);
	}, 30);

	// don't double expand
	if (!jQuery('#hdc_tabs ul li[data-hdc-content = "hdc_reviews"]').hasClass("hdc_active_tab")) {
		jQuery('#hdc_tabs ul li[data-hdc-content = "hdc_reviews"]').click();
	}
});

// on submit
jQuery("#hdc_submit_review").click(function() {
	jQuery("#hdc_submit_review .required").removeClass("required");

	var canContinue = true;

	// check all required fields
	jQuery(".required").each(function() {
		var data = jQuery(this).val();
		if (data == "" || data == null) {
			canContinue = false;
			jQuery(this).addClass("hdc_required");
		}
	});

	// validate the email address
	var email = jQuery("#hdc_email").val();
	// man, this is a crazy regex. Taken from https://hexillion.com/samples/
	var reEmail = /^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!\.)){0,61}[a-zA-Z0-9]?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!$)){0,61}[a-zA-Z0-9]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/;

	if (!email.match(reEmail)) {
		canContinue = false;
		jQuery("#hdc_email").addClass("hdc_required");
	}

	if (canContinue) {
		var hdc_name = jQuery("#hdc_name").val();
		var hdc_email = jQuery("#hdc_email").val();
		var hdc_review = jQuery("#hdc_review").val();
		var hdc_rating = jQuery("#hdc_rating").val();
		var hdc_review_nonce = jQuery("#hdc_product_review_" + hdc_id + "_nonce").val();

		var ajaxUrl = hdc_ajax;
		// hide it so that multiple submitions are not made
		jQuery(this).hide();
		jQuery.ajax({
			type: "POST",
			data: {
				action: "hdc_submit_review",
				hdc_id: hdc_id,
				hdc_review_nonce: hdc_review_nonce,
				hdc_rating: hdc_rating,
				hdc_name: hdc_name,
				hdc_email: hdc_email,
				hdc_review: hdc_review
			},
			url: ajaxUrl,
			success: function(data) {
				var data = "<p>Thank you for submitting your review</p>";
				jQuery("#hdc_submit_review_form").html(data);
			},
			error: function() {
				alert("Sorry, there was an error submitting your review");
			},
			complete: function() {
				// complete
			}
		});
	}
});

async function getQuantityTotal(){
	let value = 0;
	let products = document.getElementsByClassName("hdc_cart_quantity");
	for(let i = 0; i < products.length; i++){
		value += parseFloat(products[i].getAttribute("data-price")) * parseFloat(products[i].value);
	}
	return value;
}

/* Coupons */
// when code is added
jQuery("#hdc_cart_actions").on("keyup", "#hdc_cart_coupon_input", async function(e) {
	if (e.keyCode === 13) {
		let code = this.value;	
		this.value = "";
		this.placeholder = "checking code...";

		let ajaxUrl = hdc_ajax;
		let nonce = jQuery("#hdc_cart_nonce").val();

		let cart_amount = await getQuantityTotal();
		let addedCodes = document.getElementsByClassName("hdc_cart_coupon");
		if (addedCodes.length > 0) {
			addedCodes[0].remove();
			jQuery("tr.coupon").remove();
			recalculateTotals();
			checkForCode();
		} else {
			checkForCode();
		}

		function checkForCode() {
			jQuery.ajax({
				type: "POST",
				data: {
					action: "hdc_check_coupon",
					hdc_nonce: nonce,
					coupon: code,
					cart_amount: cart_amount
				},
				url: ajaxUrl,
				success: function(data) {
					console.log(data);
					data = JSON.parse(data);
					if (data.success === "success") {
						let html =
							'<span class = "hdc_cart_coupon" title = "remove this coupon" data-code = "' +
							data.code +
							'" data-symbol = "' +
							data.coupon.symbol +
							'" data-type = "' +
							data.coupon.type +
							'" data-amount = "' +
							data.coupon.amount +
							'">code - ' +
							data.code +
							"</span>";
						document.getElementById("hdc_added_cart_coupons").insertAdjacentHTML("beforeend", html);
						hdc_apply_coupon();
					} else {
						console.log(data);
					}
				},
				error: function() {
					console.log("Error checking for code");
				},
				complete: function() {
					document.getElementById("hdc_cart_coupon_input").placeholder = "enter coupon code here";
				}
			});
		}
	}
});

function hdc_apply_coupon() {
	let addedCodes = document.getElementsByClassName("hdc_cart_coupon");
	if (addedCodes.length > 0) {
		let code = addedCodes[0];
		code = {
			code: code.getAttribute("data-code"),
			type: code.getAttribute("data-type"),
			symbol: code.getAttribute("data-symbol"),
			amount: parseFloat(code.getAttribute("data-amount"))
		};
		let data =
			'<tr class="coupon"><td>Coupon: ' +
			code.code +
			"</td><td>discount: " +
			code.symbol +
			"" +
			code.amount +
			"</td><td></td><td></td></tr>";
		jQuery("#hdc_cart_table tbody").append(data);
		recalculateTotals();
	}
}

// remove selected coupon from cart
jQuery("#hdc_cart_actions").on("click", ".hdc_cart_coupon", function(e) {
	this.remove();
	jQuery("tr.coupon").remove();
	recalculateTotals();
});

async function initSingleProduct() {
	const price = {
		price: JSON.parse(productPrice)[0],
		sale: JSON.parse(productPrice)[1]
	};
	const permutations = await getPermutations();
	const variations = document.getElementsByClassName("hdc_variation");
	const variation_types = document.getElementsByClassName("hdc_variation_type");
	const out_of_stock = document.getElementsByClassName("hdc_variation_out_of_stock");
	if (permutations != null && permutations != false && variation_types.length <= 1) {
		checkForOutOfStockVariationsFirst();
	}

	if (permutations != null && permutations != false) {
		checkForDefault();
	}

	let product_options = document.getElementsByClassName("hdc_variation");
	for (let i = 0; i < product_options.length; i++) {
		product_options[i].addEventListener("click", variationSelect);
	}

	function variationSelect() {
		if (this.classList.contains("hdc_variation_selected")) {
			return;
		}
		let type = this.parentNode;
		let type_options = type.getElementsByClassName("hdc_variation_selected");

		if (variation_types.length > 1) {
			if (type_options.length > 0) {
				type_options[0].classList.remove("hdc_variation_selected");
			}
			this.classList.add("hdc_variation_selected");

			while (out_of_stock.length > 0) {
				out_of_stock[0].classList.remove("hdc_variation_out_of_stock");
			}
			checkForOutOfStockVariations(this);
			checkIfCanAddToCart();
		} else {
			if (!this.classList.contains("hdc_variation_out_of_stock")) {
				if (type_options.length > 0) {
					type_options[0].classList.remove("hdc_variation_selected");
				}
				this.classList.add("hdc_variation_selected");
				checkIfCanAddToCart();
			}
		}
	}

	function checkIfCanAddToCart() {
		let canAdd = true;
		for (let i = 0; i < variation_types.length; i++) {
			let variations = variation_types[i].getElementsByClassName("hdc_variation_selected");
			if (variations.length == 0) {
				canAdd = false;
				break;
			}
		}

		if (canAdd) {
			document.getElementById("add_to_cart").classList.add("hdc_button_active");
			let id = "";
			let selected = document.getElementsByClassName("hdc_variation_selected");
			for (let i = 0; i < selected.length; i++) {
				let type = selected[i].getAttribute("data-variation-type");
				let name = selected[i].getAttribute("data-variation-name");
				if (i < selected.length - 1) {
					id += type + "*" + name + "|";
				} else {
					id += type + "*" + name;
				}
			}

			for (let i = 0; i < permutations.length; i++) {
				if (permutations[i].id == id) {
					setPermutationPrice(permutations[i]);
					break;
				}
			}
		} else {
			document.getElementById("add_to_cart").classList.remove("hdc_button_active");
		}
	}

	function setPermutationPrice(permutation) {
		let p = price.price;
		let s = price.sale;
	
		if (permutation.options.price != "") {
			p = permutation.options.price;
			console.log(permutation.options.price)
		}

		if (permutation.options.sale != "") {
			s = permutation.options.sale;
		} else {
			s = "";
		}

		let symbol = hdc_currency_symbol[1];
		let button_price = p;
		if (s != "") {
			data = `<s>${symbol}${p}</s> ${symbol}${s}`;
			button_price = s;
		} else {
			data = `${symbol}${p}`;
		}
		document.getElementById("product_price").innerHTML = data;
		document.getElementById("add_to_cart").innerHTML = `Add to cart: ${symbol}${button_price}`;
	}

	async function getPermutations() {
		let permutations = JSON.parse(productData) || null;
		return permutations;
	}

	function checkForOutOfStockVariations(el) {
		let active_variations = document.getElementsByClassName("hdc_variation hdc_variation_selected");
		let type = el.getAttribute("data-variation-type");
		let name = el.getAttribute("data-variation-name");

		let no_stock = [];
		for (let i = 0; i < permutations.length; i++) {
			if (permutations[i].options.stock === 0) {
				let data = permutations[i].id;
				data = data.split("|");
				for (let x = 0; x < data.length; x++) {
					data[x] = data[x].split("*");
				}
				no_stock.push({ data: data, id: permutations[i].id });
			}
		}

		for (let i = 0; i < no_stock.length; i++) {
			for (let x = 0; x < no_stock[i].data.length; x++) {
				if (no_stock[i].data[x][0] == type && no_stock[i].data[x][1] == name) {
					// set all other than selected as out of stock
					setPermutationsOutOfStock(no_stock[i], el);
				}
			}
		}
	}

	function checkForOutOfStockVariationsFirst() {
		for (let i = 0; i < permutations.length; i++) {
			if (permutations[i].options.stock === 0) {
				setPermutationsOutOfStock(permutations[i]);
			}
		}
	}

	function checkForDefault() {
		for (let i = 0; i < permutations.length; i++) {
			if (permutations[i].options.default == "yes") {
				selectPermutation(permutations[i]);
				break;
			}
		}

		function selectPermutation(permutation) {
			let id = permutation.id;
			id = id.split("|");
			permutation = [];
			for (let i = 0; i < id.length; i++) {
				permutation.push(id[i].split("*"));
			}

			for (let i = 0; i < permutation.length; i++) {
				for (let x = 0; x < variations.length; x++) {
					let type = variations[x].getAttribute("data-variation-type");
					let name = variations[x].getAttribute("data-variation-name");
					if (type == permutation[i][0] && name == permutation[i][1]) {
						setTimeout(function() {
							variations[x].click();
						}, 100); // not sure why we need a delay
					}
				}
			}
		}
	}

	function setPermutationsOutOfStock(permutation, el = null) {
		let id = permutation.id;
		id = id.split("|");
		permutation = [];
		for (let i = 0; i < id.length; i++) {
			permutation.push(id[i].split("*"));
		}

		for (let i = 0; i < permutation.length; i++) {
			for (let x = 0; x < variations.length; x++) {
				let type = variations[x].getAttribute("data-variation-type");
				let name = variations[x].getAttribute("data-variation-name");
				if (type == permutation[i][0] && name == permutation[i][1]) {
					if (variations[x] != el) {
						variations[x].classList.add("hdc_variation_out_of_stock");
						variations[x].classList.remove("hdc_variation_selected");
					}
				}
			}
		}
	}

	// Add product to cart
	// ______________________________________________

	jQuery("#add_to_cart").click(function() {
		if (jQuery(this).hasClass("hdc_button_active")) {
			var variations = "";
			jQuery(".hdc_variation_selected").each(function() {
				var type = jQuery(this).attr("data-variation-type");
				var name = jQuery(this).attr("data-variation-name");
				variations = variations + type + "*" + name + "|";
			});
			if (variations != "") {
				variations = variations.slice(0, -1);
				variations = createSafeId(variations, false);
				jQuery("#hdc_variation_name").val(variations);
				console.log(variations);
				console.log(permutations);
			}
			jQuery("#hdc_product_add_to_cart").submit();
		}
	});
}


function setProductCategoryListeners(){
	// style changes	
	const icons = document.getElementsByClassName("hdc_category_style_icon");
	for(let i = 0; i < icons.length; i++){
		icons[i].addEventListener("click", updateCategoryStyle);
	}
	
	function updateCategoryStyle(){
		let active = document.getElementsByClassName("hdc_category_style_active")[0];
		if(active != null){
			active.classList.remove("hdc_category_style_active");
		}
		
		this.classList.add("hdc_category_style_active");	
		
		let el = document.getElementById("hdc_products");
		let style = this.getAttribute("data-id");
		let styles = ["hdc_product_columns1","hdc_product_columns2","hdc_product_columns3","hdc_product_columns4"];
		for(let i = 0; i < styles.length; i++){
			el.classList.remove(styles[i]);
		}
		el.classList.add(style)
	}
	
	// if order changes
	const order = document.getElementById("hdc_category_order_input");
	if(order != null){
		order.addEventListener("change", submitFormCategoryOrder)
	}
	
	function submitFormCategoryOrder(){
		const order = document.getElementById("hdc_category_order_input_form");
		if(order != null){
			order.submit();
		}
	}
}
setProductCategoryListeners();