jQuery(document).ready(function($) {
	tinymce.PluginManager.add('emfw_button', function(editor, url) {
		function ajaxCall(tdata) {
			var retval;
			$.ajax({
				type:	"POST",
				url:	url + "/pluginajax.php",
				async: false,
				data:	{
					typeofdata: tdata,
					mydata: emfw_params.ajaxdata,
				},
				dataType: "text",
				success: function(msg) {
					retval = msg;
				}
			});
			return retval;
		}
		
		function showDialog() {
			var mclists = eval(ajaxCall('mclists'));
			var mylistids = eval(ajaxCall('styleslist'));
			if (tinyMCE.majorVersion == "3") {
				editor.windowManager.open({
					title: "MailChimp Form",
					url : url + '/oldtiny.htm',
	   			width : 320,
	   			height : 240,
	   			inline : 1,
				}, {
					title_label : emfw_params.texts[0],
					listid_label : emfw_params.texts[1],
					styleid_label : emfw_params.texts[2],
					listid_value : mclists,
					styleid_value : mylistids,
				});
			} else {
				editor.windowManager.open({
					title: "MailChimp Form",
					body: [
						{type: 'textbox', name: 'title', label: emfw_params.texts[0]},
						{type: 'listbox', name: 'listid', label: emfw_params.texts[1], values: mclists },
						{type: 'listbox', name: 'styleid', label: emfw_params.texts[2], values: mylistids }
	        ],
	        onsubmit: function(e) {
	        	var selected = editor.selection.getContent();
						var content = '[emfw_mailchimp_forms title="' + e.data.title + '" list="' + e.data.listid + '" style="' + e.data.styleid + '"]';
						if (selected) {
							// If text is selected when button is clicked
							// Wrap shortcode around it.
							content += selected + '[/emfw_mailchimp_forms]';
						}
						editor.execCommand('mceInsertContent', false, content);
	        }
				});
			}
		}
	
		editor.addButton('emfw_button', {
			image: url + '/plugin.png',
			title: emfw_params.texts[3],
			onclick: showDialog
		});
	
	});
});