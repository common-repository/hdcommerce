/*
	HDC main admin script
*/

jQuery(window).load(function() {
	console.log("HD Commerce initiated");

	// On window load usability functions
	// ______________________________________________

	function hdc_window_load() {
		// show the default tab on load
		var activeTab = jQuery("#hdc_tabs .hdc_active_tab").attr("data-hdc-content");
		jQuery("#" + activeTab).addClass("hdc_tab_active");
		jQuery(".hdc_tab_active").slideDown(500);

		// small usability enhancements.
		// If it is a new product, focus add product name
		if (jQuery("#product_name").val() == "" || jQuery("#product_name").val() == null) {
			setTimeout(function() {
				jQuery("#product_name").focus();
			}, 1000);
		}
		// if this is not a new product, add the view product button
		if (!jQuery("body").hasClass("post-new-php")) {
			jQuery("#hdc_mainProdTitle").addClass("editing_post");
			jQuery("#hdc_view_product").addClass("editing_post");
		}

		// make the model draggable
		jQuery("#hdc_message_model_inner").draggable();
	}
	hdc_window_load();

	// Convert select boxes to custom selectable divs
	// ______________________________________________

	function hdc_create_select_boxes() {
		jQuery("#hdc_wrapper .hdc_select").each(function() {
			var jQuerythis = jQuery(this),
				numberOfOptions = jQuery(this).children("option").length;

			jQuerythis.addClass("select-hidden");
			jQuerythis.wrap('<div class="select"></div>');
			jQuerythis.after('<div class="select-styled"></div>');

			var jQuerystyledSelect = jQuerythis.next("div.select-styled");
			jQuerystyledSelect.text(
				jQuerythis
					.children("option")
					.eq(0)
					.text()
			);

			var jQuerylist = jQuery("<ul />", {
				class: "select-options"
			}).insertAfter(jQuerystyledSelect);

			for (var i = 0; i < numberOfOptions; i++) {
				jQuery("<li />", {
					text: jQuerythis
						.children("option")
						.eq(i)
						.text(),
					rel: jQuerythis
						.children("option")
						.eq(i)
						.val()
				}).appendTo(jQuerylist);
			}

			var jQuerylistItems = jQuerylist.children("li");

			jQuery(this)
				.next(".select-styled")
				.html(
					jQuery(this)
						.find(":selected")
						.text()
				);

			jQuerystyledSelect.click(function(e) {
				e.stopPropagation();
				jQuery("div.select-styled.active")
					.not(this)
					.each(function() {
						jQuery(this)
							.removeClass("active")
							.next("ul.select-options")
							.hide();
					});
				jQuery(this)
					.toggleClass("active")
					.next("ul.select-options")
					.toggle();
			});

			jQuerylistItems.click(function(e) {
				e.stopPropagation();
				jQuerystyledSelect.text(jQuery(this).text()).removeClass("active");
				jQuerythis.val(jQuery(this).attr("rel"));
				jQuerylist.hide();
			});

			jQuery(document).click(function() {
				jQuerystyledSelect.removeClass("active");
				jQuerylist.hide();
			});
		});
	}
	hdc_create_select_boxes();
});

// Disable product page default submit
// this is just a safety
// ______________________________________________

jQuery("#post").on("submit", function(e) {
	e.preventDefault();
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

// Custom input placeholders
// ______________________________________________

jQuery("#hdc_meta_products, #hdc_settings").on("focusout", ".hdc_input", function() {
	if (jQuery(this).val() != "") {
		jQuery(this).addClass("has-content");
	} else {
		jQuery(this).removeClass("has-content");
	}
});

// When a new category is entered
// ______________________________________________

jQuery("#hdc_product_wrapper").on("keyup", "#addNewCategory", function(e) {
	e.preventDefault();
	var code = e.which;
	if (code == 13) {
		hdc_enter_notification = false;
		jQuery(".hdc_input_notification").fadeOut();
		jQuery("#addNewCategory").fadeOut();
		var data = "";
		var parentCategory = jQuery("#addNewCategoryChild")
			.find(":selected")
			.val();
		var categoryName = jQuery(this).val();
		if (parentCategory == "hide") {
			parentCategory = 0;
		}
		var hdc_new_cat = categoryName + "|" + parentCategory;
		jQuery.ajax({
			type: "POST",
			data: {
				action: "hdc_add_new_category",
				hdc_product_number: productId,
				hdc_nonce: jQuery("#hdc_meta_prod_category_nonce").val(),
				hdc_new_cat: hdc_new_cat
			},
			url: ajaxurl,
			success: function(data) {
				if (data == "permission denied") {
					alert(data);
				} else {
					var termId = data;
					// add the new cat to the page
					if (parentCategory == 0) {
						// if no parent category was selected
						data =
							'<div class="hdc_category hdc_category_parent" data-cat-id="' +
							termId +
							'"><label class="hdc_full_label" for="term_' +
							termId +
							'"><div class="hdc-options-check"><input type="checkbox" id="term_' +
							termId +
							'" value="yes" name="term_' +
							termId +
							'"><label for="term_' +
							termId +
							'"></label></div>' +
							categoryName +
							"</label></div>";
						jQuery("#hdc_category_list").append(data);
					} else {
						// add a sub category
						data =
							'<div class="hdc_category" data-cat-id="' +
							termId +
							'"><label class="hdc_full_label" for="term_' +
							termId +
							'"><div class="hdc-options-check"><input type="checkbox" id="term_' +
							termId +
							'" value="yes" name="term_' +
							termId +
							'"><label for="term_' +
							termId +
							'"></label></div>' +
							categoryName +
							"</label></div>";
						// find the category with the parentCat
						jQuery("#hdc_category_list")
							.find('.hdc_category[data-cat-id="' + parentCategory + '"]')
							.append(data);
					}
				}
			},
			fail: function() {
				alert("There as an error");
			},
			complete: function() {
				jQuery("#addNewCategory").fadeIn();
				jQuery("#addNewCategory").val("");
				jQuery("#addNewCategory").removeClass("hasContent");
			}
		});
	} else {
		var content = jQuery(this).val();
		if (content != "" && content != null) {
			hdc_press_enter_notificiation("#addNewCategory");
		} else {
			jQuery(".hdc_input_notification").fadeOut();
			hdc_enter_notification = false;
		}
	}
});

// Create a list of selected product categoires
// ______________________________________________

function createCategoryList() {
	var categoryList = "";
	jQuery(".hdc_category .hdc-options-check input").each(function(index) {
		var categoryId = jQuery(this).attr("id");
		categoryId = categoryId.replace(/term_/g, "");
		if (jQuery(this).is(":checked")) {
			categoryList = categoryList + categoryId + ",";
		}
	});
	categoryList = categoryList.slice(0, -1);
	return categoryList;
}

// Popup alert and message model
// ______________________________________________

function createMessageModel(hdFadeIn, hdFadeOut, hdDelay, hdTitle, hdDescription, hdButton, hdButton2, hdExtra) {
	// reset
	jQuery("#hdc_message_model #hdc_message_model_title h4").html("");
	jQuery("#hdc_message_model #hdc_message_model_content").html("");
	jQuery("#hdc_message_model #hdc_message_model_footer").html("");

	jQuery("#hdc_message_model #hdc_message_model_title h4").html(hdTitle);

	if (hdDescription != null) {
		jQuery("#hdc_message_model #hdc_message_model_content").html(hdDescription);
	}

	if (hdButton != null) {
		jQuery("#hdc_message_model_footer").append(hdButton);
		jQuery("#hdc_message_model_footer").show();
	} else {
		jQuery("#hdc_message_model_footer").hide();
	}

	if (hdButton2 != null) {
		jQuery("#hdc_message_model_footer").append(hdButton2);
		jQuery("#hdc_message_model_footer").show();
	}

	if (hdFadeIn != null) {
		jQuery("#hdc_message_model").fadeIn(hdFadeIn);
	}

	if (hdDelay != null && hdFadeOut != null) {
		jQuery("#hdc_message_model")
			.delay(hdDelay)
			.fadeOut(hdFadeOut);
	}

	if (hdDelay == null && hdFadeOut != null) {
		jQuery("#hdc_message_model").fadeOut(hdFadeOut);
	}

	if (hdExtra != null) {
		// run the extra
		hdExtra();
	}
}

jQuery("#hdc_message_model_close").click(function() {
	jQuery("#hdc_message_model").fadeOut(500);
});

// On settings page submit
// ______________________________________________

jQuery("#hdc_settings_form").on("submit", function(e) {
	// get countries
	var countries = [];
	jQuery("#hdc_select_countries .hdc_selected_country").each(function(index) {
		var country = jQuery(this).html();
		var countryCode = jQuery(this).attr("data-country-code");
		var countryStates = jQuery(this).attr("data-states");
		countries.push({
			country: country,
			code: countryCode,
			states: countryStates
		});
	});
	countries = JSON.stringify(countries);
	jQuery("#hdc_store_selling_countries").val(countries);

	// get tax chart
	var taxChart = [];
	jQuery("#hdc_tax_table tbody tr").each(function() {
		var country = jQuery(this)
			.find("td")
			.eq(0)
			.html();
		var state = jQuery(this)
			.find("td")
			.eq(1)
			.html();
		var taxName = jQuery(this)
			.find("td")
			.eq(2);
		taxName = jQuery(taxName)
			.find(".hdc_tax_name")
			.html();
		var taxValue = jQuery(this)
			.find("td")
			.eq(3);
		taxValue = jQuery(taxValue)
			.find(".hdc_tax_value")
			.html();
		var taxCompound = jQuery(this)
			.find("td")
			.eq(4);
		taxCompound = jQuery(taxCompound).find("input");
		if (jQuery(taxCompound).is(":checked")) {
			taxCompound = "yes";
		} else {
			taxCompound = "no";
		}

		taxChart.push({
			country: country,
			state: state,
			taxName: taxName,
			taxValue: taxValue,
			taxCompound: taxCompound
		});
	});
	taxChart = JSON.stringify(taxChart);
	jQuery("#hdc_tax_chart").val(taxChart);

	// get shipping method
	var shippingMethod = "";
	jQuery("#hdc_settings_shipping .hdc_setting_image_select").each(function() {
		if (jQuery(this).hasClass("hdc_selected")) {
			shippingMethod = jQuery(this).attr("data-select");
			shippingMethod = "hdc_shipping_" + shippingMethod;
		}
	});

	if (shippingMethod != "") {
		hdc_shipping_grab_all_methods();
	}
});

// On settings select countries model
// ______________________________________________

// show model
jQuery("#hdc_select_countries").on("click", "#hdc_select_countries_button", function() {
	jQuery(".hdc_selected_country").each(function() {
		var countryCode = jQuery(this).attr("data-country-code");
		jQuery(".hdc_select_country").each(function() {
			var countryCode2 = jQuery(this).attr("data-country-code");
			if (countryCode2 == countryCode) {
				jQuery(this).addClass("hdc_country_disabled");
			}
		});
	});
	jQuery("#hdc_select_countries_model_wrap").fadeIn();
});

// close the module
jQuery("#hdc_select_countries").on("click", "#hdc_select_countries_model_close", function() {
	jQuery("#hdc_select_countries_model_wrap").fadeOut();
});

// on country select
jQuery("#hdc_select_countries_model").on("click", ".hdc_select_country", function() {
	if (!jQuery(this).hasClass("hdc_country_disabled")) {
		var country = jQuery(this).html();
		var countryCode = jQuery(this).attr("data-country-code");
		var countryStates = jQuery(this).attr("data-states");
		var data =
			'<div data-country-code = "' +
			countryCode +
			'" data-states = "' +
			countryStates +
			'" class="hdc_selected_country">' +
			country +
			"</div>";
		jQuery("#hdc_select_countries").append(data);
		jQuery(this).addClass("hdc_country_disabled");
	}
});

// on country remove
jQuery("#hdc_select_countries").on("click", ".hdc_selected_country", function() {
	var countryCode = jQuery(this).attr("data-country-code");
	jQuery(".hdc_country_disabled").each(function() {
		var countryCode2 = jQuery(this).attr("data-country-code");
		if (countryCode2 == countryCode) {
			jQuery(this).removeClass("hdc_country_disabled");
		}
	});

	jQuery(this).remove();
});

// On settings gateway select
// ______________________________________________

jQuery("#hdc_settings_payment_gateway").on("click", ".hdc_setting_image_select", function() {
	var select = jQuery(this).attr("data-select");
	var selectFunction = jQuery(this).attr("data-function");
	jQuery("#hdc_payment_gateway").val(select + "|" + selectFunction);
	jQuery("#hdc_settings_payment_gateway .hdc_selected").removeClass("hdc_selected");
	jQuery(this).addClass("hdc_selected");
	jQuery("#hdc_settings_payment_gateway .hdc_gateway").hide();
	jQuery("#hdc_" + select).fadeIn(1000);
	console.log(jQuery("#hdc_payment_gateway").val());
});

// On settings shipping select
// ______________________________________________

jQuery("#hdc_settings_shipping").on("click", ".hdc_setting_image_select", function() {
	var select = jQuery(this).attr("data-select");
	var selectFunction = jQuery(this).attr("data-function");
	jQuery("#hdc_shipping").val(select + "|" + selectFunction);
	jQuery("#hdc_settings_shipping .hdc_selected").removeClass("hdc_selected");
	jQuery(this).addClass("hdc_selected");
	jQuery("#hdc_settings_shipping .hdc_shipping").hide();
	jQuery("#hdc_" + select).fadeIn(1000);
});

// Create a safeId for
// variations and products
// ______________________________________________

function createSafeId(val, slice) {
	val = encodeURIComponent(val);
	val = val.replace(/%20/g, "-");
	val = val.toLowerCase();
	if (slice) {
		val = val.slice(0, -1);
	}
	return val;
}

// Settings Tax Tab, Country Select
// ______________________________________________

jQuery("#hdc_tax_country").change(function() {
	var states = jQuery("#hdc_tax_country")
		.find(":selected")
		.val();
	if (states != "" && states != null) {
		states = jQuery("#hdc_tax_country")
			.find(":selected")
			.attr("data-states");

		jQuery("#hdc_tax_state").html('<option value="">Select State/Province/Territory</option>');
		jQuery("#hdc_tax_state").append(
			'<option value = "* all States/Provinces ">All States/Provinces/Territories</option>'
		);

		if (states != "" && states != null) {
			states = states.split("|");
			jQuery(states).each(function() {
				var data = '<option value = "' + this + '">' + this + "</option>";
				jQuery("#hdc_tax_state").append(data);
			});
		}
	}
});

// Settings Tax Tab, State Select
// ______________________________________________

jQuery("#hdc_tax_state").change(function() {
	var state = jQuery("#hdc_tax_state")
		.find(":selected")
		.val();
	var country = jQuery("#hdc_tax_country")
		.find(":selected")
		.val();

	jQuery("#hdc_add_tax").val("");
	var taxId = "hdc_" + country + "-" + state;
	taxId = createSafeId(taxId, false);
	var data =
		'<tr class = "grab">\
	<td>' +
		country +
		"</td>\
	<td>" +
		state +
		'</td>\
	<td><div class = "hdc_tax_name" contenteditable = "true">enter tax name</div></td>\
	<td><span class = "hdc_tax_value" contenteditable = "true">0.00</span>%</td>\
	<td><div class="hdc-options-check"><input type="checkbox" id="' +
		taxId +
		'" value="yes" name="' +
		taxId +
		'"><label for="' +
		taxId +
		'"></label></div></td>\
	<td><div class="hdc_remove_tax">X</div></td>\
	</tr>';
	jQuery("#hdc_tax_table tbody").append(data);
});

// Edit tax name and rate
// ______________________________________________

jQuery("#hdc_tax_table").on("click", ".hdc_tax_name, .hdc_tax_value", function(e) {
	var val = jQuery(this).html();
	if (val == "enter tax name" || val == "0.00") {
		jQuery(this).html("");
	}
});

// Remove Tax
// ______________________________________________

jQuery("#hdc_tax_table").on("click", ".hdc_remove_tax", function(e) {
	jQuery(this)
		.closest("tr")
		.fadeOut("slow", function() {
			jQuery(this).remove();
		});
});

// Drag and drop tr in tax table
// ______________________________________________

jQuery("#hdc_tax_table").on("mousedown", ".grab", function(e) {
	if (e.which === 1) {
		var tr = jQuery(e.target).closest("tr"),
			si = tr.index(),
			sy = e.pageY,
			b = jQuery(document.body),
			drag;

		function move(e) {
			if (!drag && Math.abs(e.pageY - sy) < 10) return;
			tr.addClass("grabbed");
			drag = true;
			tr.siblings().each(function() {
				var s = jQuery(this),
					i = s.index(),
					y = s.offset().top;
				if (e.pageY >= y && e.pageY < y + s.outerHeight()) {
					if (i < tr.index()) s.insertAfter(tr);
					else s.insertBefore(tr);
					return false;
				}
			});
		}

		function up(e) {
			if (drag && si != tr.index()) {
				drag = false;
			}
			jQuery(document)
				.unbind("mousemove", move)
				.unbind("mouseup", up);
			b.removeClass("grabCursor").css("userSelect", "none");
			tr.removeClass("grabbed");
		}
		jQuery(document)
			.mousemove(move)
			.mouseup(up);
	}
});

// Settings Shipping Methods
// ______________________________________________

function hdc_shipping_grab_all_methods() {
	var list = "";
	var selectedShippingMethod = jQuery("#hdc_shipping").val();
	selectedShippingMethod = selectedShippingMethod.split("|");
	selectedShippingMethod = selectedShippingMethod[0];
	jQuery("#hdc_" + selectedShippingMethod + " .hdc-options-check").each(function() {
		var method = jQuery(this).attr("data-method");
		var check = jQuery(this).children()[0].checked ? "1" : "0";
		if (check == 1) {
			list = list + method + ",";
		}
	});
	list = list.slice(0, -1);
	jQuery("#hdc_shipping_" + selectedShippingMethod + "_methods").val(list);
}

// Accordion
// ______________________________________________

jQuery(".hdc_accordion h3").click(function() {
	jQuery(this)
		.next("div")
		.toggle(600);
});

// Disable form submition if ENTER
// is pressed on a model form
// ______________________________________________

jQuery("#hdc_message_model_content").on("keydown", ".hdc_input_simple", function(e) {
	// only allow numbers
	var key = e.charCode || e.keyCode || 0;
	if (e.keyCode == 13) {
		e.preventDefault();
		return false;
	}
});

// Export Orders
// ______________________________________________

function hdc_order_export() {
	function hdc_toggle_orders_export_date_ranges() {
		let el = document.getElementById("hdc_export_orders_date_range");
		if (el.style.display === "grid") {
			el.style.display = "none";
		} else {
			el.style.display = "grid";
		}
	}
	document
		.getElementById("hdc_export_choose_date_range")
		.addEventListener("click", hdc_toggle_orders_export_date_ranges);

	function hdc_export_orders_start() {
		let el = document.getElementById("hdc_export_orders_results");
		el.innerHTML =
			"<p>Generating CSV... please wait, this may take a while depending on server stress and amount of orders to export.</p>";
	}

	function hdc_export_orders(el) {
		let export_type = el.getAttribute("id");
		hdc_export_orders_start();
		let date_range = {
			start: el.getAttribute("data-start"),
			end: el.getAttribute("data-end")
		};

		let nonce = jQuery("#hdc_export_orders").val();

		jQuery.ajax({
			type: "POST",
			data: {
				action: "hdc_export_orders",
				export_type: export_type,
				date_range: date_range,
				nonce: nonce
			},
			url: ajaxurl,
			success: function(data) {
				let el = document.getElementById("hdc_export_orders_results");
				el.innerHTML = data;
			},
			error: function() {
				let el = document.getElementById("hdc_export_orders_results");
				el.innerHTML = "<p>ERROR: there was an error creating the export file.</p>";
			}
		});
	}

	document.getElementById("hdc_export_all_orders").addEventListener("click", function(e) {
		hdc_export_orders(this);
	});

	document.getElementById("hdc_export_date_range_submit").addEventListener("click", function(e) {
		// check that date ranges have been selected first
		function validateDates(el) {
			let start = document.getElementById("hdc_export_start_date").value;
			let end = document.getElementById("hdc_export_end_date").value;
			if (start == "" || end == "") {
				let el = document.getElementById("hdc_export_orders_results");
				el.innerHTML = "<p>Please select a valid date range before exporting</p>";
				return;
			}
			el.setAttribute("data-start", start);
			el.setAttribute("data-end", end);
			hdc_export_orders(el);
		}
		validateDates(this);
	});
}
if (jQuery("body").hasClass("toplevel_page_hdc_export_orders")) {
	hdc_order_export();
}
