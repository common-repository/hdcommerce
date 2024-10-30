// TODO:
// 		validation variation fields
//      allow hooks to change default variation fields
// 		create check and radio elements


console.log("Init: HDC Product Page");
const HDC = {
	EL: {
		page: {
			header: {
				title: document.getElementById("heading_title"),
				actions: {
					wrapper: document.getElementById("header_actions"),
					save: document.getElementById("save")
				}
			},
			editors: document.getElementsByClassName("editor"),
			slug: document.getElementById("product_slug")
		},
		tabs: {
			nav: document.getElementsByClassName("tab_nav_item"),
			content: document.getElementsByClassName("tab_content"),
			active: {
				nav: document.getElementsByClassName("tab_nav_item_active"),
				content: document.getElementsByClassName("tab_content_active")
			}
		},
		product: {
			name: document.getElementById("product_name"),
			variation: {
				addVariation: document.getElementById("add_variation"),
				variations: document.getElementById("product_variations"),
				variationItems: document.getElementsByClassName("variation_item"),
				permutations: document.getElementById("product_permutations"),
				addOption: document.getElementsByClassName("variation_option"),
				options: document.getElementsByClassName("option_item"),
				products: document.getElementsByClassName("product_item"),
				sidebar: document.getElementById("product_variation_sidebar"),
				variationFields: {
					fields:
						'[[{"name":"price","value":"","label":"Enter a custom price","placeholder":"0.00","type":"currency","default":""},{"name":"sale","value":"","label":"Enter a custom sale price","placeholder":"0.00","type":"currency","default":""}],[{"name":"sku","value":"","label":"Enter a custom SKU","placeholder":"","type":"text","default":""},{"name":"stock","value":"","label":"Enter stock amount","placeholder":"0","type":"integer","default":""}],[{"type":"seperator"}],[{"name":"weight","value":"","label":"Enter product weight","placeholder":"0","type":"float","default":""},{"name":"width","value":"","label":"Enter product width","placeholder":"0","type":"float","default":""}],[{"name":"height","value":"","label":"Enter product height","placeholder":"0","type":"float","default":""},{"name":"length","value":"","label":"Enter product length","placeholder":"0","type":"float","default":""}],[{"name":"shipping_class","value":"","label":"Custom shipping class","type":"select","options":[{"value":"A","label":"A"},{"value":"B","label":"B"},{"value":"C","label":"C"},{"value":"D","label":"D"},{"value":"E","label":"E"},{"value":"F","label":"FREE"}]},{"name":"default","value":"","label":"Set as default variation","type":"radio","default":false,"options":[{"value":"yes","label":"Yes"}]}]]',
					default: async function() {
						let fields = JSON.parse(this.fields);
						let d = {};
						for (let i = 0; i < fields.length; i++) {
							for (let x = 0; x < fields[i].length; x++) {
								if (fields[i][x].name) {
									if (fields[i][x].name == "price") {
										d[fields[i][x].name] = await HDC.getValueByType.currency(
											document.getElementById("product_price"),
											{}
										);
									} else if (fields[i][x].name == "sale") {
										d[fields[i][x].name] = await HDC.getValueByType.currency(
											document.getElementById("product_sale_price"),
											{}
										);
									} else if (fields[i][x].name == "weight") {
										d[fields[i][x].name] = await HDC.getValueByType.float(
											document.getElementById("product_weight"),
											{}
										);
									} else if (fields[i][x].name == "width") {
										d[fields[i][x].name] = await HDC.getValueByType.float(
											document.getElementById("product_width"),
											{}
										);
									} else if (fields[i][x].name == "height") {
										d[fields[i][x].name] = await HDC.getValueByType.float(
											document.getElementById("product_height"),
											{}
										);
									} else if (fields[i][x].name == "length") {
										d[fields[i][x].name] = await HDC.getValueByType.float(
											document.getElementById("product_length"),
											{}
										);
									} else if (fields[i][x].name == "stock") {
										d[fields[i][x].name] = await HDC.getValueByType.integer(
											document.getElementById("product_stock"),
											{}
										);
									} else if (fields[i][x].name == "shipping_class") {
										d[fields[i][x].name] = await HDC.getValueByType.select(
											document.getElementById("shipping_class"),
											{}
										);
									} else {
										d[fields[i][x].name] = "";
									}
								}
							}
						}
						return JSON.stringify(d);
					}
				}
			},
			categories: document.getElementsByClassName("hdc_category_input"),
			inputEnter: document.getElementsByClassName("input_enter"),
			inputs: document.getElementsByClassName("hderp_input")
		}
	},
	VARS: {
		editors: [],
		mediaFrame: {
			frame: null,
			title: null,
			button: null,
			multiple: false
		},
		showingEnterNotifiction: [],
		page: async function() {
			let data = null;
			if (adminpage == "post-new-php") {
				data = 0;
			} else if (adminpage == "post-php") {
				data = document.getElementById("post_ID").value;
			}
			return data;
		}
	},
	init: function() {
		HDC.createEditors(); // initialize various content editors
		setTabEventListeners(); // allow tab switching
		setProductNameListener(); // pair product name and page title
		HDC.setEnterInputListeners(); // notifiy user to press ENTER to add data
		setHeaderActionsListeners(); // save button
		HDC.variations.setProductAddVariationListener(); // for permutations
		HDC.setImageListeners();
		HDC.setProductListeners();
		HDC.setSlugListener();

		// allow tab switching
		function setTabEventListeners() {
			for (let i = 0; i < HDC.EL.tabs.nav.length; i++) {
				HDC.EL.tabs.nav[i].addEventListener("click", loadTabContent);
			}

			function loadTabContent() {
				HDC.EL.tabs.active.nav[0].classList.remove("tab_nav_item_active");
				this.classList.add("tab_nav_item_active");

				let content = "tab_" + this.getAttribute("data-id");
				HDC.EL.tabs.active.content[0].classList.remove("tab_content_active");
				document.getElementById(content).classList.add("tab_content_active");
			}
		}

		// live update page title with product title
		function setProductNameListener() {
			HDC.EL.product.name.addEventListener("keyup", updatePageTitle);
			HDC.EL.product.name.addEventListener("keydown", updatePageTitle);

			async function updatePageTitle(e) {
				let v = this.value;
				let prefix = "";
				let p = await HDC.VARS.page();
				if (p == 0) {
					prefix = "Adding";
				} else {
					prefix = "Editing";
				}
				let t = prefix + ' <span id = "page_title_span">' + v + "</span>";
				HDC.EL.page.header.title.innerHTML = t;
			}
		}

		function setHeaderActionsListeners() {
			HDC.EL.page.header.actions.save.addEventListener("click", HDC.validate.validateProduct);
		}
	},
	setSlugListener: function() {
		if (HDC.EL.page.slug != null) {
			HDC.EL.page.slug.addEventListener("click", editSlug);
		}

		function editSlug() {
			let slug = this.getAttribute("data-slug");
			let html = `<div id = "slug_edit_wrapper">
				<input id = "slug_edit" type = "text" placeholder = "enter new slug" class = "input input_enter" value = "${slug}"/>
			</div>`;
			this.insertAdjacentHTML("afterend", html);
			document.getElementById("slug_edit").focus();
			document.getElementById("slug_edit").addEventListener("keyup", updateSlug);

			function updateSlug(e) {
				if (e.keyCode === 13) {
					let slug = this.value;
					slug = HDC.getCleanName(slug);
					e.target.value = slug;
					HDC.EL.page.slug.setAttribute("data-slug", slug);
					let permalink = HDC.EL.page.slug.getAttribute("data-permalink") + slug;
					console.log(permalink);

					HDC.EL.page.slug.innerHTML = permalink;
					document.getElementById("slug_edit_wrapper").remove();
				}
			}
		}
	},
	setEnterInputListeners: function() {
		for (let i = 0; i < HDC.EL.product.inputEnter.length; i++) {
			HDC.VARS.showingEnterNotifiction.push(false);
			HDC.EL.product.inputEnter[i].addEventListener("keyup", function(e) {
				setEnterNotification(i, e, this);
			});
		}

		function setEnterNotification(i, e, el) {
			if (e.keyCode === 13) {
				let next = el.nextSibling;
				if (next.nodeName == "P" && next.classList.contains("enter_notification")) {
					next.remove();
				}
				HDC.VARS.showingEnterNotifiction[i] = false;
			} else {
				if (el.value.length > 0 && HDC.VARS.showingEnterNotifiction[i] == false) {
					let data = `<p class = "enter_notification">press enter to add</p>`;
					el.insertAdjacentHTML("afterend", data);
					HDC.VARS.showingEnterNotifiction[i] = true;
					setTimeout(function() {
						let next = el.nextSibling;
						if (next.nodeName == "P" && next.classList.contains("enter_notification")) {
							next.classList.add("enter_notification_visible");
						}
					}, 1000);
				} else if (el.value.length == 0 && HDC.VARS.showingEnterNotifiction[i] == true) {
					HDC.VARS.showingEnterNotifiction[i] = false;
					let next = el.nextSibling;
					if (next.nodeName == "P" && next.classList.contains("enter_notification")) {
						next.remove();
					}
				}
			}
		}
	},
	setProductListeners: function() {
		for (let i = 0; i < HDC.EL.product.variation.addOption.length; i++) {
			HDC.EL.product.variation.addOption[i].addEventListener("keyup", HDC.variations.add.checkAddOption);
		}

		for (let i = 0; i < HDC.EL.product.variation.options.length; i++) {
			HDC.EL.product.variation.options[i].addEventListener("click", HDC.removeOption);
		}

		for (let i = 0; i < HDC.EL.product.variation.variationItems.length; i++) {
			HDC.EL.product.variation.variationItems[i].firstChild.nextSibling.addEventListener(
				"click",
				HDC.removeVariation
			);
		}

		for (let i = 0; i < HDC.EL.product.variation.products.length; i++) {
			HDC.EL.product.variation.products[i].addEventListener("click", HDC.variations.openProductVariationModel);
		}
		
		let option_sort = document.getElementsByClassName("variation_options_wrapper");
		for(let i = 0; i < option_sort.length; i++){
			jQuery(option_sort[i]).sortable({
				cursor: "move",
				delay: 150,
				distance: 5,
				tolerance: "pointer",
				scrollSensitivity: 1,
				helper: "clone",
				placeholder: "variation_item_sort"
			});
		}
		
		/*
		// breaks purmutation calculation
		jQuery("#product_variations").sortable({
			cursor: "move",
			delay: 150,
			distance: 5,
			tolerance: "pointer",
			scrollSensitivity: 1,
			helper: "clone",
			placeholder: "variation_sort"
		});	
		*/
		
		
	},
	setImageListeners: function() {
		let image_fields = document.getElementsByClassName("input_image");
		
		let images = document.getElementsByClassName("gallery_field_image");
		for(let i = 0; i < images.length; i++){
			images[i].addEventListener("click", removeImageFromGallery);
		}
		
		let images_remove = document.getElementsByClassName("remove_image");
		for(let i = 0; i < images_remove.length; i++){
			images_remove[i].addEventListener("click", removeImage)
		}
		
		function removeImage(){
			let id = this.getAttribute("data-id");
			el = document.getElementById(id);
			el.setAttribute("data-value", "");
			el.innerHTML = "set image";
			this.parentNode.remove();
		}
		
		function removeImageFromGallery(){			
			// get new order
			let parent = this.getAttribute("data-parent");
			this.remove();
			let container = document.getElementById(parent + "_container");
			parent = document.getElementById(parent);
			let images = container.getElementsByClassName("gallery_field_image");
			let ids = [];
			for (let i = 0; i < images.length; i++) {
				ids.push(images[i].getAttribute("data-id"));
			}
			ids = ids.join(",");
			parent.setAttribute("data-value", ids);						
		}
		
		
		for (let i = 0; i < image_fields.length; i++) {
			image_fields[i].addEventListener("click", HDC.mediaUploader);
			let type = image_fields[i].getAttribute("data-type");
			if (type == "gallery") {
				let id = "#" + image_fields[i].getAttribute("id") + "_container";
				jQuery(id).sortable({
					cursor: "move",
					delay: 150,
					distance: 5,
					helper: "clone",
					placeholder: "image_item_sort",
					stop: function(event, ui) {
						// get new order
						let parent = ui.item[0].getAttribute("data-parent");
						let container = document.getElementById(parent + "_container");
						parent = document.getElementById(parent);
						let images = container.getElementsByClassName("gallery_field_image");
						let ids = [];
						for (let i = 0; i < images.length; i++) {
							ids.push(images[i].getAttribute("data-id"));
						}
						ids = ids.join(",");
						parent.setAttribute("data-value", ids);
					}
				});
			}
		}
	},
	createEditors: function() {
		let editors = document.getElementsByClassName("hderp_editor");
		for (let i = 0; i < editors.length; i++) {
			let parent = editors[i].parentNode.parentNode.parentNode;
			let tab = parent.getAttribute("data-tab");
			if (parent.getAttribute("data-required") == "required") {
				editors[i].setAttribute("data-required", "required");
			}
			editors[i].classList.add("hderp_input");
			editors[i].setAttribute("data-type", "editor");
			editors[i].setAttribute("data-tab", tab);
		}
	},
	getCleanName(v) {
		return v
			.toLowerCase()
			.replace(/ /g, "-")
			.replace(/[^\w-]+/g, "");
	},
	validate: {
		validateProduct: async function() {			
			if(HDC.EL.page.header.actions.save.classList.contains("saving")){
				// we are currently saving
				return;
			}
			
			let r = [];

			for (let i = 0; i < HDC.EL.product.inputs.length; i++) {
				const input = HDC.EL.product.inputs[i];
				let type = input.getAttribute("data-type");
				let data = {
					name: input.getAttribute("id"),
					type: type,
					required: input.getAttribute("data-required") || null,
					value: await HDC.validate.getValue(input, type),
					tab: input.getAttribute("data-tab")
				};
				r.push(data);
			}

			let required = await HDC.validate.checkRequiredFields(r);

			if (!required) {
				return;
			} else {
				let errors = await HDC.validate.checkForCustomValidation();
				if (errors.length === 0) {
					let required = document.getElementsByClassName("input_required");
					while (required.length > 0) {
						required[0].classList.remove("input_required");
					}

					// turn into associative
					let payload = {};
					for (let i = 0; i < r.length; i++) {
						payload[r[i].name] = r[i];
					}

					console.log("let's save!");
					await HDC.save(payload);
				} else {
					for (let i = 0; i < errors.length; i++) {
						for (let x = 0; x < errors[i].fields.length; x++) {
							errors[i].fields[x].classList.add("input_required");
						}
						console.warn(errors[i].message);
					}
				}
			}
		},
		getValue: async function(input, type) {
			let o = JSON.parse(decodeURIComponent(input.getAttribute("data-options"))) || {};
			let data = await HDC.getValueByType[type](input, o);
			return data;
		},
		checkRequiredFields: async function(r) {
			let valid = true;
			for (let i = 0; i < r.length; i++) {
				value = r[i].value;

				if (typeof value == "object") {
					value = r[i].value.text; // for editor
				}

				if (r[i].required === "required" && (value.length <= 0 || value == "\n")) {
					if (r[i].type == "editor") {
						document
							.getElementById("wp-" + r[i].name + "-editor-container")
							.classList.add("input_required");
					} else {
						document.getElementById(r[i].name).classList.add("input_required");
					}
					document
						.querySelector(`#tab_nav .tab_nav_item[data-id="${r[i].tab}"]`)
						.classList.add("input_required");
					valid = false;
				} else {
					if (r[i].type == "editor") {
						document
							.getElementById("wp-" + r[i].name + "-editor-container")
							.classList.remove("input_required");
					} else {
						document.getElementById(r[i].name).classList.remove("input_required");
					}
				}
			}

			let tabs = document.querySelectorAll("#tab_nav .tab_nav_item.input_required");
			for (let i = 0; i < tabs.length; i++) {
				let tab = tabs[i].getAttribute("data-id");
				let required = document.getElementById("tab_" + tab).querySelectorAll(".input_required");
				if (required.length === 0) {
					tabs[i].classList.remove("input_required");
				}
			}

			return valid;
		},
		checkForCustomValidation: async function() {
			{
				// sale price
				let errors = [];
				let price = document.getElementById("product_price");
				let sale = document.getElementById("product_sale_price");
				if (sale.value >= price.value) {
					errors.push({
						fields: [price, sale],
						message: "Your sale price is higher than your regular price"
					});
				}
				// TODO: add hooks so theme/addons can easily add custom validations/checks
				return errors;
			}
		}
	},
	variations: {
		openProductVariationModel: async function() {
			let active = document.getElementsByClassName("active_product_variation");
			if (active.length > 0) {
				active[0].classList.remove("active_product_variation");
			}
			this.classList.add("active_product_variation");

			let options = this.getAttribute("data-options");

			let title = this.getAttribute("data-name");
			let fields = await createProductVariationFields(options);
			const data = `<div id = "close_variation_options" title = "cancel">x</div>
		<div id = "product_variation_options_content">
			<h3 id = "product_variation_options_title">${title}</h3>
			${fields}
			<center><div id = "save_product_variation_options" class = "button button_primary">SAVE</div></center>
		</div>`;

			HDC.EL.product.variation.sidebar.innerHTML = data;
			HDC.EL.product.variation.sidebar.classList.add("active");

			document.getElementById("save_product_variation_options").addEventListener("click", saveVariationFields);
			document.getElementById("close_variation_options").addEventListener("click", function() {
				HDC.EL.product.variation.sidebar.innerHTML = "";
				HDC.EL.product.variation.sidebar.classList.remove("active");
			});

			function saveVariationFields() {
				let fields = document
					.getElementById("product_variation_sidebar")
					.querySelectorAll(".input_variation_field");

				let data = {};

				for (let i = 0; i < fields.length; i++) {
					// TODO: Send data to validator
					if (fields[i].getAttribute("data-type") == "radio") {
						let id = fields[i].getAttribute("data-id");
						console.log(id);
					} else {
						data[fields[i].getAttribute("data-id")] = fields[i].value;
					}
				}

				let active = document.getElementsByClassName("active_product_variation")[0];
				active.setAttribute("data-options", encodeURIComponent(JSON.stringify(data)));

				if(data.stock != "" && data.stock == 0){
					console.log("sadasdda");
					active.classList.add("permutation_no_stock")
				} else {
					active.classList.remove("permutation_no_stock")
				}
				
				HDC.EL.product.variation.sidebar.innerHTML = "";
				HDC.EL.product.variation.sidebar.classList.remove("active");
			}

			async function createProductVariationFields(options) {
				options = JSON.parse(decodeURIComponent(options)); // to get current values
				let variation_fields = JSON.parse(HDC.EL.product.variation.variationFields.fields); // to get fields

				// set fields value to options value
				for (let i = 0; i < variation_fields.length; i++) {
					for (let x = 0; x < variation_fields[i].length; x++) {
						if (typeof variation_fields[i][x].name != "undefined") {
							let n = variation_fields[i][x].name;
							if (options[n] != "" || options[n] === 0) {
								variation_fields[i][x].value = options[n];
							}
						}
					}
				}

				let data = "";
				for (let i = 0; i < variation_fields.length; i++) {
					if (variation_fields[i].length === 1) {
						data += '<div class = "row">';
					} else if (variation_fields[i].length === 2) {
						data += '<div class = "row col-1-1">';
					} else if (variation_fields[i].length >= 3) {
						data += '<div class = "row col-1-1-1">';
					}

					for (let x = 0; x < variation_fields[i].length; x++) {
						let type = variation_fields[i][x].type;
						data += await HDC.variations.createVariationField[type](variation_fields[i][x]);
					}

					data += "</div>";
				}
				return data;
			}
		},
		createVariationField: {
			seperator: async function(o) {
				return `<hr style = "width:100%; border: 1px solid #ddd;"/>`;
			},
			currency: async function(o) {
				if (o.default && o.value == "") {
					o.value = o.default;
				}
				return `<div class="input_item">
				<label class="input_label" for="variation_field_${o.name}">
					${o.label}
				</label>
				<div class="input_has_prefix">
					<div class="input_prefix">$</div>
					<input type="number" data-type="currency" step="0.01" min="0" max="999999999" class="input input_variation_field" data-id = "${o.name}" id="variation_field_${o.name}" value="${o.value}" placeholder="${o.placeholder}">
				</div>
			</div>`;
			},
			text: async function(o) {
				if (o.default && o.value == "") {
					o.value = o.default;
				}
				return `<div class="input_item">
				<label class="input_label" for="variation_field_${o.name}">
					${o.label}
				</label>		
				<input type="text" data-type="text" class="input input_variation_field" data-id = "${o.name}" id="variation_field_${o.name}" value="${o.value}" placeholder="${o.placeholder}">		
			</div>`;
			},
			integer: async function(o) {
				if (o.default && o.value == "") {
					o.value = o.default;
				}
				return `<div class="input_item">
				<label class="input_label" for="variation_field_${o.name}">
					${o.label}
				</label>			
				<input type="number" data-type="integer" min = "0" max = "99999999" step = "1" class="input input_variation_field" data-id = "${o.name}" id="variation_field_${o.name}" value="${o.value}" placeholder="${o.placeholder}">		
			</div>`;
			},
			float: async function(o) {
				if (o.default && o.value == "") {
					o.value = o.default;
				}
				return `<div class="input_item">
				<label class="input_label" for="variation_field_${o.name}">
					${o.label}
				</label>
				<input type="number" data-type="float" min = "0" step = "0.1" class="input input_variation_field" data-id = "${o.name}" id="variation_field_${o.name}" value="${o.value}" placeholder="${o.placeholder}">
			</div>`;
			},
			radio: async function(o) {
				let data = '<div class="input_item">';
				data += `<label class = "input_label" for="">${o.label}</label>`;
				for (let i = 0; i < o.options.length; i++) {
					let checked = "";
					if (o.value == o.options[i].value) {
						checked = "checked";
					}
					data += `<div class = "hdc_checkbox_container">			
					<div class="hdc_checkbox">			
					<input type="checkbox" onChange = "HDC.variations.variationFieldRadioSelect(this)" value="${o.options[i].value}" data-type = "radio" class = "hdc_checkbox_input" data-id = "${o.name}" id="variation_field_${o.options[i].value}" ${checked}>
					<label for="variation_field_${o.options[i].value}"></label>
					</div><label for="variation_field_${o.options[i].value}">${o.options[i].label}</label></div>`;
				}
				data += `<input type = "hidden" style = "display:none;" class = "input_variation_field" data-id = "${o.name}" id = "variation_field_${o.name}" value = "${o.value}"/></div>`;
				return data;
			},
			select: async function(o) {
				let options = `<option value = "-">-</option>`;
				for (let i = 0; i < o.options.length; i++) {
					let selected = "";
					if (o.value == o.options[i].value) {
						selected = "selected";
					}
					options += `<option value = "${o.options[i].value}" ${selected}>${o.options[i].label}</option>`;
				}
				return `<div class="input_item">
				<label class="input_label" for="variation_field_${o.name}">
					${o.label}
				</label>
				<select class="input input_variation_field" data-id = "${o.name}" id="variation_field_${o.name}">
					${options}
				</select>
			</div>`;
			}
		},
		variationFieldRadioSelect: function(el) {
			let v = el.value;
			let checked = false;
			let radios = el.parentNode.parentNode.parentNode.querySelectorAll(".hdc_checkbox_input");
			for (let i = 0; i < radios.length; i++) {
				if (radios[i] != el) {
					radios[i].checked = false;
				}

				if (radios[i].checked == true) {
					checked = true;
				}
			}

			if (!checked) {
				v = "";
			}
			let id = el.getAttribute("data-id");
			document.getElementById("variation_field_" + id).value = v;
		},
		add: {
			checkAddVariation: async function(e) {
				if (e.keyCode === 13) {
					let v = this.value.trim();
					if (v.length == 0) return;

					this.value = "";
					await HDC.variations.add.addVariation(v);
					HDC.setProductListeners();
				}
			},
			addVariation: async function(variation) {
				let name = await HDC.getCleanName(variation);

				let exists = false;

				for (let i = 0; i < HDC.EL.product.variation.variationItems.length; i++) {
					if (HDC.EL.product.variation.variationItems[i].getAttribute("data-slug") == name) {
						exists = true;
						break;
					}
				}

				if (exists) {
					return false;
				}

				let data = `<div class = "variation_item" data-name = "${variation}" data-slug = "${name}" id = "variation_${name}">
						<div data-id = "variation_${name}" class = "variation_item_delete" title = "delete this variation"></div>
						<h3 class = "variation_title">${variation}</h3>
						<div class="input_item">
							<label class="input_label" for="add_option_${name}">
								Add Variation Option
								<span class="tooltip">
									?
									<span class="tooltip_content">
										<span>Enter the variation options: Example: large, medium, small, or red, blue, green etc</span>
									</span>
								</span>
							</label>
							<input data-type="text" type="text" data-slug = "${name}" data-name = "${variation}" class="input variation_option input_enter" id="add_option_${name}" value="" placeholder="option name...">
						</div>
						<div class = "variation_options_wrapper"></div>
					</div>`;
				HDC.EL.product.variation.variations.insertAdjacentHTML("beforeend", data);
				HDC.setEnterInputListeners();
				HDC.setProductListeners();
			},
			checkAddOption: async function(e) {
				if (e.keyCode === 13) {
					let v = this.value.trim();
					if (v.length == 0) return;

					let s = this.getAttribute("data-slug");
					let n = this.getAttribute("data-name");
					this.value = "";
					await HDC.variations.add.addOption(v, s, n, this);
				}
			},
			addOption: async function(v, s, n, el) {
				let name = await HDC.getCleanName(v);

				let parent = el.parentNode.parentNode;
				let options = parent.querySelectorAll(".option_item");

				let exists = false;
				for (let i = 0; i < options.length; i++) {
					if (options[i].getAttribute("data-slug") == name) {
						exists = true;
						break;
					}
				}

				if (exists) {
					return;
				}

				let data = `<div class="option_item" data-variation = "${s}" data-variation-name = "${n}" data-slug = "${name}" data-name="${v}" title = "click to delete, drag to reorder">${v}</div>`;
				document.getElementById("variation_" + s).querySelector(".variation_options_wrapper").insertAdjacentHTML("beforeend", data);

				// ***************************************************
				HDC.setProductListeners();

				await HDC.variations.add.getPermutations();
			},
			getPermutations: async function() {
				let options = [...HDC.EL.product.variation.options];

				options = await createPermuteArray(options);
				options = await permute(options);

				await createProductVariations(options);

				async function createProductVariations(options) {
					let html = "";
					for (let i = 0; i < options.length; i++) {
						let d = null;
						let slug = options[i].slug;
						let product_permutations = document.getElementById("product_permutations");
						let existing = product_permutations.querySelector(`[data-slug="${slug}"]`);
						if (existing != null) {
							d = existing.getAttribute("data-options");
						} else {
							d = await HDC.EL.product.variation.variationFields.default();
							d = encodeURIComponent(d);
						}

						html += `<div class="product_item" data-options = "${d}" data-slug = "${options[i].slug}" data-name = "${options[i].name}" title = "select to set variation options">${options[i].title}</div>`;
					}

					HDC.EL.product.variation.permutations.innerHTML = html;

					HDC.setProductListeners();
				}

				async function permute(arr) {
					if (arr.length < 1) return [];
					if (arr.length === 1) return arr[0];

					const inner_arr = await permute(arr.slice(1));

					let f = [];
					arr[0].map(first);

					function first(v, i) {
						for (let i = 0; i < inner_arr.length; i++) {
							let d = {
								slug: v.slug + "|" + inner_arr[i].slug,
								title:
									v.title + '<span class = "product_item_name_spacer"></span>' + inner_arr[i].title,
								name: v.name + " " + inner_arr[i].name
							};
							f.push(d);
						}
					}
					return f;
				}

				async function createPermuteArray(options) {
					data = [];
					let variations = [];
					for (let i = 0; i < options.length; i++) {
						let variation = options[i].getAttribute("data-variation");
						let v = {
							slug:
								options[i].getAttribute("data-variation") + "*" + options[i].getAttribute("data-slug"),
							title: `<strong class = "product_item_name">${options[i].getAttribute(
								"data-variation-name"
							)}</strong>: ${options[i].getAttribute("data-name")}`,
							name:
								options[i].getAttribute("data-variation-name") +
								" " +
								options[i].getAttribute("data-name")
						};

						if (variations.includes(variation)) {
							let size = variations.length;
							data[size - 1].push(v);
						} else {
							data.push([v]);
							variations.push(variation);
						}
					}
					return data;
				}
			}
		},
		setProductAddVariationListener: function() {
			HDC.EL.product.variation.addVariation.addEventListener("keyup", HDC.variations.add.checkAddVariation);
		}
	},
	removeVariation: async function() {
		this.parentNode.remove();
		await HDC.variations.add.getPermutations();
	},
	removeOption: async function() {
		this.remove();
		await HDC.variations.add.getPermutations();
	},
	getValueByType: {
		text: async function(input, options) {
			return input.value;
		},
		currency: async function(input, options) {
			let d = input.value || null;
			if (d != null) {
				d = parseFloat(d);
				if (d > options.max) {
					d = options.max;
				}
				if (d < options.min) {
					d = options.min;
				}
				d = d.toFixed(2);

				input.value = d;
			} else {
				d = "";
			}
			return d;
		},
		integer: async function(input, options) {
			let d = input.value || null;
			if (d != null) {
				if (d > options.max) {
					d = options.max;
				}
				if (d < options.min) {
					d = options.min;
				}
				input.value = parseInt(d);
				return parseInt(d);
			} else {
				return "";
			}
		},
		float: async function(input, options) {
			let d = input.value || null;
			if (d != null) {
				if (d > options.max) {
					d = options.max;
				}
				if (d < options.min) {
					d = options.min;
				}
				input.value = parseFloat(d);
				return parseFloat(d);
			} else {
				return "";
			}
		},
		editor: async function(input, options) {
			await tinyMCE.triggerSave(); // trigger tinyMCE so we can get the value
			return input.value;
		},
		select: async function(input, options) {
			return input.options[input.selectedIndex].value;
		},
		image: async function(input, options) {
			return input.getAttribute("data-value");
		},
		gallery: async function(input, options) {
			return input.getAttribute("data-value");
		},
		categories: async function(input, options){
			let categories = [];
			for(let i = 0; i < HDC.EL.product.categories.length; i++){
				let cat = HDC.EL.product.categories[i];
				if(cat.checked){
					categories.push(cat.getAttribute("data-id"));
				}
			}
			return categories;
		},
		variations: async function(input, options) {
			let variations = HDC.EL.product.variation.variationItems;
			let data = [];
			for (let i = 0; i < variations.length; i++) {
				let options = variations[i].getElementsByClassName("option_item");
				if (options.length > 0) {
					let variation = {
						name: variations[i].getAttribute("data-name"),
						slug: variations[i].getAttribute("data-slug"),
						options: []
					};

					for (let i = 0; i < options.length; i++) {
						let option = {
							name: options[i].getAttribute("data-name"),
							slug: options[i].getAttribute("data-slug")
						};
						variation.options.push(option);
					}
					data.push(variation);
				}
			}
			return data;
		},
		permutations: async function(input, options) {
			let permutations = HDC.EL.product.variation.products;
			if (permutations.length == 0) {
				return "";
			}

			let data = [];
			for (let i = 0; i < permutations.length; i++) {
				let product = {
					options: JSON.parse(decodeURIComponent(permutations[i].getAttribute("data-options"))),
					name: permutations[i].getAttribute("data-name"),
					id: permutations[i].getAttribute("data-slug"),
					title: permutations[i].innerHTML
				};

				data.push(product);
			}
			return data;
		}
	},
	mediaUploader: function() {
		let el = this;
		let multiple = false;
		let options = this.getAttribute("data-options");
		options = decodeURIComponent(options);
		options = JSON.parse(options);
		if (options.multiple) {
			HDC.VARS.mediaFrame.multiple = options.multiple;
		}
		if (options.title) {
			HDC.VARS.mediaFrame.title = options.title;
		}
		if (options.button) {
			HDC.VARS.mediaFrame.button = options.button;
		}

		var type = this.getAttribute("data-type");

		if (type == "gallery") {
			multiple = true;
		}
		HDC.VARS.mediaFrame.multiple = multiple;

		// Create the media frame.
		HDC.VARS.mediaFrame.frame = wp.media.frames.file_frame = wp.media({
			title: HDC.VARS.mediaFrame.title,
			button: {
				text: HDC.VARS.mediaFrame.button
			},
			multiple: HDC.VARS.mediaFrame.multiple
		});

		// When an image is selected, run a callback.
		HDC.VARS.mediaFrame.frame.on("select", function() {
			let attachment = HDC.VARS.mediaFrame.frame.state().get("selection");
			if (type == "image") {
				setImage(attachment, el);
			} else if (type == "gallery") {
				setGallery(attachment, el);
			}
		});

		// Finally, open the modal
		HDC.VARS.mediaFrame.frame.open();

		async function setImage(attachment, el) {
			attachment = attachment.first().toJSON();
			if (attachment.type != "image") {
				alert("this is not an image");
				return;
			}
			let url = await getImageThumb(attachment.sizes);
			let id = attachment.id;
			let title = attachment.title;
			let data = `<img class = "image_field_image" src = "${url}" alt = "${title}" />`;
			el.innerHTML = data;
			el.setAttribute("data-value", id);
			if(el.nextElementSibling == null || !el.nextElementSibling.classList.contains("remove_image_wrapper")){
				id = el.getAttribute("id");
				data = `<p class = "remove_image_wrapper" style="text-align:center"><span class="remove_image" data-id="${id}">remove image</span></p>`;
				el.insertAdjacentHTML("afterend", data);
			}
			HDC.setImageListeners();
		}

		async function setGallery(attachments, el) {
			attachments = attachments.toJSON();
			let container = document.getElementById(el.getAttribute("id") + "_container");
			let arr = [];
			let gallery = el.getAttribute("data-value");
			if (gallery == "0") {
				gallery = [];
			} else {
				gallery = gallery.split(",");
			}

			for (let i = 0; i < attachments.length; i++) {
				if (attachments[i].type == "image") {
					let url = await getImageThumb(attachments[i].sizes);
					let id = attachments[i].id;
					let title = attachments[i].title;
					gallery.push(id);
					let data = `<img data-id = "${id}" data-parent = "${el.getAttribute(
						"id"
					)}" class = "gallery_field_image" title = "click to remove, or drag to reorder" src = "${url}" alt = "${title}" />`;
					container.insertAdjacentHTML("beforeend", data);
				}
			}

			gallery = gallery.join(",");
			el.setAttribute("data-value", gallery);
		}

		async function getImageThumb(sizes) {
			if (sizes.large) {
				return sizes.large.url;
			} else {
				return sizes.full.url;
			}
		}
	},
	save: async function(data) {
		HDC.EL.page.header.actions.save.classList.add("saving");
		HDC.EL.page.header.actions.save.innerHTML = "saving...";
		
		let slug = "";
		if (HDC.EL.page.slug != null) {
			slug = HDC.EL.page.slug.getAttribute("data-slug");
		}

		console.log(data);

		jQuery.ajax({
			type: "POST",
			data: {
				action: "hdc_add_new_product",
				hdc_product_id: await HDC.VARS.page(),
				slug: slug,
				payload: data,
				nonce: document.getElementById("hdc_meta_products_nonce").value
			},
			url: ajaxurl,
			success: function(data) {
				console.log(data);
				let json = JSON.parse(data);
				if (json.success) {
					adminpage = "post-php";
					document.getElementById("post_ID").value = json.success.id;
					if (HDC.EL.page.slug == null) {
						let permalink = json.success.permalink;
						permalink.replace("/" + json.success.slug, "");
						let html = `<div id="product_slug" data-slug="${json.success.slug}" data-permalink="${permalink}" title="update product slug (url)">${json.success.permalink}</div>
						<a href="${json.success.permalink}" target="_blank" id="view_product_page" data-id="view_product_page" class="button button_secondary" title="view product page">view product page</a>`;						
						HDC.EL.page.header.actions.wrapper.insertAdjacentHTML("afterbegin", html);
						HDC.EL.page.slug = document.getElementById("product_slug");
						HDC.setSlugListener();
					} else {
						document.getElementById("view_product_page").setAttribute("href", json.success.permalink);
					}
				}
			},
			complete: function() {
				HDC.EL.page.header.actions.save.classList.remove("saving");
				HDC.EL.page.header.actions.save.innerHTML = "SAVE";
			}
		});
	}
};
HDC.init();
