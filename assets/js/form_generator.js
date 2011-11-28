jQuery(function($){
	
	var generate;


	generate = {
	
		field : function(e){
			e.preventDefault();
			var type = $(this).data('type'),
				field = '', 
				label = '',
				desc  = '',
				elem  = '';

			label = generate._get_label();
			field = generate._get_field('test', type);

			$('#main-form').find('li:last').before('<li>'+label+field+'</li>');
			$('#main-form').sortable(generate.sortable);
		},

		sortable : {
			axis : 'y'
		},

		_get_label : function(text, description){
			if (text === null || text === undefined) {
				text = 'Sample label';
			}

			if (description === null || description === undefined) {
				description = '<small>Sample description</small>';
			} 

			return '<label>'+text+description+'</label>';
		},

		_get_field : function(name, type) {
			var field = '',
				commonAttrs = ' name="'+name+'" id="'+name+'" class="field" ';

			switch (type) {
				default:
				case 'text':
					field = '<input type="text"'+commonAttrs+'>';
					break;
				case 'textarea':
					field = '<textarea'+commonAttrs+'></textarea>';
					break;

			}
			return '<div>'+field+'</div>';
		},

		editField : function() {
			var label = $(this).find('label').html(),
				desc  = $(this).find('small').html(),
				name  = $(this).find('input').attr('name');

			$('.selected').removeClass('selected');
			$(this).addClass('selected');

			label = label.replace('<small>'+desc+'</small>', '');

			$('#settings-name').val(name);
			$('#settings-description').val(desc);
			$('#settings-label').val(label);
		},

		setLabel : function() {
			$('.selected').find('label').html(
				$('#settings-label').val()+'<small>'+$('#settings-description').val()+'</small>'
			);
		},

		setName : function() {
			$('.selected').find('.field').attr('name', $(this).val()).attr('id', $(this).val());
		},

		setClass : function() {
			var selected = $('.selected').find('.field');
			selected[0].className = selected[0].className.replace(/([\s]+|)span([0-9]+)([\s]+|)/ig, '');
			selected.addClass($(this).val());
		},

		submit : function(e) {
			var form = $(this).clone();
			form.find('li:last').remove();
			form.find('.ui-sortable').removeClass('ui-sortable');
			$('#content').val(form.html());
		}
			
	};


	$('#elements').find('a').live('click', generate.field);
	$('#main-form').find('li').live('click', generate.editField);
	$('#settings-name').keyup(generate.setName);
	$('#settings-label').keyup(generate.setLabel);
	$('#settings-description').keyup(generate.setLabel);
	$('#settings-class').change(generate.setClass);
	$('#main-form').parent().submit(generate.submit);

});